<?php
// Fetch group_elem data from DB (assuming $pdo is your PDO connection)
$stmt = $pdo->query("SELECT * FROM group_elems LIMIT 1");
$group = $stmt->fetch(PDO::FETCH_ASSOC);

$logo = $group['logo'];
$color_primary   = $group['color_primary']   ?? '#FFFFFF';   // For main backgrounds
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';   // For soft backgrounds
$color_tertiary  = $group['color_tertiary']  ?? '#F97316';   // For highlights and CTA

// Determine donation action link
$donationAction = (!empty($group['donation_link_bool']) && $group['donation_link_bool'] == 1 && !empty($group['donation_link']))
    ? htmlspecialchars($group['donation_link'], ENT_QUOTES, 'UTF-8')
    : '../public/donation.php';
?>

<div class="py-48" style="background-color: <?= $color_secondary ?>;">
  <div class="text-center">
    <h2 class="text-2xl font-semibold mb-6" style="color: <?= $color_tertiary ?>;">JE DONNE POUR LES ANIMAUX</h2>
    <div class="flex justify-center items-center space-x-4 mb-6">
      <button id="onceBtn" class="px-6 py-2 rounded-full text-white font-semibold transition"
              style="background-color: <?= $color_tertiary ?>;">Une fois</button>
      <span class="text-xl font-light" style="color: <?= $color_tertiary ?>;">OU</span>
      <button id="monthlyBtn" class="px-6 py-2 rounded-full font-semibold transition"
              style="background-color: <?= $color_secondary ?>; color: <?= $color_tertiary ?>;">Tous les mois</button>
    </div>
  </div>

  <div class="container mx-auto px-4">
    <!-- UNE FOIS -->
    <div id="onceSection" class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
      <?php
      $onceOptions = [
        ['amount' => 60, 'fisc' => 20.4],
        ['amount' => 120, 'fisc' => 40.8],
        ['amount' => 200, 'fisc' => 68],
      ];
      foreach ($onceOptions as $opt):
      ?>
      <div class="flex flex-col items-center text-center">
        <div class="w-[100px] h-[100px] bg-white rounded-full shadow flex flex-col justify-center items-center mb-2">
          <span class="text-2xl font-bold" style="color: <?= $color_tertiary ?>;"><?= $opt['amount'] ?>€</span>
        </div>
        <p class="font-semibold" style="color: <?= $color_tertiary ?>;">Soit <?= number_format($opt['fisc'], 1) ?>€</p>
        <p class="text-sm text-black">après déduction fiscale</p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- TOUS LES MOIS -->
    <div id="monthlySection" class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8 hidden">
      <?php
      $monthlyOptions = [
        ['amount' => 10, 'fisc' => 3.4],
        ['amount' => 15, 'fisc' => 5.1],
        ['amount' => 30, 'fisc' => 10.2],
      ];
      foreach ($monthlyOptions as $opt):
      ?>
      <div class="flex flex-col items-center text-center">
        <div class="w-[100px] h-[100px] bg-white rounded-full shadow flex flex-col justify-center items-center mb-2">
          <span class="text-2xl font-bold" style="color: <?= $color_tertiary ?>;"><?= $opt['amount'] ?>€</span>
          <span class="text-sm font-medium" style="color: <?= $color_tertiary ?>;">/mois</span>
        </div>
        <p class="font-semibold" style="color: <?= $color_tertiary ?>;">Soit <?= number_format($opt['fisc'], 1) ?>€</p>
        <p class="text-sm text-black">après déduction fiscale</p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- CUSTOM DONATION -->
    <div class="mt-14 text-center">
      <h3 class="text-lg font-semibold mb-4" style="color: <?= $color_tertiary ?>;">OU UN MONTANT À MA CONVENANCE</h3>
      <form action="<?= $donationAction ?>" method="post" class="flex flex-col sm:flex-row justify-center items-center gap-3">
        <div class="relative">
          <input name="custom_amount" type="number" min="1"
                 class="border border-gray-300 rounded-md py-2 pl-4 pr-10 w-32 text-center focus:outline-none focus:ring-2"
                 style="--tw-ring-color: <?= $color_tertiary ?>;" placeholder="..." required />
          <span class="absolute top-2.5 right-3 text-black">€</span>
        </div>
        <button type="submit" class="text-white font-semibold py-2 px-6 rounded-md transition"
                style="background-color: <?= $color_tertiary ?>;">Valider</button>
      </form>
    </div>
  </div>
</div>

<script>
  const onceBtn = document.getElementById('onceBtn');
  const monthlyBtn = document.getElementById('monthlyBtn');
  const onceSection = document.getElementById('onceSection');
  const monthlySection = document.getElementById('monthlySection');

  onceBtn.addEventListener('click', () => {
    onceSection.classList.remove('hidden');
    monthlySection.classList.add('hidden');
    onceBtn.style.backgroundColor = "<?= $color_tertiary ?>";
    onceBtn.style.color = "#fff";
    monthlyBtn.style.backgroundColor = "<?= $color_secondary ?>";
    monthlyBtn.style.color = "<?= $color_tertiary ?>";
  });

  monthlyBtn.addEventListener('click', () => {
    onceSection.classList.add('hidden');
    monthlySection.classList.remove('hidden');
    monthlyBtn.style.backgroundColor = "<?= $color_tertiary ?>";
    monthlyBtn.style.color = "#fff";
    onceBtn.style.backgroundColor = "<?= $color_secondary ?>";
    onceBtn.style.color = "<?= $color_tertiary ?>";
  });
</script>