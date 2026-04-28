# Maslaki Project — Progress Tracker

---

## 📊 Database Schema

| Table | Used in Code? | Status |
|-------|---------------|--------|
| `students` | ✅ | Registration, login, sessions |
| `institutions` | ✅ | List, detail, search, AI |
| `saved_schools` | ✅ | Save, remove, dashboard count |
| `ai_recommendations` | ✅ | Stores AI search results |
| `deadlines` | ✅ | Shown on cards + dashboard |
| `reviews` | ✅ | Submit, approve, display |

---

## ✅ Completed Features (30)

### Core
1. Database connection (PDO) ✔
2. Student registration + duplicate email check ✔
3. Student login + hashed password verify ✔
4. Logout (session destroy) ✔
5. Session protection on all private pages ✔

### Pages
6. Landing page — hero + stats bar (dynamic counts from DB) ✔
7. Dashboard — welcome + nav cards + upcoming deadlines ✔
8. Institutions list — card grid with all 49 schools ✔
9. Institution detail page — full info + reviews ✔
10. Saved schools page — with remove button ✔
11. AI form — bac dropdown + average + city ✔
12. AI results — smart filtering + save button ✔
13. Admin reviews panel — approve/reject ✔

### Features
14. Save school (duplicate prevention) ✔
15. Remove saved school (confirm dialog) ✔
16. "Already Saved" indicator on institution cards ✔
17. Save button hidden from guests ✔
18. AJAX search — `search_ajax.php` (text + city + type) ✔
19. Live search — debounced input + instant filters ✔
20. AI recommendations — bac→type mapping + average filter + city priority ✔
21. AI results saved to `ai_recommendations` table ✔
22. Deadlines on institution cards (color-coded urgency) ✔
23. Upcoming deadlines on dashboard (for saved schools) ✔
24. Reviews — submit (1 per user per school, pending status) ✔
25. Reviews — admin approve/reject panel ✔
26. Reviews — display approved reviews on detail page ✔

### Design (Navy + Orange)
27. Complete CSS redesign matching `maslaki_redesign_preview.html` ✔
28. All pages in French ✔
29. Shared header/footer with session-aware navbar ✔
30. XSS protection (`htmlspecialchars`) on all output ✔

---

## ❌ Missing / Future Features

1. Student profile page (view/edit profile info)
2. Admin role system (currently any logged-in user can access admin)
3. Password reset functionality
4. Pagination on institutions page (currently loads all 49)

---

## 🐞 All Bugs Fixed

1. ~~`save_school.php` dead-end~~ → Redirects ✔
2. ~~`register_process.php` dead-end~~ → Redirects to login ✔
3. ~~`login_process.php` dead-end~~ → Redirects with error ✔
4. ~~No XSS protection~~ → `htmlspecialchars()` ✔
5. ~~`save_school.php` no validation~~ → Validates + prevents duplicates ✔
6. ~~`index.php` test page~~ → Real landing page ✔
7. ~~No navigation~~ → Shared navbar ✔
8. ~~`ai_process.php` ignores input~~ → Smart filtering ✔
9. ~~404 on XAMPP~~ → Junction link created ✔

---

## 📁 Final File Structure

```
Maslaki-projet/
├── index.php                    ← Landing page (hero + stats)
├── login_process.php            ← Login backend
├── register_process.php         ← Register backend
├── save_school.php              ← Save school backend
├── remove_school.php            ← Remove saved school
├── search_ajax.php              ← AJAX search endpoint
├── ai_process.php               ← AI results page
├── submit_review.php            ← Review submission backend
├── maslaki.sql                  ← Database schema + data
│
├── config/
│   └── DataBase.php             ← PDO connection
│
├── views/
│   ├── login.php                ← Login form
│   ├── register.php             ← Register form
│   ├── dashboard.php            ← User dashboard
│   ├── institutions.php         ← School list + search
│   ├── institution_detail.php   ← School detail + reviews
│   ├── saved_schools.php        ← Saved schools list
│   ├── ai_form.php              ← AI orientation form
│   ├── admin_reviews.php        ← Admin review panel
│   └── logout.php               ← Logout
│
├── assets/
│   ├── css/style.css            ← Full design system
│   └── js/script.js             ← JS placeholder
│
├── includes/
│   ├── header.php               ← Shared navbar
│   └── footer.php               ← Shared footer
│
├── database/
│   └── seed_deadlines.sql       ← Deadline seed data
│
└── md/
    ├── structure.md
    └── workflow.md
```

---

## ⚠️ IMPORTANT: Run this SQL to enable deadlines

Import `database/seed_deadlines.sql` in phpMyAdmin to populate the deadlines table.

---

*Last updated: 2026-04-27 — ALL PHASES COMPLETE*
