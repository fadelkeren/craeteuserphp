<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar User</title>
  <style>body{font-family:system-ui;padding:20px}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:8px}</style>
</head>
<body>
  <h1>Daftar User</h1>
  <?php
  require_once __DIR__ . '/db.php';
  $stmt = $pdo->query('SELECT id, username, email, created_at FROM users ORDER BY id DESC');
  $users = $stmt->fetchAll();
  ?>
  <table>
    <thead>
      <tr><th>#</th><th>Username</th><th>Email</th><th>Dibuat</th></tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?php echo htmlspecialchars($u['id']) ?></td>
          <td><?php echo htmlspecialchars($u['username']) ?></td>
          <td><?php echo htmlspecialchars($u['email']) ?></td>
          <td><?php echo htmlspecialchars($u['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="create.php">Buat user baru</a></p>
</body>
</html>
