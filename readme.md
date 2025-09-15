# 🐾 Patteform

**Patteform** is a web project inspired by [la-spa.fr](https://www.la-spa.fr/), built to help manage an animal adoption platform.  
It features a modular admin dashboard, customizable sections, and a user-friendly frontend for browsing adoption listings, news, and more.  

---

## 🚀 Features

- **Frontend**
  - Home page with customizable sections
  - Adoption listings with "favorite" heart feature (session-based)
  - News/actualités with share button animations (Instagram, Twitter, Email)
  - Responsive design with **Tailwind CSS**

- **Backend**
  - Modular admin dashboard
  - Manage site sections (add/remove, reorder)
  - Change site colors and logo
  - Full CRUD for animals, news, and team members
  - User login system with security (5 failed attempts = blocked)

- **Database**
  - MySQL with tables:
    - `animaux_a_adopter`, `animaux_adopter`
    - `actualite`, `actualite_secs`
    - `homepage_sections`, `group_elems`
    - `users` (with login attempt tracking)
    - `contact`, `equipe`, `photo_chiens`

---

## 🛠️ Tech Stack

- **Frontend**: HTML, PHP, Tailwind CSS (via CDN)
- **Backend**: PHP 8+
- **Database**: MySQL
- **Hosting**: [InfinityFree](https://www.infinityfree.net/)

---

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/Jdsbecker/patteform.git
   cd patteform

    Import the SQL schema into MySQL:

    source database/schema.sql;

Configure your database connection in:

includes/db_connect.php

Deploy to your InfinityFree hosting or local PHP server:

php -S localhost:8000

Access the site at:

http://localhost:8000

🔑 Admin Dashboard

Default login:

username: admin
password: admin

    After 5 failed attempts, login is blocked until reset in DB.

📜 License

This project is under the GNU GENERAL PUBLIC LICENSE. You are free to use, modify, and distribute with attribution.
🤝 Contributing
Pull requests are welcome! For major changes, please open an issue first to discuss what you’d like to change.
