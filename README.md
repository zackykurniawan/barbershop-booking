# Barbershop Booking

Aplikasi web booking barbershop berbasis PHP native dan MySQL. Aplikasi ini menyediakan autentikasi, dashboard ringkasan, manajemen booking, manajemen layanan, dan manajemen user untuk admin.

## Fitur

- Login dan register user.
- Role pengguna: `Admin` dan `User`.
- Dashboard statistik booking, status booking, layanan, dan pendapatan selesai.
- CRUD data booking.
- CRUD data layanan barbershop.
- CRUD data user khusus role `Admin`.
- Soft delete menggunakan kolom `deleted_at`.
- Tampilan admin menggunakan Bootstrap, DataTables, dan ApexCharts.

## Kebutuhan

- PHP 8.x atau versi yang kompatibel.
- MySQL atau MariaDB.
- Web server lokal seperti Laragon, XAMPP, atau Apache.
- Browser modern.

## Instalasi

1. Letakkan folder project di direktori web server.

   Contoh untuk Laragon:

   ```text
   C:\laragon\www\barbershop
   ```

2. Buat database dan import file SQL.

   Import file berikut melalui phpMyAdmin, Adminer, atau MySQL CLI:

   ```text
   barbershop.sql
   ```

   File SQL akan membuat database:

   ```text
   barbershop_db
   ```

3. Sesuaikan konfigurasi database jika diperlukan.

   File konfigurasi berada di:

   ```text
   config/db.php
   ```

   Konfigurasi default:

   ```php
   $dbHost = 'localhost';
   $dbUser = 'root';
   $dbPass = '';
   $dbName = 'barbershop_db';
   ```

4. Jalankan web server lokal.

5. Buka aplikasi melalui browser.

   Jika menggunakan Laragon:

   ```text
   http://barbershop.test
   ```

   Atau:

   ```text
   http://localhost/barbershop
   ```

## Akun Demo

Gunakan akun bawaan dari file `barbershop.sql`.

| Role | Username | Password |
| --- | --- | --- |
| Admin | `admin` | `admin123` |
| User | `barber1` | `user123` |

Pada halaman login, pastikan pilihan `Status` sesuai dengan role akun yang digunakan.

## Struktur Folder

```text
barbershop/
+-- assets/              # Asset CSS, JavaScript, font, dan gambar
+-- auth/                # Halaman login, register, dan logout
+-- config/              # Konfigurasi database dan helper aplikasi
+-- dashboard/           # Dashboard dan modul manajemen
|   +-- booking/         # CRUD booking
|   +-- services/        # CRUD layanan
|   +-- users/           # CRUD user khusus admin
+-- layouts/             # Template header, footer, sidebar, dan topbar
+-- barbershop.sql       # Schema database dan seed data
+-- index.php            # Redirect ke halaman login
```

## Hak Akses

- `Admin` dapat mengakses dashboard, booking, layanan, dan user.
- `User` dapat mengakses dashboard, booking, dan layanan.
- Halaman tertentu dilindungi oleh session login.

## Catatan Pengembangan

- Zona waktu aplikasi diset ke `Asia/Jakarta` di `config/helpers.php`.
- Password user disimpan menggunakan hash bcrypt dari `password_hash()`.
- Data yang dihapus menggunakan mekanisme soft delete, sehingga tidak langsung hilang dari database.
- Jika path redirect tidak sesuai, pastikan project berjalan dari root domain atau virtual host yang benar.
