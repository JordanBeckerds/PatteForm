<?php
$pages = [
    'home' => ['icon'=>'home','label'=>'Home'],
    'actualite' => ['icon'=>'newspaper','label'=>'Actualités'],
    'animaux' => ['icon'=>'paw','label'=>'Animaux'],
    'equipe' => ['icon'=>'users','label'=>'Équipe'],
    'homepage_sections' => ['icon'=>'view-grid','label'=>'Homepage Sections'],
    'users' => ['icon'=>'user','label'=>'Users']
];
$current_page = $_GET['page'] ?? 'home';
?>

<aside class="w-64 bg-[<?=$color_primary?>] min-h-screen shadow-md">
    <div class="p-6 text-[<?=$homepage_main_color_text?>] text-center font-bold text-4xl">Dashboard</div>
    <nav class="mt-6">
        <?php foreach($pages as $key => $data): ?>
            <a href="dashboard.php?page=<?= $key ?>" 
               class="flex items-center p-4 mb-2 rounded hover:bg-[<?=$color_tertiary?>] transition-colors <?= $current_page==$key ? 'bg-[<?=$color_secondary?>]' : '' ?>">
                <svg class="w-5 h-5 mr-2 text-[<?=$color_tertiary?>]" fill="none" stroke="currentColor" stroke-width="2">
                    <use href="#<?= $data['icon'] ?>"/>
                </svg>
                <?= $data['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>

<!-- Heroicons definitions -->
<svg style="display:none">
    <symbol id="home" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3a2 2 0 01-2-2v-9z"/></symbol>
    <symbol id="newspaper" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2zM19 6H5m14 6H5m4 4h6"/></symbol>
    <symbol id="paw" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm-5.5-5c-.828 0-1.5.672-1.5 1.5S5.672 13 6.5 13 8 12.328 8 11.5 7.328 10 6.5 10zm11 0c-.828 0-1.5.672-1.5 1.5S16.672 13 17.5 13 19 12.328 19 11.5 18.328 10 17.5 10zm-8-6c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z"/></symbol>
    <symbol id="users" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z"/></symbol>
    <symbol id="view-grid" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/></symbol>
    <symbol id="user" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M12 14a4 4 0 100-8 4 4 0 000 8z"/></symbol>
</svg>