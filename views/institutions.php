<?php
$pageTitle = "Établissements";
require "../includes/header.php";
require "../config/DataBase.php";

// Get metadata for filters with safety checks
$villes = [];
$categories = [];
$migrationNeeded = false;

try {
    $villes = $pdo->query("SELECT * FROM villes ORDER BY nom ASC")->fetchAll();
    $categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();
} catch (Exception $e) {
    $migrationNeeded = true;
}

$types = [];
try {
    $types = $pdo->query("SELECT DISTINCT type FROM institutions WHERE type IS NOT NULL ORDER BY type")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {}

$isLoggedIn = isset($_SESSION['user_id']);

// Initial load
$sql = "SELECT * FROM institutions";
try {
    // Try with is_popular if it exists
    $pdo->query("SELECT is_popular FROM institutions LIMIT 1");
    $sql .= " ORDER BY is_popular DESC, name ASC";
} catch (Exception $e) {
    $sql .= " ORDER BY name ASC";
}
$institutions = $pdo->query($sql)->fetchAll();


// Get saved IDs
$savedIds = [];
if ($isLoggedIn) {
    $savedIds = $pdo->query("SELECT institution_id FROM saved_schools WHERE student_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_COLUMN);
}
?>

<div class="institutions-layout">
    <?php if ($migrationNeeded): ?>
        <div class="migration-alert" style="grid-column: 1/-1; background: #fffbeb; border: 1px solid #fef3c7; color: #92400e; padding: 16px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
            <span>⚠️</span>
            <div>
                <strong>Mise à jour requise :</strong> De nouvelles fonctionnalités ont été ajoutées. 
                <a href="../migrate.php" style="text-decoration: underline; font-weight: 700;">Cliquez ici pour mettre à jour votre base de données</a>.
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Sidebar -->

    <aside class="filter-sidebar">
        <div class="sidebar-header">
            <h3>Filtres</h3>
        </div>

        <div class="filter-group">
            <label>Recherche rapide</label>
            <input type="text" id="searchInput" placeholder="Nom de l'école, filière..." class="search-input">
        </div>

        <div class="filter-group">
            <label>📍 Ville</label>
            <select id="filterCity" class="filter-select">
                <option value="">Toutes les villes</option>
                <?php foreach($villes as $v): ?>
                    <option value="<?php echo $v['id']; ?>"><?php echo htmlspecialchars($v['nom']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>📚 Catégorie</label>
            <select id="filterCategory" class="filter-select">
                <option value="">Tous les domaines</option>
                <?php foreach($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nom']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>🏢 Type</label>
            <select id="filterType" class="filter-select">
                <option value="">Public & Privé</option>
                <?php foreach($types as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button id="resetFilters" class="btn btn-outline btn-full" style="margin-top: 20px; border-color: var(--border-color); color: var(--text-muted);">
            Réinitialiser les filtres
        </button>
    </aside>


    <!-- Results Main -->
    <main class="results-main">
        <div class="results-header">
            <h1 class="page-title">Découvrir les établissements</h1>
            <p id="resultsCount" class="results-count"><?php echo count($institutions); ?> établissements trouvés</p>
        </div>

        <div class="cards-grid" id="resultsGrid">
            <?php foreach($institutions as $inst): ?>
                <div class="card">
                    <img src="../assets/images/institutions/<?php echo $inst['image'] ?? 'default_school.jpg'; ?>" class="card-img" alt="<?php echo htmlspecialchars($inst['name']); ?>">
                    <div class="card-body">
                        <div class="card-tag"><?php echo htmlspecialchars($inst['type']); ?></div>
                        <h3><?php echo htmlspecialchars($inst['name']); ?></h3>
                        <p class="school-location">📍 <?php echo htmlspecialchars($inst['city'] ?? 'Maroc'); ?></p>
                        
                        <div class="card-info-row">
                            <span class="info-item">🎓 <?php echo htmlspecialchars($inst['diplome'] ?? 'Diplôme'); ?></span>
                            <span class="info-item">⏳ <?php echo htmlspecialchars($inst['duree_etudes'] ?? '--'); ?></span>
                        </div>

                        <div class="card-footer">
                            <span class="seuil">Seuil: <strong><?php echo $inst['seuil'] ?? '--'; ?></strong></span>
                            <div class="card-actions">
                                <a href="institution_detail.php?id=<?php echo $inst['id']; ?>" class="btn-link">Détails →</a>
                                <?php if ($isLoggedIn): ?>
                                    <button class="btn-icon-save <?php echo in_array($inst['id'], $savedIds) ? 'active' : ''; ?>" 
                                            onclick="toggleSave(<?php echo $inst['id']; ?>, this)">
                                        ❤
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<style>
.institutions-layout { display: grid; grid-template-columns: 320px 1fr; gap: 40px; padding: 40px 0; }
.filter-sidebar { background: #fff; padding: 30px; border-radius: 24px; box-shadow: var(--shadow-md); height: fit-content; position: sticky; top: 120px; border: 1px solid var(--border-color); }
.sidebar-header h3 { font-size: 1.3rem; font-weight: 800; color: var(--primary); margin-bottom: 25px; border-bottom: 2px solid var(--bg-light); padding-bottom: 15px; }

.filter-group { margin-bottom: 25px; }
.filter-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
.filter-select, .search-input { width: 100%; padding: 14px 18px; border-radius: 12px; border: 1.5px solid var(--border-color); background: #f8fafc; font-size: 0.95rem; transition: var(--transition); color: var(--text-dark); }
.filter-select:focus, .search-input:focus { border-color: var(--primary); background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1); }

.results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.results-header h2 { font-size: 1.8rem; font-weight: 800; color: var(--primary-dark); }
.results-count { color: var(--text-muted); font-weight: 600; }

.cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }

@media (max-width: 992px) {
    .institutions-layout { grid-template-columns: 1fr; }
    .filter-sidebar { position: static; margin-bottom: 30px; }
}

.card-info-row {
    display: flex;
    gap: 12px;
    margin: 10px 0;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.btn-icon-save {
    background: none;
    border: 1px solid var(--border-color);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    color: #cbd5e1;
}

.btn-icon-save.active {
    background: #fff1f2;
    border-color: #fda4af;
    color: #e11d48;
}

@media (max-width: 992px) {
    .institutions-layout {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
const searchInput = document.getElementById('searchInput');
const filterCity = document.getElementById('filterCity');
const filterCategory = document.getElementById('filterCategory');
const filterType = document.getElementById('filterType');
const resultsGrid = document.getElementById('resultsGrid');
const resultsCount = document.getElementById('resultsCount');
const resetBtn = document.getElementById('resetFilters');

let debounceTimer;

function doSearch() {
    const params = new URLSearchParams();
    if (searchInput.value) params.set('search', searchInput.value);
    if (filterCity.value) params.set('city_id', filterCity.value);
    if (filterCategory.value) params.set('cat_id', filterCategory.value);
    if (filterType.value) params.set('type', filterType.value);

    fetch('../search_ajax.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
            resultsCount.textContent = data.length + ' établissements trouvés';
            renderResults(data);
        });
}

function renderResults(data) {
    if (data.length === 0) {
        resultsGrid.innerHTML = '<div class="empty-state">Aucun établissement ne correspond à vos critères.</div>';
        return;
    }

    resultsGrid.innerHTML = data.map(inst => `
        <div class="card">
            <img src="../assets/images/institutions/${inst.image || 'default_school.jpg'}" class="card-img" alt="${inst.name}">
            <div class="card-body">
                <div class="card-tag">${inst.type}</div>
                <h3>${inst.name}</h3>
                <p class="school-location">📍 ${inst.city || 'Maroc'}</p>
                <div class="card-info-row">
                    <span>🎓 ${inst.diplome || 'Diplôme'}</span>
                    <span>⏳ ${inst.duree_etudes || '--'}</span>
                </div>
                <div class="card-footer">
                    <span class="seuil">Seuil: <strong>${inst.seuil || '--'}</strong></span>
                    <div class="card-actions">
                        <a href="institution_detail.php?id=${inst.id}" class="btn-link">Détails →</a>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function toggleSave(id, btn) {
    fetch(`../save_school.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                btn.classList.toggle('active');
            }
        });
}

searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(doSearch, 300);
});

[filterCity, filterCategory, filterType].forEach(el => el.addEventListener('change', doSearch));

resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    filterCity.value = '';
    filterCategory.value = '';
    filterType.value = '';
    doSearch();
});
</script>

<?php require "../includes/footer.php"; ?>