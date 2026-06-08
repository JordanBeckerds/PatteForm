# PatteForm — Installation Guide

This guide covers local development, shared hosting, and VPS.

---

## 1. Local development

### Prerequisites
- PHP 8.0+ (`php --version`)
- MySQL 5.7+ or MariaDB
- Git

### Steps

```bash
git clone https://github.com/JordanBeckerds/PatteForm.git
cd PatteForm
cp .env.example .env
# Edit .env: set DB_HOST, DB_NAME, DB_USER, DB_PASS

# Start built-in PHP server
php -S localhost:8000
```

Then open `http://localhost:8000/setup/` to run the wizard.

The wizard will:
1. Check PHP requirements (version, PDO, MySQL driver)
2. Create the database and import the SQL schema
3. Ask for your shelter’s name, address, and brand colors
4. Create your admin account
5. Write a `.installed` marker so setup can’t be accidentally re-run

**After setup**
- Public site: `http://localhost:8000/public/`
- Admin dashboard: `http://localhost:8000/public/dashboard.php`

---

## 2. Shared hosting (InfinityFree, OVH, Hostinger…)

### Option A — File manager upload

1. Download this repo as ZIP (GitHub → Code → Download ZIP)
2. Upload and extract to `public_html/` (or a subfolder)
3. In your host’s control panel, create a MySQL database and note the credentials
4. Copy `.env.example` to `.env`, fill in the DB credentials
5. Visit `https://yoursite.com/setup/` to run the wizard

### Option B — Git deploy (if your host supports SSH)

```bash
ssh user@yourhost
cd public_html
git clone https://github.com/JordanBeckerds/PatteForm.git .
cp .env.example .env
nano .env
```

Then visit `/setup/`.

### Troubleshooting

| Problem | Fix |
|---------|-----|
| Blank page | Check PHP error log; ensure PHP 8+ is selected in host panel |
| DB connection error | Some hosts use `127.0.0.1` instead of `localhost` |
| File not found | Check that `.env` is present (FTP clients sometimes hide dot-files) |
| Images not loading | Ensure `assets/img/` is writable: `chmod 755 assets/img/` |

---

## 3. VPS (nginx + PHP-FPM)

```nginx
server {
    listen 80;
    server_name yoursite.com;
    root /var/www/patteform/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # Protect sensitive files
    location ~ /\.(env|installed|git) {
        deny all;
    }
}
```

For HTTPS: `certbot --nginx -d yoursite.com`

---

## Environment variables

All configuration lives in `.env` (copied from `.env.example`):

| Variable | Description | Default |
|----------|-------------|----------|
| `DB_HOST` | Database host | `localhost` |
| `DB_NAME` | Database name | `patteform` |
| `DB_USER` | Database user | `root` |
| `DB_PASS` | Database password | *(empty)* |
| `APP_LANG` | Default language (`fr` or `en`) | `fr` |
| `APP_URL` | Full URL of your installation | `http://localhost:8000` |

---

## Re-running setup

To reset and re-run the wizard, delete `.installed` from the project root:

```bash
rm .installed
```

Then visit `/setup/`. **Warning: this re-imports the SQL schema and may overwrite data.**

---

## Updating

```bash
git pull origin main
```

Check the [releases page](https://github.com/JordanBeckerds/PatteForm/releases) for any manual migration steps.
