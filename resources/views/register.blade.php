@extends('layout')

@section('title', 'Register')

@section('content')
<main class="flex items-center justify-center min-h-[70vh] px-6 py-16">
    <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-100">
        <h2 class="text-3xl font-black mb-2">Create Account</h2>
        <p class="text-slate-500 mb-8">Join the community for exclusive drops.</p>
        
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <p class="text-red-700 font-bold text-sm">❌ Registration Failed</p>
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-sm mt-1">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <p class="text-red-700 font-bold text-sm">❌ {{ session('error') }}</p>
            </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}"
                    required 
                    class="w-full bg-slate-50 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-200' }} rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all"
                    placeholder="John Doe"
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    class="w-full bg-slate-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-200' }} rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all"
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    class="w-full bg-slate-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }} rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all"
                    placeholder="At least 8 characters"
                >
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number (Optional)</label>
                <input 
                    type="tel" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    class="w-full bg-slate-50 border {{ $errors->has('phone') ? 'border-red-500' : 'border-slate-200' }} rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900 transition-all"
                    placeholder="+60123456789"
                >
                @error('phone')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
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