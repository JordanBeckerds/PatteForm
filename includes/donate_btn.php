<?php
// Fetch group_elem data from DB (assuming $pdo is your PDO connection)
$stmt = $pdo->query("SELECT * FROM group_elems LIMIT 1");
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// Set default values if null
$color_primary = $group['color_primary'] ?? '#FFFFFF';
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';
$color_tertiary = $group['color_tertiary'] ?? '#F97316'; // fallback to your orange
$donation_link_bool = (bool) $group['donation_link_bool'];
$donation_link = $donation_link_bool && !empty($group['donation_link'])
    ? $group['donation_link']
    : '../public/donation.php';
?>

<style>
  /* Responsive donation button */
  @media (max-width: 1024px) {
    a.donation-btn {
      bottom: 5vh !important;
      padding: 0.5rem 1rem !important;
      font-size: 1rem !important;
    }

    a.donation-btn img {
      height: 2rem !important;
      margin-right: 0.5rem !important;
    }
  }

  @media (max-width: 640px) {
    a.donation-btn {
      right: 5vh !important;
      left: auto;
      padding: 0.5rem !important;
      min-width: auto !important;
    }

    a.donation-btn span {
      display: none !important;
    }

    a.donation-btn img {
      height: 2.25rem !important;
      margin: 0 !important;
    }
  }
</style>

<a href="<?= htmlspecialchars($donation_link) ?>"
   class="donation-btn fixed bottom-16 right-[5vh] text-white flex justify-around items-center font-semibold py-4 px-6 rounded-full shadow-lg transition-colors duration-300 z-50 text-2xl hover:brightness-110"
   style="
     min-width: 160px;
     box-shadow: 0 6px 14px rgba(0,0,0,0.3);
     background-color: <?= htmlspecialchars($color_tertiary) ?>;
   "
>
  <img src="../assets/img/donate_btn.png" alt="Donate" class="h-10 mr-4" />
  <span>Donate</span>
</a>

<?php
// Helper function to darken hex color by percentage
function darkenColor($hex, $percent) {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = max(0, min(255, hexdec(substr($hex, 0, 2)) - round(255 * ($percent / 100))));
    $g = max(0, min(255, hexdec(substr($hex, 2, 2)) - round(255 * ($percent / 100))));
    $b = max(0, min(255, hexdec(substr($hex, 4, 2)) - round(255 * ($percent / 100))));
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
?>