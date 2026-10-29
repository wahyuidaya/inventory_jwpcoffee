<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;

class StockTransactionController extends Controller
{
    public function masuk(Request $request)
    {
        $query = StockTransaction::with('product', 'user')->where('type', 'masuk');
        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->paginate(10);
        return view('transactions.masuk', compact('transactions'));
    }

    public function keluar(Request $request)
    {
        $query = StockTransaction::with('product', 'user')->where('type', 'keluar');
        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->paginate(10);
        return view('transactions.keluar', compact('transactions'));
    }

    public function riwayat(Request $request)
    {
        $query = StockTransaction::with('product', 'user');

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->paginate(10);
        return view('transactions.riwayat', compact('transactions'));
    }

    public function export(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Transaksi');

        // Judul laporan dengan info rentang tanggal
        $dateRange = $this->resolveDateRange($request);

        $sheet->setCellValue('A1', 'Riwayat Transaksi — ' . $dateRange);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF4A3728']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Header kolom di baris 2
        $headers = ['#', 'Tanggal', 'Bahan Baku', 'Tipe', 'Jumlah', 'Stok Setelah', 'Oleh', 'Keterangan'];
        $sheet->fromArray([$headers], null, 'A2');
        $sheet->getStyle('A2:H2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4A3728']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        foreach (['A' => 5, 'B' => 14, 'C' => 25, 'D' => 10, 'E' => 10, 'F' => 14, 'G' => 18, 'H' => 30] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Data mulai baris 3
        foreach ($transactions as $i => $tx) {
            $row = $i + 3;
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $tx->transaction_date->format('Y-m-d'));
            $sheet->setCellValue("C{$row}", $tx->product->name ?? '-');
            $sheet->setCellValue("D{$row}", $tx->type === 'masuk' ? 'Masuk' : 'Keluar');
            $sheet->setCellValue("E{$row}", ($tx->type === 'masuk' ? '+' : '-') . $tx->quantity);
            $sheet->setCellValue("F{$row}", $tx->stock_after);
            $sheet->setCellValue("G{$row}", $tx->user->name ?? '-');
            $sheet->setCellValue("H{$row}", $tx->note ?: '-');

            $color = $tx->type === 'masuk' ? 'FF16A34A' : 'FFDC2626';
            $sheet->getStyle("D{$row}")->getFont()->getColor()->setARGB($color);
            $sheet->getStyle("E{$row}")->getFont()->getColor()->setARGB($color);

            if ($i % 2 === 0) {
                $sheet->getStyle("A{$row}:H{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF9F5F2');
            }
        }

        $lastRow = $transactions->count() + 2;
        $sheet->getStyle("A2:H{$lastRow}")->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $filename = 'riwayat_transaksi_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new XlsxWriter($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->get();

        $dateRange  = $this->resolveDateRange($request);
        $exportedAt = now()->format('d M Y, H:i');

        $pdf = Pdf::loadView('transactions.export-pdf', compact('transactions', 'dateRange', 'exportedAt'))
            ->setPaper('a4', 'landscape');

        $filename = 'riwayat_transaksi_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function resolveDateRange(Request $request): string
    {
        if ($request->filled('date_from') && $request->filled('date_to')) {
            return $request->date_from . ' s/d ' . $request->date_to;
        } elseif ($request->filled('date_from')) {
            return 'Dari ' . $request->date_from;
        } elseif ($request->filled('date_to')) {
            return 'Sampai ' . $request->date_to;
        }
        return 'Semua Tanggal';
    }

    // ---------------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------------

    public function create(string $type)
    {
        abort_unless(in_array($type, ['masuk', 'keluar']), 404);
        $products = Product::with('category')->orderBy('name')->get();
        return view('transactions.create', compact('type', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'type'             => 'required|in:masuk,keluar',
            'quantity'         => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'note'             => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->type === 'keluar' && $request->quantity > $product->stock) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi!'])->withInput();
        }

        DB::transaction(function () use ($request, $product) {
            $stockAfter = $request->type === 'masuk'
                ? $product->stock + $request->quantity
                : $product->stock - $request->quantity;

            StockTransaction::create([
                'product_id'       => $product->id,
                'user_id'          => Auth::id(),
                'type'             => $request->type,
                'quantity'         => $request->quantity,
                'stock_before'     => $product->stock,
                'stock_after'      => $stockAfter,
                'transaction_date' => $request->transaction_date,
                'note'             => $request->note,
            ]);

            $product->update(['stock' => $stockAfter]);
        });

        $route = $request->type === 'masuk' ? 'persediaan.masuk' : 'persediaan.keluar';
        return redirect()->route($route)->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show($id)
    {
        $transaction = StockTransaction::with('product', 'user')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }
}