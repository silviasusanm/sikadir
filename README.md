<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Konfigurasi Pembayaran POS

- **Tunai:** masukkan uang yang diterima atau gunakan tombol nominal cepat; sistem menghitung kembalian.
- **QRIS:** letakkan gambar QRIS resmi merchant di `public/images/qris.png`. Sistem menampilkan jumlah yang harus dibayar dan kasir harus mencentang konfirmasi setelah memeriksa pembayaran benar-benar masuk.
QRIS pada aplikasi ini adalah pencatatan pembayaran manual, bukan integrasi/payment gateway. QRIS harus berasal dari merchant toko. Setelah mengubah `.env`, jalankan `php artisan config:clear`.

## Deploy pembaruan ke Railway

1. Jalankan test lokal dengan `php artisan test`.
2. Periksa perubahan dengan `git status` dan commit hanya file yang memang ingin dipublikasikan. Jangan commit `.env` karena berisi konfigurasi rahasia.
3. Push commit ke branch GitHub yang terhubung ke service Railway. Untuk branch `main`, perintahnya `git push origin main`.
4. Pastikan Railway memakai branch tersebut di pengaturan Source/Deployments, lalu tunggu build dan deployment berstatus sukses. Jika build dari GitHub sedang tertunda, buka Deployments dan jalankan redeploy setelah status Railway normal.
5. Pastikan environment variables production, terutama `APP_KEY`, `APP_URL`, dan koneksi database yang benar. Biarkan konfigurasi database production yang sudah berjalan tetap sama; jangan mengganti kredensial database untuk deploy ini.

Gambar `public/images/qris.png` ikut dalam commit aplikasi sehingga otomatis tersedia setelah deploy. Jika QRIS diganti, commit file gambar penggantinya lalu deploy ulang. Jangan unggah atau menaruh kredensial merchant di GitHub.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
