<?php
$pageTitle = "Universités";
require "../includes/header.php";
require "../config/DataBase.php";

// Get unique cities and types for filter dropdowns
$cities = $pdo->query("SELECT DISTINCT city FROM institutions ORDER BY city")->fetchAll(PDO::FETCH_COLUMN);
$types = $pdo->query("SELECT DISTINCT type FROM institutions ORDER BY type")->fetchAll(PDO::FETCH_COLUMN);

// Initial full load with deadlines
$sql = "SELECT institutions.*, deadlines.deadline_date 
        FROM institutions 
        LEFT JOIN deadlines ON institutions.id = deadlines.institution_id
        ORDER BY institutions.name ASC";
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

<h1 class="page-title">Universités & Écoles</h1>

<?php if (isset($_GET['success'])): ?>
    <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<!-- Search & Filters -->
<div class="search-bar">
    <input type="text" id="searchInput" placeholder="Rechercher une filière, université..." class="search-input">
    
    <div class="filters">
        <select id="filterCity" class="filter-select">
            <option value="">Toutes les villes</option>
            <?php foreach($cities as $c): ?>
                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
            <?php endforeach; ?>
        </select>

        <select id="filterType" class="filter-select">
            <option value="">Tous les types</option>
            <?php foreach($types as $t): ?>
                <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<p id="resultsCount" class="results-count"><?php echo count($institutions); ?> établissements trouvés</p>

<!-- Results Container -->
<div class="cards-grid" id="resultsGrid">
    <?php foreach($institutions as $inst): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                <h3><?php echo htmlspecialchars($inst["name"]); ?></h3>
                <span class="badge"><?php echo htmlspecialchars($inst["type"]); ?></span>
            </div>
            <p><?php echo htmlspecialchars($inst["city"]); ?></p>
            <p><?php echo htmlspecialchars($inst["description"]); ?></p>
            <p><span class="label">Moyenne min:</span> <?php echo htmlspecialchars($inst["min_average"]); ?>/20</p>
            <div class="requirements"><?php echo htmlspecialchars($inst["requirements"]); ?></div>

            <?php if (!empty($inst["deadline_date"])): ?>
                <?php
                    $deadline = new DateTime($inst["deadline_date"]);
                    $today = new DateTime();
                    $diff = $today->diff($deadline)->days;
                    $isPast = $deadline < $today;
                    
                    if ($isPast) {
                        $deadlineClass = "deadline-past";
                        $deadlineText = "Expiré";
                    } elseif ($diff <= 7) {
                        $deadlineClass = "deadline-urgent";
                        $deadlineText = "Dans " . $diff . " jour(s)";
                    } elseif ($diff <= 30) {
                        $deadlineClass = "deadline-soon";
                        $deadlineText = $deadline->format("d/m/Y");
                    } else {
                        $deadlineClass = "deadline-normal";
                        $deadlineText = $deadline->format("d/m/Y");
                    }
                ?>
                <div class="deadline-badge <?php echo $deadlineClass; ?>">
                    📅 Date limite: <?php echo $deadlineText; ?>
                </div>
            <?php endif; ?>

            <div class="card-actions">
                <a href="institution_detail.php?id=<?php echo $inst['id']; ?>" class="btn btn-primary">Voir détails</a>
                <?php if ($isLoggedIn): ?>
                    <?php if (in_array($inst['id'], $savedIds)): ?>
                        <span class="badge-saved">✅ Sauvegardé</span>
                    <?php else: ?>
                        <a href="../save_school.php?id=<?php echo $inst['id']; ?>" class="btn btn-save">Sauvegarder</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
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
            resultsCount.textContent = data.length + ' établissements trouvés';

            if (data.length === 0) {
                resultsGrid.innerHTML = `
                    <div class="empty-state" style="grid-column: 1/-1;">
                        <div class="icon">🔍</div>
                        <p>Aucun établissement trouvé.</p>
                    </div>`;
                return;
            }

            let html = '';
            data.forEach(inst => {
                html += `<div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                        <h3>${escHtml(inst.name)}</h3>
                        <span class="badge">${escHtml(inst.type)}</span>
                    </div>
                    <p>${escHtml(inst.city)}</p>
                    <p>${escHtml(inst.description)}</p>
                    <p><span class="label">Moyenne min:</span> ${escHtml(inst.min_average)}/20</p>
                    <div class="requirements">${escHtml(inst.requirements)}</div>
                    <div class="card-actions">
                        ${isLoggedIn ? `<a href="../save_school.php?id=${inst.id}" class="btn btn-save">Sauvegarder</a>` : ''}
                    </div>
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

searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(doSearch, 300);
});

filterCity.addEventListener('change', doSearch);
filterType.addEventListener('change', doSearch);
</script>

<?php require "../includes/footer.php"; ?>