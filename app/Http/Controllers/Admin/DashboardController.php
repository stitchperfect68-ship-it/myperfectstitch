<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'       => Order::count(),
            'orders_this_month'  => Order::whereMonth('created_at', now()->month)->count(),
            'revenue_this_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)->sum('amount'),
            'total_revenue'      => Payment::where('status', 'completed')->sum('amount'),
            'pending_quotes'     => Quote::where('status', 'new')->count(),
            'total_customers'    => Customer::count(),
            'total_products'     => Product::count(),
            'low_stock'          => Product::where('track_stock', true)->where('stock_qty', '<=', 5)->count(),
        ];

        $recentOrders = Order::with('items')->latest()->limit(8)->get();
        $pendingQuotes = Quote::where('status', 'new')->latest()->limit(5)->get();

        $revenueChart = Payment::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $orderStatusChart = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'pendingQuotes',
            'revenueChart', 'orderStatusChart', 'topProducts'
        ));
    }
}
