<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Buat User</title>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Helvetica,Arial;max-width:760px;margin:32px auto;padding:0 16px}
    form{display:grid;gap:8px}
    label{font-weight:600}
    input{padding:8px;border:1px solid #ccc;border-radius:6px}
    button{padding:10px 14px;border:none;border-radius:6px;background:#0366d6;color:white;font-weight:700}
    .error{background:#ffe6e6;padding:8px;border:1px solid #ffb3b3;border-radius:6px}
    .success{background:#e6ffef;padding:8px;border:1px solid #b3ffd6;border-radius:6px}
  </style>
</head>
<body>
  <h1>Buat User</h1>

  <?php
  require_once __DIR__ . '/db.php';

  $errors = [];
  $success = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = isset($_POST['username']) ? trim($_POST['username']) : '';
      $email = isset($_POST['email']) ? trim($_POST['email']) : '';
      $password = isset($_POST['password']) ? $_POST['password'] : '';


      if ($username === '') $errors[] = 'Username wajib diisi.';
      if ($email === '') $errors[] = 'Email wajib diisi.';
      elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
      if ($password === '') $errors[] = 'Password wajib diisi.';
      elseif (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';

      if (empty($errors)) {
     
          $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM users WHERE username = :u OR email = :e');
          $stmt->execute([':u' => $username, ':e' => $email]);
          $row = $stmt->fetch();

          if ($row && $row['cnt'] > 0) {
              $errors[] = 'Username atau email sudah terpakai.';
          } else {
      
              $hash = password_hash($password, PASSWORD_DEFAULT);
              $insert = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:u, :e, :p)');
              $insert->execute([':u' => $username, ':e' => $email, ':p' => $hash]);

              $success = 'User berhasil dibuat.';

              $username = $email = '';
          }
      }
  }
  ?>

  <?php if (!empty($errors)): ?>
    <div class="error">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <div>
      <label for="username">Username</label>
      <input id="username" name="username" type="text" value="<?php echo isset($username) ? htmlspecialchars($username) : '' ?>" required>
    </div>

    <div>
      <label for="email">Email</label>
      <input id="email" name="email" type="email" value="<?php echo isset($email) ? htmlspecialchars($email) : '' ?>" required>
    </div>

    <div>
      <label for="password">Password</label>
      <input id="password" name="password" type="password" required>
    </div>

    <div>
      <button type="submit">Buat User</button>
    </div>
  </form>

  <p style="margin-top:16px"><a href="list.php">Lihat semua user</a></p>

</body>
</html>
