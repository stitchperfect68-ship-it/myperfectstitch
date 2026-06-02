<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenue(Request $request)
    {
        $year = $request->input('year', now()->year);

        $monthly = Payment::where('status', 'completed')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = [
                'month'   => date('M', mktime(0, 0, 0, $m, 1)),
                'total'   => $monthly->get($m)?->total ?? 0,
                'count'   => $monthly->get($m)?->count ?? 0,
            ];
        }

        return response()->json($data);
    }

    public function export(Request $request)
    {
        $orders = Order::with(['items', 'payment'])
            ->when($request->from, fn ($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn ($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()->get();

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=orders-export.csv'];

        $callback = function () use ($orders) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Ref', 'Customer', 'Phone', 'Status', 'Total', 'Payment', 'Date']);
            foreach ($orders as $order) {
                fputcsv($out, [
                    $order->ref,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->status_label,
                    'K' . number_format($order->total, 2),
                    $order->payment?->status ?? 'none',
                    $order->created_at->format('Y-m-d'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
