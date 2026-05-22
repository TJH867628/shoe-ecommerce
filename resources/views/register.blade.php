@extends('layout')

@section('title', 'Register')

@section('content')
<main class="flex items-center justify-center min-h-[70vh] px-6 py-16">
    <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-100">
        <h2 class="text-3xl font-black mb-2">Create Account</h2>
        <p class="text-slate-500 mb-8">Join the community for exclusive drops.</p>
        
        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all">
            </div>
            
            <button type="submit" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                Register
            </button>
        </form>
        
        <p class="text-center text-sm text-slate-500 mt-8">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-slate-900 hover:underline">Sign in</a>
        </p>
    </div>
</main>
@endsection