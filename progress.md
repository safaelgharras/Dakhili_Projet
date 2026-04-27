# Dakhili Project — Progress Tracker

---

## 📊 Database Schema (verified from `dakhili.sql`)

| Table | Columns | Used in Code? |
|-------|---------|---------------|
| `students` | id, name, email, password, bac_branch, average, city, created_at | ✅ Yes |
| `institutions` | id, name, city, type, min_average, description, created_at, requirements | ✅ Yes |
| `saved_schools` | id, student_id, institution_id, created_at | ✅ Yes |
| `ai_recommendations` | id, student_id, result, created_at | ❌ NOT used in code |
| `deadlines` | id, institution_id, deadline_date | ❌ NOT used in code |
| `reviews` | id, student_id, institution_id, content, status(pending/approved), created_at | ❌ NOT used in code |

---

## ✅ Completed Features

1. **Database connection** — `config/DataBase.php` ✔
2. **Student registration** — form + backend + redirect + duplicate email check ✔
3. **Student login** — form + backend + redirect on error ✔
4. **Logout** — session destroy + redirect ✔
5. **Dashboard** — styled welcome banner + nav cards ✔
6. **List institutions** — card grid with all 49 schools ✔
7. **Save a school** — duplicate prevention + redirect with message ✔
8. **View saved schools** — card grid + empty state ✔
9. **AI form (frontend)** — styled with bac dropdown ✔
10. **Shared header/navbar** — session-aware, all pages ✔
11. **Shared footer** — all pages ✔
12. **CSS styling** — full design system (cards, forms, nav, responsive) ✔
13. **Landing page (`index.php`)** — hero section with CTA ✔
14. **Login ↔ Register links** ✔
15. **Save button hidden from guests** ✔
16. **XSS protection** — `htmlspecialchars()` on all output ✔
17. **Input validation** — GET id check, email duplicate check ✔
18. **Success/Error messages** — styled flash messages ✔
19. **AI recommendations (smart filtering)** — filters by average + bac branch → school type mapping + city priority ✔
20. **AI results saved to DB** — inserts into `ai_recommendations` table ✔
21. **Save button on AI results** — can save recommended schools ✔

---

## 🔄 Features In Progress

_(none currently)_

---

## ❌ Missing Features

1. `search_ajax.php` — No AJAX search functionality
2. **Remove saved school** — No unsave feature
3. **Deadlines feature** — Table exists, no code
4. **Reviews feature** — Table exists, no code
5. **Student profile page** — Cannot view/edit profile

---

## 🐞 Bugs Fixed (this session)

1. ~~`save_school.php` dead-end~~ → Now redirects with message ✔
2. ~~`register_process.php` dead-end~~ → Now redirects to login ✔
3. ~~`login_process.php` dead-end on failure~~ → Now redirects with error ✔
4. ~~No XSS protection~~ → Added `htmlspecialchars()` everywhere ✔
5. ~~`save_school.php` no GET validation~~ → Now validates + prevents duplicates ✔
6. ~~`index.php` test page~~ → Now a proper landing page ✔
7. ~~No navigation~~ → Shared header with navbar on all pages ✔
8. ~~Save button visible to guests~~ → Now hidden if not logged in ✔
9. ~~No Login ↔ Register links~~ → Added ✔

---

## 🐞 Remaining Bugs

_(none currently)_

---

## 📌 Next Step

**Phases 1, 2, 3 (partial), 5 are DONE.**

**Next: Add "Remove saved school" feature + create `search_ajax.php` for AJAX search (Phase 3.2 + Phase 4)**

---

*Last updated: 2026-04-27*
