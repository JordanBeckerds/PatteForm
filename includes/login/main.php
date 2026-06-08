<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare('
                SELECT id, username, password, failed_attempts, locked_until
                FROM users WHERE username = :username
            ');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = 'Invalid username or password.';
            } elseif ($user['locked_until'] && new DateTime() < new DateTime($user['locked_until'])) {
                $error = 'Account locked. Try again later.';
            } elseif (!password_verify($password, $user['password'])) {
                $attempts = (int)$user['failed_attempts'] + 1;
                if ($attempts >= 5) {
                    // Lock for 30 minutes
                    $pdo->prepare('UPDATE users SET failed_attempts = ?, locked_until = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE id = ?')
                        ->execute([$attempts, $user['id']]);
                    $error = 'Too many failed attempts. Account locked for 30 minutes.';
                } else {
                    $pdo->prepare('UPDATE users SET failed_attempts = ? WHERE id = ?')
                        ->execute([$attempts, $user['id']]);
                    $error = 'Invalid username or password. ' . (5 - $attempts) . ' attempt(s) remaining.';
                }
            } else {
                // Successful login — reset counter
                $pdo->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?')
                    ->execute([$user['id']]);
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log('PatteForm login error: ' . $e->getMessage());
            $error = 'A server error occurred. Please try again.';
        }
    }
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-4">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" id="username" required autocomplete="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" required autocomplete="current-password"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <button type="submit"
              class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
        Login
      </button>
    </form>
  </div>
</div>
