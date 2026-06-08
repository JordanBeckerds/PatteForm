<?php
define('PATTEFORM_SETUP', true);
session_start();

$rootDir = dirname(__DIR__);
$installedMarker = $rootDir . '/.installed';
$envFile = $rootDir . '/.env';

if (file_exists($installedMarker)) {
    header('Content-Type: text/html; charset=UTF-8');
    die('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>PatteForm</title>
    <script src="https://cdn.tailwindcss.com"></script></head>
    <body class="bg-orange-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg p-10 max-w-md text-center">
      <div class="text-5xl mb-4">🐾</div>
      <h1 class="text-2xl font-bold text-green-600 mb-3">✓ Already installed</h1>
      <p class="text-gray-500 mb-6">Setup is complete. Delete <code class="bg-gray-100 px-1 rounded">.installed</code> from the project root to re-run.</p>
      <a href="../public/" class="inline-block bg-orange-500 text-white font-semibold px-6 py-3 rounded-xl hover:bg-orange-600 transition">→ Go to your site</a>
    </div></body></html>');
}

$step   = (int)($_SESSION['pf_step'] ?? 1);
$saved  = $_SESSION['pf_data'] ?? [];
$errors = [];

function pf_redirect(): void {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Handle POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Step 2: test DB connection
    if ($action === 'step2') {
        $host = trim($_POST['db_host'] ?? 'localhost');
        $name = trim($_POST['db_name'] ?? '');
        $user = trim($_POST['db_user'] ?? '');
        $pass = $_POST['db_pass'] ?? '';

        if (!$name) $errors[] = 'Database name is required.';
        if (!$user) $errors[] = 'Database username is required.';

        if (empty($errors)) {
            try {
                $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $saved['db'] = compact('host', 'name', 'user', 'pass');
                $_SESSION['pf_data'] = $saved;
                $_SESSION['pf_step'] = 3;
                pf_redirect();
            } catch (PDOException $e) {
                $errors[] = 'Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
            }
        }
        $step = 2;
    }

    // Step 3: shelter info
    if ($action === 'step3') {
        $org_name = trim($_POST['org_name'] ?? '');
        $address  = trim($_POST['address']  ?? '');
        $phone    = trim($_POST['phone']    ?? '');
        $color_p  = trim($_POST['color_p']  ?? '#FFFFFF');
        $color_s  = trim($_POST['color_s']  ?? '#FEF4EE');
        $color_a  = trim($_POST['color_a']  ?? '#F97316');
        $lang     = in_array($_POST['lang'] ?? '', ['fr','en']) ? $_POST['lang'] : 'fr';

        if (!$org_name) $errors[] = 'Organization name is required.';

        if (empty($errors)) {
            $saved['shelter'] = compact('org_name','address','phone','color_p','color_s','color_a','lang');
            $_SESSION['pf_data'] = $saved;
            $_SESSION['pf_step'] = 4;
            pf_redirect();
        }
        $step = 3;
    }

    // Step 4: admin account + final install
    if ($action === 'step4') {
        $admin_user  = trim($_POST['admin_user']  ?? '');
        $admin_email = trim($_POST['admin_email'] ?? '');
        $admin_pass  = $_POST['admin_pass']  ?? '';
        $admin_pass2 = $_POST['admin_pass2'] ?? '';

        if (!$admin_user)                                        $errors[] = 'Username is required.';
        if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL))    $errors[] = 'A valid email is required.';
        if (strlen($admin_pass) < 8)                             $errors[] = 'Password must be at least 8 characters.';
        if ($admin_pass !== $admin_pass2)                        $errors[] = 'Passwords do not match.';

        if (empty($errors)) {
            try {
                $db      = $saved['db'];
                $shelter = $saved['shelter'];

                $pdo = new PDO(
                    "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4",
                    $db['user'], $db['pass'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );

                // Import schema
                $sql = file_get_contents($rootDir . '/sql/patteform.sql');
                foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
                    $pdo->exec($stmt);
                }

                // Insert shelter data
                $pdo->prepare("
                    INSERT INTO group_elems
                      (group_name, adress, telephone, color_primary, color_secondary, color_tertiary, date_creation)
                    VALUES (?, ?, ?, ?, ?, ?, CURDATE())
                ")->execute([
                    $shelter['org_name'], $shelter['address'], $shelter['phone'],
                    $shelter['color_p'],  $shelter['color_s'],  $shelter['color_a'],
                ]);

                // Create admin user
                $pdo->prepare("
                    INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')
                ")->execute([$admin_user, $admin_email, password_hash($admin_pass, PASSWORD_BCRYPT)]);

                // Write .env
                file_put_contents($envFile,
                    "DB_HOST={$db['host']}\nDB_NAME={$db['name']}\nDB_USER={$db['user']}\nDB_PASS={$db['pass']}\n"
                    . "APP_LANG={$shelter['lang']}\nAPP_URL=\n"
                );

                // Mark installed
                file_put_contents($installedMarker, date('c'));

                session_destroy();
                $step = 5;
            } catch (Exception $e) {
                $errors[] = 'Installation failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
                $step = 4;
            }
        } else {
            $step = 4;
        }
    }
}

// Requirements check
$reqs = [
    'PHP 8.0+'               => version_compare(PHP_VERSION, '8.0.0', '>='),
    'PDO extension'          => extension_loaded('pdo'),
    'PDO MySQL driver'       => extension_loaded('pdo_mysql'),
    'SQL schema file exists' => file_exists($rootDir . '/sql/patteform.sql'),
    'Root dir writable'      => is_writable($rootDir),
];
$reqsOk = !in_array(false, $reqs, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PatteForm — Setup</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-orange-50 to-amber-50 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-xl">

  <div class="text-center mb-8">
    <div class="text-5xl mb-2">🐾</div>
    <h1 class="text-3xl font-bold text-orange-600">PatteForm</h1>
    <p class="text-gray-400 text-sm mt-1">Setup Wizard</p>
  </div>

  <!-- Step indicator -->
  <?php if ($step < 5): ?>
  <div class="flex justify-center items-center gap-1 mb-8">
    <?php
    $labels = ['Requirements','Database','Organization','Admin account'];
    for ($i = 1; $i <= 4; $i++):
    ?>
      <div class="flex items-center gap-1">
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
          <?= $step > $i ? 'bg-green-500 text-white' : ($step === $i ? 'bg-orange-500 text-white' : 'bg-white border-2 border-gray-200 text-gray-400') ?>">
          <?= $step > $i ? '✓' : $i ?>
        </div>
        <?php if ($i < 4): ?>
          <div class="w-6 h-px <?= $step > $i ? 'bg-green-400' : 'bg-gray-200' ?>"></div>
        <?php endif; ?>
      </div>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

  <?php if ($errors): ?>
  <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
    <?php foreach ($errors as $e): ?>
      <p class="text-red-700 text-sm">⚠ <?= $e ?></p>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="bg-white rounded-2xl shadow-lg p-8">

  <?php if ($step === 1): ?>
    <h2 class="text-lg font-semibold text-gray-800 mb-5">Checking requirements</h2>
    <ul class="space-y-3 mb-8">
      <?php foreach ($reqs as $label => $ok): ?>
        <li class="flex items-center gap-3">
          <span class="text-xl <?= $ok ? 'text-green-500' : 'text-red-500' ?>"><?= $ok ? '✓' : '✗' ?></span>
          <span class="<?= $ok ? 'text-gray-700' : 'text-red-700 font-medium' ?>"><?= htmlspecialchars($label) ?></span>
          <?php if (!$ok && $label === 'PHP 8.0+'): ?>
            <span class="text-xs text-gray-400">current: <?= PHP_VERSION ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php if ($reqsOk): ?>
      <form method="post"><input type="hidden" name="action" value="step2">
        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition">Continue →</button>
      </form>
    <?php else: ?>
      <p class="text-red-600 text-sm text-center">Fix the issues above before continuing.</p>
    <?php endif; ?>

  <?php elseif ($step === 2): ?>
    <h2 class="text-lg font-semibold text-gray-800 mb-5">Database connection</h2>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="step2">
      <?php
      $fields = [
        ['db_host','Host','text','localhost'],
        ['db_name','Database name','text','patteform'],
        ['db_user','Username','text',''],
        ['db_pass','Password','password',''],
      ];
      foreach ($fields as [$n,$l,$t,$ph]):
      ?>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1"><?= $l ?></label>
          <input type="<?= $t ?>" name="<?= $n ?>" placeholder="<?= htmlspecialchars($ph) ?>"
            value="<?= $t !== 'password' ? htmlspecialchars($_POST[$n] ?? $ph) : '' ?>"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
        </div>
      <?php endforeach; ?>
      <p class="text-xs text-gray-400">The database will be created if it doesn’t exist yet.</p>
      <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition">Test &amp; Continue →</button>
    </form>

  <?php elseif ($step === 3): ?>
    <h2 class="text-lg font-semibold text-gray-800 mb-5">Your organization</h2>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="step3">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Organization name <span class="text-red-400">*</span></label>
        <input type="text" name="org_name" placeholder="ASPA de Pau" required
          value="<?= htmlspecialchars($_POST['org_name'] ?? '') ?>"
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
        <input type="text" name="address" placeholder="14 avenue du Doyen Robert Poplawski, 64000 Pau"
          value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
        <input type="text" name="phone" placeholder="06 00 00 00 00"
          value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Brand colors</label>
        <div class="grid grid-cols-3 gap-3">
          <?php foreach ([['color_p','Background','#FFFFFF'],['color_s','Secondary','#FEF4EE'],['color_a','Accent','#F97316']] as [$n,$l,$d]): ?>
            <div class="text-center">
              <label class="block text-xs text-gray-500 mb-1"><?= $l ?></label>
              <input type="color" name="<?= $n ?>" value="<?= htmlspecialchars($_POST[$n] ?? $d) ?>"
                class="w-full h-10 rounded cursor-pointer border border-gray-200">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Default language</label>
        <select name="lang" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
          <option value="fr" <?= ($_POST['lang'] ?? 'fr') === 'fr' ? 'selected' : '' ?>>Français</option>
          <option value="en" <?= ($_POST['lang'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
        </select>
      </div>
      <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition">Continue →</button>
    </form>

  <?php elseif ($step === 4): ?>
    <h2 class="text-lg font-semibold text-gray-800 mb-5">Create admin account</h2>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="step4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-400">*</span></label>
        <input type="text" name="admin_user" required value="<?= htmlspecialchars($_POST['admin_user'] ?? '') ?>"
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-400">*</span></label>
        <input type="email" name="admin_email" required value="<?= htmlspecialchars($_POST['admin_email'] ?? '') ?>"
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-400">*</span> <span class="text-gray-400 font-normal">(min. 8 characters)</span></label>
        <input type="password" name="admin_pass" required minlength="8"
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password <span class="text-red-400">*</span></label>
        <input type="password" name="admin_pass2" required
          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
      </div>
      <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition">Install PatteForm →</button>
    </form>

  <?php elseif ($step === 5): ?>
    <div class="text-center py-4">
      <div class="text-6xl mb-4">🐾</div>
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Installation complete!</h2>
      <p class="text-gray-500 text-sm mb-8">PatteForm is ready. For security, restrict access to the <code class="bg-gray-100 px-1 rounded">setup/</code> directory on your server.</p>
      <div class="flex flex-col gap-3">
        <a href="../public/" class="block bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition">→ View your site</a>
        <a href="../public/dashboard.php" class="block bg-white border border-orange-300 hover:bg-orange-50 text-orange-600 font-semibold py-3 rounded-xl transition">→ Admin dashboard</a>
      </div>
    </div>
  <?php endif; ?>

  </div>
  <p class="text-center text-xs text-gray-400 mt-6">PatteForm · Open-source animal shelter platform</p>
</div>
</body>
</html>
