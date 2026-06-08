<?php
// Users management page — included by public/dashboard.php
// Only admins should see this; dashboard.php already calls require_login()

require_once dirname(__DIR__, 2) . '/auth.php';
require_admin('dashboard.php');

$edit_user = null;
$msg       = '';

// Add new user
if (isset($_POST['add_user'])) {
    $uname  = trim($_POST['username'] ?? '');
    $upass  = trim($_POST['password_new'] ?? '');
    $urole  = in_array($_POST['role'] ?? '', ['admin', 'staff'], true) ? $_POST['role'] : 'staff';
    if ($uname !== '' && $upass !== '') {
        $hash = password_hash($upass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        $stmt->execute([$uname, $hash, $urole]);
        $msg = 'Utilisateur cr&#233;&#233;.';
    } else {
        $msg = 'Nom d&#39;utilisateur et mot de passe requis.';
    }
}

// Update user
if (isset($_POST['update_user'])) {
    $uid   = (int)($_POST['id'] ?? 0);
    $urole = in_array($_POST['role'] ?? '', ['admin', 'staff'], true) ? $_POST['role'] : 'staff';
    if (!empty($_POST['password_new'])) {
        $hash = password_hash($_POST['password_new'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('UPDATE users SET username=?, password=?, role=? WHERE id=?');
        $stmt->execute([$_POST['username'] ?? '', $hash, $urole, $uid]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET username=?, role=? WHERE id=?');
        $stmt->execute([$_POST['username'] ?? '', $urole, $uid]);
    }
    $msg = 'Utilisateur mis &#224; jour.';
}

// Delete user (cannot delete self)
if (isset($_GET['delete'])) {
    $uid = (int)$_GET['delete'];
    if ($uid !== (int)($_SESSION['user_id'] ?? 0)) {
        $pdo->prepare('DELETE FROM users WHERE id=?')->execute([$uid]);
    }
    header('Location: dashboard.php?page=users');
    exit;
}

// Edit prefill
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT id, username, role FROM users WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit_user = $stmt->fetch() ?: null;
}

$users = $pdo->query('SELECT id, username, role, created_at FROM users ORDER BY id ASC')->fetchAll();

$ct = htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8');
$cp = htmlspecialchars($color_primary,  ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-3xl font-bold mb-6" style="color:<?= $ct ?>">Utilisateurs</h1>

<?php if ($msg): ?>
  <p class="mb-4 text-green-700 font-semibold"><?= $msg ?></p>
<?php endif; ?>

<form method="POST" class="bg-white p-6 rounded shadow mb-6">
  <input type="hidden" name="id" value="<?= (int)($edit_user['id'] ?? 0) ?>">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="block font-semibold mb-1">Nom d&#39;utilisateur</label>
      <input type="text" name="username" required
             value="<?= htmlspecialchars($edit_user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded">
    </div>
    <div>
      <label class="block font-semibold mb-1">Mot de passe <?= isset($edit_user) ? '(laisser vide = inchang&#233;)' : '' ?></label>
      <input type="password" name="password_new"
             <?= !isset($edit_user) ? 'required' : '' ?>
             class="w-full border p-2 rounded" autocomplete="new-password">
    </div>
    <div>
      <label class="block font-semibold mb-1">R&#244;le</label>
      <select name="role" class="w-full border p-2 rounded">
        <option value="staff" <?= ($edit_user['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff</option>
        <option value="admin" <?= ($edit_user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>
  </div>
  <div class="mt-4 flex gap-2">
    <button type="submit"
            name="<?= isset($edit_user) ? 'update_user' : 'add_user' ?>"
            class="text-white px-4 py-2 rounded transition"
            style="background-color:<?= $ct ?>">
      <?= isset($edit_user) ? 'Mettre &#224; jour' : 'Ajouter' ?>
    </button>
    <?php if (isset($edit_user)): ?>
      <a href="dashboard.php?page=users"
         class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Annuler</a>
    <?php endif; ?>
  </div>
</form>

<div class="overflow-x-auto bg-white p-4 rounded shadow">
  <table class="w-full table-auto">
    <thead>
      <tr style="background-color:<?= $cp ?>" class="text-white">
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Nom d&#39;utilisateur</th>
        <th class="px-4 py-2">R&#244;le</th>
        <th class="px-4 py-2">Cr&#233;&#233; le</th>
        <th class="px-4 py-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="px-4 py-2"><?= (int)$u['id'] ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($u['username'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($u['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="px-4 py-2 flex gap-2">
            <a href="?page=users&amp;edit=<?= (int)$u['id'] ?>"
               class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Modifier</a>
            <?php if ((int)$u['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
              <a href="?page=users&amp;delete=<?= (int)$u['id'] ?>"
                 onclick="return confirm('Supprimer cet utilisateur ?')"
                 class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
