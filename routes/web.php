<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// AUTENTIKASI
// ==========================================

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {

        $request->session()->regenerate();

        if (auth()->user()->role === 'admin') {
            return redirect()->intended('/dashboard');
        }

        return redirect()->intended('/pos');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah, wak!',
    ])->onlyInput('email');

})->name('login.post');


Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');

})->name('logout');


Route::get('/profile', function () {
    return view('profile.edit');
})->name('profile.edit')->middleware('auth');


// ==========================================
// UMUM ADMIN & KASIR
// ==========================================

Route::middleware(['auth'])->group(function () {

    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos');

});


// ==========================================
// KHUSUS ADMIN
// ==========================================

Route::middleware(['auth'])->group(function () {

    Route::group([
        'middleware' => function ($request, $next) {

            if (auth()->user()->role !== 'admin') {
                abort(403, 'Maaf, halaman ini khusus Administrator.');
            }

            return $next($request);
        }

    ], function () {

        // ======================================
        // DASHBOARD
        // ======================================

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');


        // ======================================
        // MASTER PRODUK
        // ======================================

        Route::get('/products', function () {

            $products = [];

            return view('products.index', compact('products'));

        })->name('products.index');


        Route::get('/products/create', function () {

            $categories = Category::all();

            return view('products.create', compact('categories'));

        })->name('products.create');


        Route::post('/products', function (Request $request) {

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        })->name('products.store');


        Route::get('/products/{id}/edit', function ($id) {

            return view('products.edit', compact('id'));

        })->name('products.edit');


        Route::put('/products/{id}', function (Request $request, $id) {

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil diperbarui!');

        })->name('products.update');


        Route::delete('/products/{id}', function ($id) {

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil dihapus!');

        })->name('products.destroy');


        // ======================================
        // MASTER KATEGORI
        // ======================================

        Route::get('/categories', function () {

            $categories = Category::all();

            return view('categories.index', compact('categories'));

        })->name('categories.index');


        // ======================================
        // MASTER PENGGUNA
        // ======================================

        Route::get('/users', function () {

            $users = User::all();

            return view('users.index', compact('users'));

        })->name('users.index');


        // ======================================
        // LAPORAN
        // ======================================

        Route::get('/reports', function () {

            return view('reports.index');

        })->name('reports.index');

    });

});