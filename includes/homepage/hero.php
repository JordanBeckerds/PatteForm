<?php
// Fetch background, donation link, and colors
$stmt = $pdo->prepare("SELECT homepage_main_bg, donation_link, donation_link_bool, color_tertiary FROM group_elems LIMIT 1");
$stmt->execute();
$elem = $stmt->fetch(PDO::FETCH_ASSOC);

// Background fallback
$heroBg = !empty($elem['homepage_main_bg']) 
    ? htmlspecialchars($elem['homepage_main_bg'], ENT_QUOTES, 'UTF-8') 
    : '../assets/img/hero_bg.png';

// Determine donation link
$donationLink = (!empty($elem['donation_link_bool']) && $elem['donation_link_bool'] == 1 && !empty($elem['donation_link']))
    ? htmlspecialchars($elem['donation_link'], ENT_QUOTES, 'UTF-8')
    : '../public/donation.php';

// Use dynamic highlight color
$color_tertiary = $elem['color_tertiary'] ?? '#F97316'; // fallback to Tailwind orange
?>

<section 
    class="relative w-full" 
    style="
        height: 60vh; 
        background-image: url('<?= $heroBg ?>');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    ">
    
    <div class="container h-full flex flex-col justify-center items-center px-6 md:px-0">
        <div class="max-w-4xl w-full px-6 md:px-12">
            <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold text-[<?= $homepage_main_color_text ?>] mb-6 sm:mb-8 drop-shadow-lg text-left">
                Offrons-leur autant de bonheur qu'ils nous en apportent !
            </h1>
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 w-full max-w-xs sm:max-w-none text-left">
                <!-- Je donne -->
                <a href="<?= $donationLink ?>" 
                   style="background-color: <?= $color_tertiary ?>;"
                   class="text-white px-6 py-3 rounded-full font-semibold text-base 
                          transition-opacity duration-500 max-w-[160px] flex items-center justify-center text-center hover:opacity-70">
                    Je donne
                </a>
                <!-- J'adopte -->
                <a href="../public/adoption.php"
                   style="
                        background-color: <?= $color_tertiary ?>;
                        border: 1px solid <?= $color_tertiary ?>;
                   "
                   class="text-white px-6 py-3 rounded-full font-semibold text-base 
                          transition-all duration-500 max-w-[160px] flex items-center justify-center text-center
                          hover:bg-transparent hover:opacity-70">
                    J'adopte
                </a>
            </div>
        </div>
    </div>
</section>