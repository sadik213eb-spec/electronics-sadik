<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect('/account');
        }

        if (request('redirect')) {
            // Use route() or a clean path to avoid double slashes //
            $redirectPath = request('redirect');
            session()->put('url.intended', url($redirectPath));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('customer')->attempt($credentials, $request->remember)) {

            // ✅ Merge guest cart into customer cart
            $sessionId = session()->getId();
            $customerId = Auth::guard('customer')->id();

            Cart::where('session_id', $sessionId)->each(function ($item) use ($customerId) {
                $existing = Cart::where('user_id', $customerId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($existing) {
                    $existing->increment('quantity', $item->quantity);
                    $item->delete();
                } else {
                    $item->update(['user_id' => $customerId, 'session_id' => null]);
                }
            });

            $intended = session()->get('url.intended', '/account');
            session()->forget('url.intended');

            return redirect($intended);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('customer')->login($customer);

        // ✅ Check if intended URL exists after register too
        $intended = session()->get('url.intended', '/account');
        session()->forget('url.intended');

        return redirect($intended);
    }

    public function logout()
    {
        Auth::guard('customer')->logout();

        return redirect('/login');
    }
}
