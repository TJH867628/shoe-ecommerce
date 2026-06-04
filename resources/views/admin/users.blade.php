@extends('admin.layout')

@section('title', 'Users')
@section('eyebrow', 'Admin Directory')
@section('page-title', 'Users')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">Users</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Edit user details, change roles, or remove accounts.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-arrow-left"></i>Back to Dashboard</a>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Users</p>
        <p class="text-3xl font-black mt-3">{{ $users->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Admins</p>
        <p class="text-3xl font-black mt-3">{{ $roleSummary['admin'] ?? 0 }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Customers</p>
        <p class="text-3xl font-black mt-3">{{ $roleSummary['customer'] ?? 0 }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Roles</p>
        <p class="text-3xl font-black mt-3">2</p>
    </div>
</div>

<section class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">User Directory</p>
            <h2 class="text-2xl font-black mt-2">Edit and remove accounts</h2>
        </div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">Live CRUD</span>
    </div>
    <div class="overflow-x-auto p-6 md:p-8">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Joined</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($users as $user)
                <tr>
                    <td class="px-4 py-3 align-top">
                        <div class="font-bold">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500">ID: {{ $user->id }}</div>
                    </td>
                    <td class="px-4 py-3 align-top text-slate-600">{{ $user->email }}</td>
                    <td class="px-4 py-3 align-top text-slate-600">{{ $user->phone }}</td>
                    <td class="px-4 py-3 align-top">
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="flex items-center gap-2">@csrf @method('PUT')<select name="role" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none">
                                <option value="customer" @selected($user->role === 'customer')>customer</option>
                                <option value="admin" @selected($user->role === 'admin')>admin</option>
                            </select><button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">Save</button></form>
                    </td>
                    <td class="px-4 py-3 align-top text-slate-600">{{ $user->created_at?->format('M d, Y') }}</td>
                    <td class="px-4 py-3 align-top">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 pb-6">
        {{ $users->links() }}
    </div>
</section>
@endsection