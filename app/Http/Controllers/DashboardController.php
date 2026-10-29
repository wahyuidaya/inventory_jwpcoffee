<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $products        = Product::with('category')->get();
        $lowStock        = $products->filter(fn($p) => $p->isLowStock());
        $top5            = $products->sortByDesc('stock')->take(5);
        $recentTx        = StockTransaction::with('product', 'user')
                            ->orderByDesc('created_at')->take(5)->get();
        $txMasukCount    = StockTransaction::where('type', 'masuk')->count();
        $txKeluarCount   = StockTransaction::where('type', 'keluar')->count();

        // Data grafik 30 hari terakhir (masuk & keluar per hari)
        $chartLabels = [];
        $chartMasuk  = [];
        $chartKeluar = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartLabels[] = Carbon::now()->subDays($i)->format('d/m');
            $chartMasuk[]  = StockTransaction::where('type', 'masuk')
                                ->whereDate('transaction_date', $date)->sum('quantity');
            $chartKeluar[] = StockTransaction::where('type', 'keluar')
                                ->whereDate('transaction_date', $date)->sum('quantity');
        }

        return view('dashboard.index', compact(
            'products', 'lowStock', 'top5', 'recentTx',
            'txMasukCount', 'txKeluarCount',
            'chartLabels', 'chartMasuk', 'chartKeluar'
        ));
    }
}