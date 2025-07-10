<?php
// Fetch donation link from DB
$stmt = $pdo->prepare("SELECT donation_link, donation_link_bool FROM group_elems LIMIT 1");
$stmt->execute();
$elem = $stmt->fetch(PDO::FETCH_ASSOC);

$donationAction = (!empty($elem['donation_link_bool']) && $elem['donation_link_bool'] == 1 && !empty($elem['donation_link']))
    ? htmlspecialchars($elem['donation_link'], ENT_QUOTES, 'UTF-8')
    : '../public/donation.php';
?>

<div class="bg-[#fef4ee] py-20">
  <div class="text-center">
    <h2 class="text-orange-600 text-2xl font-semibold mb-6">JE DONNE POUR LES ANIMAUX</h2>
    <div class="flex justify-center items-center space-x-4 mb-6">
      <button id="onceBtn" class="px-6 py-2 rounded-full bg-orange-600 text-white font-semibold hover:bg-orange-700 transition">Une fois</button>
      <span class="text-orange-600 text-xl font-light">OU</span>
      <button id="monthlyBtn" class="px-6 py-2 rounded-full bg-orange-100 text-orange-600 font-semibold hover:bg-orange-200 transition">Tous les mois</button>
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
          <span class="text-orange-600 text-2xl font-bold"><?= $opt['amount'] ?>€</span>
        </div>
        <p class="text-orange-600 font-semibold">Soit <?= number_format($opt['fisc'], 1) ?>€</p>
        <p class="text-sm text-gray-700">après déduction fiscale</p>
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
          <span class="text-orange-600 text-2xl font-bold"><?= $opt['amount'] ?>€</span>
          <span class="text-sm text-orange-600 font-medium">/mois</span>
        </div>
        <p class="text-orange-600 font-semibold">Soit <?= number_format($opt['fisc'], 1) ?>€</p>
        <p class="text-sm text-gray-700">après déduction fiscale</p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- CUSTOM DONATION -->
    <div class="mt-14 text-center">
      <h3 class="text-orange-600 text-lg font-semibold mb-4">OU UN MONTANT À MA CONVENANCE</h3>
      <form action="<?= $donationAction ?>" method="post" class="flex flex-col sm:flex-row justify-center items-center gap-3">
        <div class="relative">
          <input name="custom_amount" type="number" min="1" class="border border-gray-300 rounded-md py-2 pl-4 pr-10 w-32 text-center focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="..." required />
          <span class="absolute top-2.5 right-3 text-gray-500">€</span>
        </div>
        <button type="submit" class="bg-orange-600 text-white font-semibold py-2 px-6 rounded-md hover:bg-orange-700 transition">Valider</button>
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
    onceBtn.classList.replace('bg-orange-100', 'bg-orange-600');
    onceBtn.classList.replace('text-orange-600', 'text-white');
    monthlyBtn.classList.replace('bg-orange-600', 'bg-orange-100');
    monthlyBtn.classList.replace('text-white', 'text-orange-600');
  });

  monthlyBtn.addEventListener('click', () => {
    onceSection.classList.add('hidden');
    monthlySection.classList.remove('hidden');
    monthlyBtn.classList.replace('bg-orange-100', 'bg-orange-600');
    monthlyBtn.classList.replace('text-orange-600', 'text-white');
    onceBtn.classList.replace('bg-orange-600', 'bg-orange-100');
    onceBtn.classList.replace('text-white', 'text-orange-600');
  });
</script>