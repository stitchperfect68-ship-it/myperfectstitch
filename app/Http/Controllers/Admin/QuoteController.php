<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quote;
use App\Services\BroadpayLincoService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('ref', 'like', '%' . $request->search . '%');
            });
        }

        $quotes = $query->paginate(20)->withQueryString();

        return view('admin.quotes.index', compact('quotes'));
    }

    public function show(Quote $quote)
    {
        return view('admin.quotes.show', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'status'        => 'required|in:new,reviewed,quoted,converted,cancelled',
            'admin_notes'   => 'nullable|string|max:2000',
            'quoted_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validated['status'] === 'quoted' && !isset($validated['quoted_amount'])) {
            return back()->withErrors(['quoted_amount' => 'A quoted amount is required when status is "quoted".']);
        }

        $quote->update($validated);

        return redirect()->route('admin.quotes.show', $quote)->with('success', 'Quote updated.');
    }

    public function sendPaymentLink(Quote $quote, BroadpayLincoService $linco)
    {
        if (!$quote->quoted_amount) {
            return back()->with('error', 'Set a quoted amount before sending a payment link.');
        }

        $order = Order::create([
            'ref'            => Order::generateRef(),
            'customer_name'  => $quote->name,
            'customer_phone' => $quote->phone,
            'customer_email' => $quote->email,
            'quote_id'       => $quote->id,
            'status'         => 'pending_payment',
            'subtotal'       => $quote->quoted_amount,
            'total'          => $quote->quoted_amount,
        ]);

        $payment = $linco->initiatePayment($order);

        $quote->update([
            'status'       => 'quoted',
            'payment_link' => $payment['url'],
        ]);

        // TODO: send WhatsApp/SMS/email to customer with payment link

        return back()->with('success', 'Payment link generated and order created.');
    }

    public function convertToOrder(Quote $quote)
    {
        if (!$quote->quoted_amount) {
            return back()->with('error', 'Set a quoted amount first.');
        }

        $order = Order::create([
            'ref'            => Order::generateRef(),
            'customer_name'  => $quote->name,
            'customer_phone' => $quote->phone,
            'customer_email' => $quote->email,
            'quote_id'       => $quote->id,
            'status'         => 'processing',
            'subtotal'       => $quote->quoted_amount,
            'total'          => $quote->quoted_amount,
        ]);

        $quote->update(['status' => 'converted']);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Quote converted to order.');
    }
}
