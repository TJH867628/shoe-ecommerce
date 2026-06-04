<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class UserController extends Controller
{
    /**
     * Show the login page
     */
    public function loginPage()
    {
        return view('login');
    }

    /**
     * Show the register page
     */
    public function registerPage()
    {
        return view("register");
    }

    /**
     * Handle login form submission
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check "remember me" checkbox
        $remember = $request->boolean('remember');

        // Attempt authentication with remember me
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            $user = Auth::user();
            $redirectTo = $user?->role === 'admin'
                ? route('admin.dashboard')
                : url('/');

            return redirect()
                ->intended($redirectTo)
                ->with('success', 'You have been logged in successfully!');
        }

        // Authentication failed
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }

    /**
     * Handle register form submission
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        try {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
            ]);

            return redirect()
                ->route('login')
                ->with('success', 'Account created successfully! Please log in.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Unable to create account. Please try again.');
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully!');
    }

    /**
     * Show user profile and orders
     */
    public function profile()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to view your profile.');
        }

        $orders = Order::with(['items.variation.shoe.brand','payment'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
            

        return view('user.profile', compact('user', 'orders'));
    }
}