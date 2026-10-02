<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::query()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('users.form', ['user' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($user->is(auth()->user()) && $validated['role'] !== $user->role) {
            return back()->withErrors(['role' => 'Anda tidak dapat mengubah peran akun yang sedang digunakan.'])->withInput();
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->withErrors(['role' => 'Sistem harus memiliki setidaknya satu admin.'])->withInput();
        }

        if (blank($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')
                ->withErrors(['user' => 'Anda tidak dapat menghapus akun yang sedang digunakan.']);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return redirect()->route('users.index')
                ->withErrors(['user' => 'Sistem harus memiliki setidaknya satu admin.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
