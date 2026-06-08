# PatteForm

**Open-source animal shelter management platform.**
Deploy a complete adoption website + admin dashboard for any rescue organization — no DevOps experience required.

PatteForm is designed to be the turnkey digital solution for animal shelters: fully white-label, configurable from an admin panel, and deployable in minutes on a standard PHP shared host.

---

## What’s included

### Public site (for adopters)
- **Homepage** — hero, live animal stats, customizable content sections, donation simulator
- **Adoption listings** — filterable catalog with session-based favorites
- **News / Actualités** — rich articles with carousels, mosaics, images
- **Contact form** — messages stored in DB, accessible from dashboard
- **About / Team page** — manage team members from dashboard
- **Donation** — configurable link (HelloAsso, PayPal…)
- Mobile-responsive, Tailwind CSS

### Admin dashboard
- Full CRUD: animals, news, team, homepage sections
- Branding control: logo, colors, opening hours — no code needed
- Adoption workflow: move animals from à adopter → adopté
- Visit scheduling (Rencontrer)
- Login with brute-force protection (5 failed attempts → lockout)

### Developer experience
- **Setup wizard** — guided install at `/setup/`, no CLI needed
- `.env`-based configuration
- PHP 8+ with PDO throughout
- Standard MySQL/MariaDB — works on any shared host
- Zero npm, zero build step

---

## Quick start

### Requirements
- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.4+
- Apache / Nginx (or PHP built-in server for local dev)

### Install
```bash
git clone https://github.com/JordanBeckerds/PatteForm.git
cd PatteForm
cp .env.example .env   # edit with your DB credentials
```

Then navigate to `http://yoursite/setup/` and follow the wizard.

See [INSTALL.md](INSTALL.md) for the full guide including shared hosting instructions.

---

## Tech stack

| Layer | Technology |
|-------|------------|
| Frontend | PHP 8+, Tailwind CSS (CDN), vanilla JS |
| Backend | PHP 8+ |
| Database | MySQL 5.7+ / MariaDB 10.4+ |
| Hosting | Any PHP shared host (OVH, Hostinger, InfinityFree…) |

Zero build tools. Zero npm. Works on €2/month shared hosting.

---

## Who this is for

- SPA / ASPA regional chapters
- Municipal animal pounds (*fourrières*)
- Independent rescue associations (*associations de protection animale*)
- Veterinary clinics with adoption programs
- Any organization that rehomes animals and needs a digital presence

---

## Roadmap

- [ ] Multi-language UI (FR + EN — infrastructure in place, see `lang/`)
- [ ] Adoption application form with status tracking
- [ ] Email notifications (contact form, adoption requests)
- [ ] Docker Compose for self-hosted VPS deployment
- [ ] RGPD compliance module

---

## License

GNU General Public License v3.0 — see [LICENSE](LICENSE).

Free to use, fork, and deploy for non-commercial animal welfare use.
For commercial deployment or managed hosting for third parties: contact the author.

---

## Author

Jordan Beckerds · [github.com/JordanBeckerds](https://github.com/JordanBeckerds)
