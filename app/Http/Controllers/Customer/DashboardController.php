<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function orders()
    {
        $customer = auth('customer')->user();
        $orders = $customer->orders()->with('items', 'payment')->latest()->paginate(10);

        return view('customer.orders', compact('orders', 'customer'));
    }

    public function showOrder(string $ref)
    {
        $customer = auth('customer')->user();
        $order = $customer->orders()->with('items', 'payment')->where('ref', $ref)->firstOrFail();

        return view('customer.order-show', compact('order', 'customer'));
    }

    public function profile()
    {
        $customer = auth('customer')->user();
        return view('customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|unique:customers,email,' . $customer->id,
            'password'     => 'nullable|string|min:8|confirmed',
        ]);

        $customer->update([
            'name'     => $validated['name'],
            'email'    => $validated['email'] ?? $customer->email,
            'password' => $validated['password'] ? Hash::make($validated['password']) : $customer->password,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function profileData()
    {
        $customer = auth('customer')->user();
        $orders   = $customer->orders()->with('items', 'payment')->latest()->get();

        $nameParts = explode(' ', trim($customer->name));
        $initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

        return response()->json([
            'customer' => [
                'id'           => $customer->id,
                'name'         => $customer->name,
                'email'        => $customer->email,
                'phone'        => $customer->phone,
                'initials'     => $initials,
                'member_since' => $customer->created_at->format('M Y'),
            ],
            'stats' => [
                'total_orders'  => $orders->count(),
                'total_spent'   => $orders->whereIn('status', ['paid','processing','ready','dispatched','delivered'])->sum('total'),
                'active_orders' => $orders->whereIn('status', ['paid','processing','ready','dispatched'])->count(),
            ],
            'orders' => $orders->map(fn($o) => [
                'ref'          => $o->ref,
                'status'       => $o->status,
                'status_label' => $o->status_label,
                'status_color' => $o->status_color,
                'total'        => $o->total,
                'items_count'  => $o->items->count(),
                'date'         => $o->created_at->format('d M Y'),
            ])->values(),
        ]);
    }

    public function updateProfileAjax(Request $request)
    {
        $customer  = auth('customer')->user();
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);
        $customer->update($validated);
        return response()->json(['success' => true, 'name' => $customer->name]);
    }
}
