<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Veuillez saisir un nom d\'utilisateur et un mot de passe.';
    } else {
        try {
            $stmt = $pdo->prepare('
                SELECT id, username, password, role, failed_attempts, locked_until
                FROM users WHERE username = :username
            ');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = 'Identifiants invalides.';
            } elseif ($user['locked_until'] && new DateTime() < new DateTime($user['locked_until'])) {
                $error = 'Compte verrouillé. Réessayez plus tard.';
            } elseif (!password_verify($password, $user['password'])) {
                $attempts = (int)$user['failed_attempts'] + 1;
                if ($attempts >= 5) {
                    $pdo->prepare('UPDATE users SET failed_attempts = ?, locked_until = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE id = ?')
                        ->execute([$attempts, $user['id']]);
                    $error = 'Trop de tentatives. Compte verrouillé 30 minutes.';
                } else {
                    $pdo->prepare('UPDATE users SET failed_attempts = ? WHERE id = ?')
                        ->execute([$attempts, $user['id']]);
                    $error = 'Identifiants invalides. ' . (5 - $attempts) . ' tentative(s) restante(s).';
                }
            } else {
                // Success — reset brute-force counters
                $pdo->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?')
                    ->execute([$user['id']]);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['user_role'] = $user['role'] ?? 'staff';
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log('PatteForm login error: ' . $e->getMessage());
            $error = 'Erreur serveur. Veuillez réessayer.';
        }
    }
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">Connexion</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-4">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
        <input type="text" name="username" id="username" required autocomplete="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
        <input type="password" name="password" id="password" required autocomplete="current-password"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <button type="submit"
              class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
        Se connecter
      </button>
    </form>
  </div>
</div>
