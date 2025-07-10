<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Patteform</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const btn = document.getElementById("mobile-menu-button");
      const icon = document.getElementById("burger-icon");
      const menu = document.getElementById("mobile-menu");

      btn.addEventListener("click", () => {
        menu.classList.toggle("max-h-0");
        menu.classList.toggle("max-h-[600px]");
        icon.classList.toggle("rotate-90");
      });
    });
  </script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

<header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">
  <div class="container mx-auto flex justify-between items-center px-6 py-6 md:py-8">
    <a href="../public/index.php" class="flex items-center space-x-2">
      <img src="../assets/img/logo.png" alt="Logo" class="h-24 w-auto object-contain" />
    </a>

    <!-- Desktop Menu -->
    <nav class="hidden md:flex space-x-8 text-lg font-medium text-gray-700 items-center">
      <a href="../public/index.php" class="nav-link">Accueil</a>
      <a href="../public/adoption.php" class="nav-link">Adoption</a>
      <a href="../public/actualites.php" class="nav-link">Actualités</a>
      <a href="../public/contact.php" class="nav-link">Contact</a>
      <a href="../public/donation.php" class="nav-link">Donation</a>

      <!-- Styled Login Button (Desktop Only) -->
    </nav>

    <!-- Mobile Hamburger -->
    <button
      id="mobile-menu-button"
      class="md:hidden p-2 border border-[#F97316] rounded-md hover:bg-[#F97316]/10 focus:outline-none focus:ring-2 focus:ring-[#F97316] transition"
    >
      <svg
        id="burger-icon"
        class="w-6 h-6 text-[#F97316] transition-transform duration-300 ease-in-out"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        viewBox="0 0 24 24"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <path d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <nav
    id="mobile-menu"
    class="overflow-hidden transition-all duration-300 max-h-0 md:hidden bg-white border-t border-gray-200 shadow-md"
  >
    <a href="../public/index.php" class="mobile-link">Accueil</a>
    <a href="../public/adoption.php" class="mobile-link">Adoption</a>
    <a href="../public/actualites.php" class="mobile-link">Actualités</a>
    <a href="../public/contact.php" class="mobile-link">Contact</a>
    <a href="../public/donation.php" class="mobile-link">Donation</a>
    <a href="../public/login.php" class="mobile-link">Login</a>
  </nav>
</header>

<!-- Tailwind Helpers -->
<style>
  .nav-link {
    position: relative;
    transition: color 0.3s;
  }

  /* Hover text color changed to #F97316 */
  .nav-link:hover {
    color: #F97316;
  }

  /* Underline color changed to #F97316 */
  .nav-link::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -4px;
    height: 2px;
    width: 100%;
    background: #F97316;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease-in-out;
  }

  .nav-link:hover::after {
    transform: scaleX(1);
  }

  .mobile-link {
    display: block;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
    transition: all 0.3s;
    position: relative;
  }

  .mobile-link:hover {
    background: #fef3c7;
    color: #F97316;
  }

  .mobile-link::after {
    content: "";
    position: absolute;
    bottom: 0.5rem;
    left: 1.5rem;
    height: 2px;
    width: 64px;
    background: #F97316;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease-in-out;
  }

  .mobile-link:hover::after {
    transform: scaleX(1);
  }
</style>
</body>
</html>