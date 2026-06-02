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
}
