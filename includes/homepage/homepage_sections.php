<?php

$sql = "SELECT * FROM homepage_sections WHERE visible = TRUE ORDER BY position ASC";
$stmt = $pdo->query($sql);
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$index = 0;

if ($sections): ?>
    <?php foreach ($sections as $row):
        $reverse = $index % 2 !== 0;
        // Set background based on even/odd index
        $bgColor = ($index % 2 === 0) ? '#fef4ee' : '#ffffff';
    ?>
        <section style="background-color: <?= $bgColor ?>;" class="py-24 md:py-32">
            <div class="container mx-auto px-6 flex flex-col-reverse <?= $reverse ? 'md:flex-row-reverse' : 'md:flex-row' ?> items-center gap-10">

                <!-- Text -->
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 "><?= htmlspecialchars($row['title']) ?></h2>
                    <p class="text-gray-700 mb-8 text-base md:text-lg leading-relaxed"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <?php if (!empty($row['button_text']) && !empty($row['button_link'])): ?>
                        <a href="<?= htmlspecialchars((preg_match('/^https?:\/\//', $row['button_link']) ? $row['button_link'] : 'https://' . $row['button_link'])) ?>" target="_blank" rel="noopener noreferrer" class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-full font-semibold transition text-base md:text-lg">
                            <?= htmlspecialchars($row['button_text']) ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Image -->
                <div class="w-full md:w-1/2">
                    <img src="<?= htmlspecialchars($row['img_url']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-full h-auto max-h-[700px] object-cover rounded-lg shadow-lg">
                </div>

            </div>
        </section>
    <?php $index++; endforeach; ?>
<?php endif; ?>