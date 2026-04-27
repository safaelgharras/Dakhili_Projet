<?php
$pageTitle = "Institutions";
require "../includes/header.php";
require "../config/DataBase.php";

// Get unique cities and types for filter dropdowns
$cities = $pdo->query("SELECT DISTINCT city FROM institutions ORDER BY city")->fetchAll(PDO::FETCH_COLUMN);
$types = $pdo->query("SELECT DISTINCT type FROM institutions ORDER BY type")->fetchAll(PDO::FETCH_COLUMN);

// Initial full load
$sql = "SELECT * FROM institutions ORDER BY name ASC";
$stmt = $pdo->query($sql);
$institutions = $stmt->fetchAll();

$isLoggedIn = isset($_SESSION['user_id']);

// Get saved school IDs for current user
$savedIds = [];
if ($isLoggedIn) {
    $savedStmt = $pdo->prepare("SELECT institution_id FROM saved_schools WHERE student_id = ?");
    $savedStmt->execute([$_SESSION['user_id']]);
    $savedIds = $savedStmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<h1 class="page-title">🏫 List of Schools</h1>

<?php if (isset($_GET['success'])): ?>
    <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<!-- Search & Filters -->
<div class="search-bar">
    <input type="text" id="searchInput" placeholder="🔍 Search schools by name..." class="search-input">
    
    <div class="filters">
        <select id="filterCity" class="filter-select">
            <option value="">All Cities</option>
            <?php foreach($cities as $c): ?>
                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
            <?php endforeach; ?>
        </select>

        <select id="filterType" class="filter-select">
            <option value="">All Types</option>
            <?php foreach($types as $t): ?>
                <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<p id="resultsCount" class="results-count">Showing <?php echo count($institutions); ?> schools</p>

<!-- Results Container -->
<div class="cards-grid" id="resultsGrid">
    <?php foreach($institutions as $inst): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($inst["name"]); ?></h3>
            <p><span class="label">City:</span> <?php echo htmlspecialchars($inst["city"]); ?></p>
            <p><span class="label">Type:</span> <span class="badge"><?php echo htmlspecialchars($inst["type"]); ?></span></p>
            <p><span class="label">Min Average:</span> <?php echo htmlspecialchars($inst["min_average"]); ?>/20</p>
            <p><?php echo htmlspecialchars($inst["description"]); ?></p>
            <div class="requirements">
                <span class="label">Requirements:</span> <?php echo htmlspecialchars($inst["requirements"]); ?>
            </div>

            <?php if ($isLoggedIn): ?>
                <?php if (in_array($inst['id'], $savedIds)): ?>
                    <span class="badge-saved">✅ Already Saved</span>
                <?php else: ?>
                    <a href="../save_school.php?id=<?php echo $inst['id']; ?>" class="btn btn-save">⭐ Save</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

const searchInput = document.getElementById('searchInput');
const filterCity = document.getElementById('filterCity');
const filterType = document.getElementById('filterType');
const resultsGrid = document.getElementById('resultsGrid');
const resultsCount = document.getElementById('resultsCount');

let debounceTimer;

function doSearch() {
    const search = searchInput.value.trim();
    const city = filterCity.value;
    const type = filterType.value;

    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (city) params.set('city', city);
    if (type) params.set('type', type);

    fetch('../search_ajax.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
            resultsCount.textContent = 'Showing ' + data.length + ' schools';

            if (data.length === 0) {
                resultsGrid.innerHTML = `
                    <div class="empty-state" style="grid-column: 1/-1;">
                        <div class="icon">🔍</div>
                        <p>No schools found matching your filters.</p>
                    </div>`;
                return;
            }

            let html = '';
            data.forEach(inst => {
                html += `<div class="card">
                    <h3>${escHtml(inst.name)}</h3>
                    <p><span class="label">City:</span> ${escHtml(inst.city)}</p>
                    <p><span class="label">Type:</span> <span class="badge">${escHtml(inst.type)}</span></p>
                    <p><span class="label">Min Average:</span> ${escHtml(inst.min_average)}/20</p>
                    <p>${escHtml(inst.description)}</p>
                    <div class="requirements">
                        <span class="label">Requirements:</span> ${escHtml(inst.requirements)}
                    </div>
                    ${isLoggedIn ? `<a href="../save_school.php?id=${inst.id}" class="btn btn-save">⭐ Save</a>` : ''}
                </div>`;
            });
            resultsGrid.innerHTML = html;
        })
        .catch(err => console.error('Search error:', err));
}

function escHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Debounced search on typing
searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(doSearch, 300);
});

// Instant search on filter change
filterCity.addEventListener('change', doSearch);
filterType.addEventListener('change', doSearch);
</script>

<?php require "../includes/footer.php"; ?>