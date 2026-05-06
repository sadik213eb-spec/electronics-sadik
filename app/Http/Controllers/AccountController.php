<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // ✅ Account dashboard
    public function index()
    {
        $customer = $this->customer();
        $recentOrders = $customer->orders()
            ->with('orderItems.product')
            ->latest()
            ->limit(5)
            ->get();

        return view('account.index', compact('customer', 'recentOrders'));
    }

    // ✅ All orders
    public function orders()
    {
        $customer = $this->customer();
        $orders = $customer->orders()
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('customer', 'orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->with('orderItems.product')
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }

    // ✅ Addresses
    public function addresses()
    {
        $customer = $this->customer();

        $addresses = $customer->addresses()->get();

        return view('account.addresses', compact('customer', 'addresses'));
    }

    // --- Wishlist----------------
    public function wishlist()
    {
        $customer = $this->customer();

        return view('account.wishlist', compact('customer'));
    }

    // ---------- Update profile---------------------------
    public function updateProfile(Request $request)
    {
        $customer = $this->customer();

        // 1. Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'birthday' => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Assign values manually (Bypasses Mass Assignment issues)
        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->birthday = $request->birthday;

        // 3. Handle Photo Upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($customer->profile_photo) {
                Storage::disk('public')->delete($customer->profile_photo);
            }

            // Store new photo and get the path
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $customer->profile_photo = $path;
        }

        // 4. Save to Database
        $customer->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    // Store Address
    public function storeAddress(Request $request)
    {
        $customer = $this->customer();

        $request->validate([
            'type' => 'required|in:home,office,other',
            'shipping_name' => 'required|string|max:255',
            'shipping_mobile' => 'required|string|max:15',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
        ]);

        $customer->addresses()->create([
            'type' => $request->type,
            'name' => $request->shipping_name,
            'mobile' => $request->shipping_mobile,
            'address' => $request->shipping_address,
            'city' => $request->shipping_city,
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $customer = $this->customer();

        if (! Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
