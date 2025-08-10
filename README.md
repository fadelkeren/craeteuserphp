# Panduan Membuat Website PHP Sederhana untuk Create Data User

## 1. Membuat Database MySQL
1. Buka **phpMyAdmin** atau terminal MySQL.
2. Jalankan SQL berikut untuk membuat database dan tabel:
```sql
CREATE DATABASE IF NOT EXISTS simple_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simple_app;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 2. Mengatur Koneksi Database (db.php)
Buat file `db.php` dan isi dengan:
```php
<?php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'simple_app';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}
```
> **Note:** Sesuaikan `$DB_USER` dan `$DB_PASS` sesuai konfigurasi MySQL lokal kamu.

## 3. Membuat Form Input (create.php)
Buat file `create.php` untuk menampilkan form dan memproses data:
```php
<?php
require_once __DIR__ . '/db.php';
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($username === '') $errors[] = 'Username wajib diisi.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM users WHERE username = :u OR email = :e');
        $stmt->execute([':u' => $username, ':e' => $email]);
        $row = $stmt->fetch();

        if ($row['cnt'] > 0) {
            $errors[] = 'Username atau email sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:u, :e, :p)')
                ->execute([':u' => $username, ':e' => $email, ':p' => $hash]);
            $success = 'User berhasil dibuat.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Buat User</title></head>
<body>
<h1>Form Buat User</h1>
<?php if ($errors): ?><div style="color:red"><?php echo implode('<br>', $errors); ?></div><?php endif; ?>
<?php if ($success): ?><div style="color:green"><?php echo $success; ?></div><?php endif; ?>
<form method="post">
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Simpan</button>
</form>
</body>
</html>
```

## 4. Menampilkan Data User (list.php)
Buat file `list.php` untuk melihat semua user:
```php
<?php
require_once __DIR__ . '/db.php';
$users = $pdo->query('SELECT id, username, email, created_at FROM users ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Daftar User</title></head>
<body>
<h1>Daftar User</h1>
<table border="1">
<tr><th>ID</th><th>Username</th><th>Email</th><th>Dibuat</th></tr>
<?php foreach ($users as $u): ?>
<tr>
<td><?= $u['id'] ?></td>
<td><?= htmlspecialchars($u['username']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= $u['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
```

## 5. Menjalankan Project
- Simpan semua file (`db.php`, `create.php`, `list.php`) di folder project.
- Pastikan server lokal (XAMPP/MAMP/Laragon) aktif.
- Akses `http://localhost/nama_folder/create.php` untuk membuat user.
- Akses `http://localhost/nama_folder/list.php` untuk melihat daftar user.

---
💡 **Tips:**
- Gunakan `password_hash()` dan `password_verify()` untuk keamanan.
- Tambahkan validasi front-end (JavaScript) agar lebih interaktif.
- Bisa diintegrasikan dengan login dan session untuk fitur lanjutan.
