<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate   = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
        $toDate     = $request->input('to_date', Carbon::now()->toDateString());
        $categoryId = $request->input('category_id');
        $type       = $request->input('type');

        $query = StockTransaction::with('product.category', 'user')
            ->whereBetween('transaction_date', [$fromDate, $toDate]);

        if ($categoryId) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $categoryId));
        }
        if ($type && in_array($type, ['masuk', 'keluar'])) {
            $query->where('type', $type);
        }

        $transactions = $query->orderByDesc('transaction_date')->get();
        $totalMasuk   = $transactions->where('type', 'masuk')->sum('quantity');
        $totalKeluar  = $transactions->where('type', 'keluar')->sum('quantity');
        $products     = Product::with('category')->orderBy('name')->get();
        $categories   = Category::orderBy('name')->get();
        $lowStock     = $products->filter(fn(Product $p) => $p->isLowStock());

        $chartLabels = [];
        $chartMasuk  = [];
        $chartKeluar = [];
        for ($i = 5; $i >= 0; $i--) {
            $month         = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M');
            $chartMasuk[]  = StockTransaction::where('type', 'masuk')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)->sum('quantity');
            $chartKeluar[] = StockTransaction::where('type', 'keluar')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)->sum('quantity');
        }

        return view('reports.index', compact(
            'transactions', 'totalMasuk', 'totalKeluar', 'products',
            'categories', 'lowStock', 'fromDate', 'toDate',
            'chartLabels', 'chartMasuk', 'chartKeluar'
        ));
    }

    public function export(Request $request)
    {
        $fromDate   = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
        $toDate     = $request->input('to_date', Carbon::now()->toDateString());
        $categoryId = $request->input('category_id');
        $type       = $request->input('type');

        $query = StockTransaction::with('product.category', 'user')
            ->whereBetween('transaction_date', [$fromDate, $toDate]);

        if ($categoryId) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $categoryId));
        }
        if ($type && in_array($type, ['masuk', 'keluar'])) {
            $query->where('type', $type);
        }

        $transactions = $query->orderByDesc('transaction_date')->get();
        $totalMasuk   = $transactions->where('type', 'masuk')->sum('quantity');
        $totalKeluar  = $transactions->where('type', 'keluar')->sum('quantity');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Stok');

        // Baris 1: Judul
        $sheet->setCellValue('A1', 'Laporan Stok — ' . $fromDate . ' s/d ' . $toDate);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF4A3728']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Baris 2: Ringkasan
        $sheet->setCellValue('A2', 'Total Masuk: ' . $totalMasuk);
        $sheet->setCellValue('D2', 'Total Keluar: ' . $totalKeluar);
        $sheet->getStyle('A2')->getFont()->setBold(true)->getColor()->setARGB('FF2E7D32');
        $sheet->getStyle('D2')->getFont()->setBold(true)->getColor()->setARGB('FFE65100');
        $sheet->getRowDimension(2)->setRowHeight(18);

        // Baris 3: kosong
        $sheet->getRowDimension(3)->setRowHeight(6);

        // Baris 4: Header kolom
        $headers = ['Tanggal', 'Bahan Baku', 'Kategori', 'Tipe', 'Jumlah', 'Stok Setelah', 'Keterangan'];
        $sheet->fromArray([$headers], null, 'A4');
        $sheet->getStyle('A4:G4')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4A3728']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Lebar kolom
        foreach (['A' => 14, 'B' => 25, 'C' => 18, 'D' => 10, 'E' => 10, 'F' => 14, 'G' => 30] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Data mulai baris 5
        foreach ($transactions as $i => $tx) {
            $row = $i + 5;
            $sheet->setCellValue("A{$row}", $tx->transaction_date->format('Y-m-d'));
            $sheet->setCellValue("B{$row}", $tx->product->name ?? '-');
            $sheet->setCellValue("C{$row}", $tx->product->category->name ?? '-');
            $sheet->setCellValue("D{$row}", $tx->type === 'masuk' ? 'Masuk' : 'Keluar');
            $sheet->setCellValue("E{$row}", ($tx->type === 'masuk' ? '+' : '-') . $tx->quantity);
            $sheet->setCellValue("F{$row}", $tx->stock_after);
            $sheet->setCellValue("G{$row}", $tx->note ?: '-');

            $color = $tx->type === 'masuk' ? 'FF2E7D32' : 'FFE65100';
            $sheet->getStyle("D{$row}")->getFont()->getColor()->setARGB($color);
            $sheet->getStyle("E{$row}")->getFont()->getColor()->setARGB($color);

            if ($i % 2 === 0) {
                $sheet->getStyle("A{$row}:G{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF9F5F2');
            }
        }

        // Border
        $lastRow = $transactions->count() + 4;
        $sheet->getStyle("A4:G{$lastRow}")->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $filename = 'laporan_stok_' . $fromDate . '_sd_' . $toDate . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new XlsxWriter($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $fromDate   = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
        $toDate     = $request->input('to_date', Carbon::now()->toDateString());
        $categoryId = $request->input('category_id');
        $type       = $request->input('type');

        $query = StockTransaction::with('product.category', 'user')
            ->whereBetween('transaction_date', [$fromDate, $toDate]);

        if ($categoryId) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $categoryId));
        }
        if ($type && in_array($type, ['masuk', 'keluar'])) {
            $query->where('type', $type);
        }

        $transactions = $query->orderByDesc('transaction_date')->get();
        $totalMasuk   = $transactions->where('type', 'masuk')->sum('quantity');
        $totalKeluar  = $transactions->where('type', 'keluar')->sum('quantity');
        $products     = Product::with('category')->orderBy('name')->get();
        $lowStock     = $products->filter(fn(Product $p) => $p->isLowStock());
        $exportedAt   = now()->format('d M Y, H:i');

        // Label kategori yang dipilih (untuk ditampilkan di PDF)
        $categoryLabel = 'Semua Kategori';
        if ($categoryId) {
            $cat = Category::find($categoryId);
            $categoryLabel = $cat ? $cat->name : 'Semua Kategori';
        }

        $typeLabel = match($type) {
            'masuk'  => 'Masuk',
            'keluar' => 'Keluar',
            default  => 'Semua',
        };

        $pdf = Pdf::loadView('reports.export-pdf', compact(
            'transactions', 'totalMasuk', 'totalKeluar',
            'products', 'lowStock',
            'fromDate', 'toDate',
            'categoryLabel', 'typeLabel', 'exportedAt'
        ))->setPaper('a4', 'landscape');

        $filename = 'laporan_stok_' . $fromDate . '_sd_' . $toDate . '.pdf';

        return $pdf->download($filename);
    }
}