# 🗺️ Dakhili — Complete Development Workflow

> A step-by-step roadmap to build the full Dakhili platform.
> Each phase builds on the previous one. Follow in order.

---

## Phase 1: 🏗️ Foundation (Layout & Navigation)
> **Goal:** Every page looks consistent and professional.

| Step | Task | Files |
|------|------|-------|
| 1.1 | Create `includes/header.php` — HTML head, navbar (Dashboard, Schools, Saved, AI, Logout), CSS link | `includes/header.php` |
| 1.2 | Create `includes/footer.php` — Footer content, JS link, closing tags | `includes/footer.php` |
| 1.3 | Create `assets/css/style.css` — Base styles, navbar, cards, buttons, forms, responsive design | `assets/css/style.css` |
| 1.4 | Update ALL views to use `header.php` + `footer.php` | `views/*.php` |
| 1.5 | Fix `index.php` — Make it a real landing page (hero, CTA to login/register) | `index.php` |

**✅ Done when:** All pages share the same nav, footer, and look clean.

---

## Phase 2: 🔐 Fix Authentication Flow
> **Goal:** Login, register, and error handling work smoothly.

| Step | Task | Files |
|------|------|-------|
| 2.1 | Add redirect after registration → login page with success message | `register_process.php` |
| 2.2 | Fix login failure — redirect back to login with error message (not blank page) | `login_process.php` |
| 2.3 | Add Login ↔ Register links on both pages | `views/login.php`, `views/register.php` |
| 2.4 | Add input validation (empty fields, email format, password length) | `register_process.php`, `login_process.php` |
| 2.5 | Add duplicate email check on registration | `register_process.php` |
| 2.6 | Add XSS protection with `htmlspecialchars()` on all output | All views |

**✅ Done when:** User can register → see success → login → see dashboard. Errors show proper messages.

---

## Phase 3: 🏫 Fix Institutions & Save Feature
> **Goal:** Browsing and saving schools works properly.

| Step | Task | Files |
|------|------|-------|
| 3.1 | Fix `save_school.php` — validate GET `id`, prevent duplicates, redirect back with message | `save_school.php` |
| 3.2 | Add "Remove saved school" button | `saved_schools.php`, new `remove_school.php` |
| 3.3 | Use AJAX for save/remove (no page reload) | `assets/js/script.js`, `save_school.php` |
| 3.4 | Show ⭐ Save button only to logged-in users | `views/institutions.php` |
| 3.5 | Show "Already Saved" status on institutions page | `views/institutions.php` |

**✅ Done when:** User can save/unsave schools smoothly. No duplicates. Visual feedback.

---

## Phase 4: 🔍 Search & Filter (AJAX)
> **Goal:** Students can search and filter schools without page reload.

| Step | Task | Files |
|------|------|-------|
| 4.1 | Add search bar + filter dropdowns to `institutions.php` (city, type, min average) | `views/institutions.php` |
| 4.2 | Create `search_ajax.php` — receives filters via GET, returns filtered results as HTML or JSON | `search_ajax.php` |
| 4.3 | Add JavaScript to send AJAX requests on input/filter change | `assets/js/script.js` |
| 4.4 | Display results dynamically without page reload | `assets/js/script.js` |

**✅ Done when:** Typing in search box or changing filters instantly updates the school list.

---

## Phase 5: 🤖 Smart AI Recommendations
> **Goal:** Students get personalized school suggestions based on their profile.

| Step | Task | Files |
|------|------|-------|
| 5.1 | Fix `ai_process.php` — read `$_POST` data (bac_branch, average, city) | `ai_process.php` |
| 5.2 | Build smart SQL query — filter by `min_average <= student_average`, match city, match type to bac_branch | `ai_process.php` |
| 5.3 | Auto-fill AI form with student's profile data from session/DB | `views/ai_form.php` |
| 5.4 | Save results to `ai_recommendations` table | `ai_process.php` |
| 5.5 | Display results as styled cards (not inline HTML) | `ai_process.php` or new `views/ai_results.php` |
| 5.6 | Add "Save" button on recommended schools | `views/ai_results.php` |

**✅ Done when:** Student submits form → gets relevant schools matching their profile → can save them.

---

## Phase 6: 📅 Deadlines Feature
> **Goal:** Show application deadlines for each institution.

| Step | Task | Files |
|------|------|-------|
| 6.1 | Seed `deadlines` table with sample data | `dakhili.sql` or admin |
| 6.2 | Show deadline on institution cards (JOIN with `deadlines`) | `views/institutions.php` |
| 6.3 | Highlight upcoming deadlines (< 7 days = red, < 30 days = orange) | `assets/css/style.css` |
| 6.4 | Add "Upcoming Deadlines" section on dashboard | `views/dashboard.php` |

**✅ Done when:** Students see deadlines on school cards and dashboard alerts.

---

## Phase 7: 💬 Reviews Feature
> **Goal:** Students can leave reviews on institutions.

| Step | Task | Files |
|------|------|-------|
| 7.1 | Create review form on institution detail page (or modal) | New `views/institution_detail.php` |
| 7.2 | Create `submit_review.php` — insert into `reviews` (status = 'pending') | New `submit_review.php` |
| 7.3 | Display approved reviews on institution page | `views/institution_detail.php` |
| 7.4 | Build basic admin page to approve/reject reviews | New `views/admin_reviews.php` |

**✅ Done when:** Students submit reviews → admin approves → reviews show on school pages.

---

## Phase 8: 🎨 Final Polish
> **Goal:** Professional look and feel.

| Step | Task | Files |
|------|------|-------|
| 8.1 | Responsive design — mobile-friendly navbar, cards stack vertically | `assets/css/style.css` |
| 8.2 | Add loading spinners for AJAX requests | `assets/js/script.js`, CSS |
| 8.3 | Add empty states (no results, no saved schools) with icons | Views |
| 8.4 | Add student profile page (view/edit profile info) | New `views/profile.php` |
| 8.5 | Improve dashboard — stats (schools saved, recommendations, deadlines) | `views/dashboard.php` |
| 8.6 | Final testing of all features end-to-end | All files |

**✅ Done when:** App looks professional, works on mobile, all features connected.

---

## 📋 Summary — Build Order

```
Phase 1: Layout & Nav          ███░░░░░░░░░ Foundation
Phase 2: Auth Flow Fix         █████░░░░░░░ Core
Phase 3: Save/Remove Schools   ███████░░░░░ Core
Phase 4: AJAX Search           █████████░░░ Feature
Phase 5: AI Recommendations    ██████████░░ Feature
Phase 6: Deadlines             ███████████░ Feature
Phase 7: Reviews               ███████████░ Feature
Phase 8: Polish                ████████████ Final
```

---

## ⚠️ Rules

1. **Complete each phase before starting the next**
2. **Test after every step** — open in browser, check it works
3. **One step at a time** — don't skip ahead
4. **Never break what already works**
5. **Update `progress.md` after each step**

---

*Created: 2026-04-27*
