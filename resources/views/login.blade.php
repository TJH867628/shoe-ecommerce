@extends('layout')

@section('title', 'Login')

@section('content')
<main class="flex items-center justify-center min-h-[70vh] px-6 py-16">
    <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-100">
        <h2 class="text-3xl font-black mb-2">Welcome Back</h2>
        <p class="text-slate-500 mb-8">Enter your credentials to access your account.</p>
        
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all">
            </div>
            
            <button type="submit" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                Sign In
            </button>
        </form>
        
        <p class="text-center text-sm text-slate-500 mt-8">
            Don't have an account? <a href="{{ route('register') }}" class="font-bold text-slate-900 hover:underline">Sign up now</a>
        </p>
    </div>
</main>
@endsection