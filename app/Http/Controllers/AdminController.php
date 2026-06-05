<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Shoe;
use App\Models\ShoeVariations;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $adminUser = $request->user();
        $stats = [
            ['label' => 'Total Users', 'value' => User::count(), 'note' => 'Registered accounts'],
            ['label' => 'Admin Accounts', 'value' => User::where('role', 'admin')->count(), 'note' => 'Privileged access'],
            ['label' => 'Brands', 'value' => Brand::count(), 'note' => 'Active catalog brands'],
            ['label' => 'Shoes', 'value' => Shoe::count(), 'note' => 'Products in catalog'],
            ['label' => 'Orders', 'value' => Order::count(), 'note' => 'Placed purchases'],
        ];

        $recentShoes = Shoe::with('brand')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();
        return view('admin.dashboard', compact('adminUser', 'stats', 'recentShoes', 'recentUsers'));
    }

    public function updateUser(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,customer'],
        ]);

        $user = User::findOrFail($userId);

        $user->forceFill($validated)->save();

        return back()->with('success', 'User updated successfully.');
    }

    public function deleteUser(int $userId): RedirectResponse
    {
        if (Auth::id() === $userId) {
            return back()->with('error', 'You cannot delete the account you are currently using.');
        }

        $user = User::findOrFail($userId);
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function updateOrder(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,shipping,delivered,cancelled'],
        ]);

        $order = Order::findOrFail($orderId);
        $order->update($validated);

        return back()->with('success', 'Order updated successfully.');
    }

    public function deleteOrder(int $orderId): RedirectResponse
    {
        $order = Order::findOrFail($orderId);
        $order->delete();

        return back()->with('success', 'Order deleted successfully.');
    }
}