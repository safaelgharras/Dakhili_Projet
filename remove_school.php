<?php
session_start();
require "config/DataBase.php";

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// #region agent log
@file_put_contents(__DIR__ . '/debug-3cb009.log', json_encode([
    'sessionId' => '3cb009',
    'runId' => 'pre-fix',
    'hypothesisId' => 'H1-H2',
    'location' => 'remove_school.php:6',
    'message' => 'Delete endpoint entry',
    'data' => [
        'method' => $_SERVER['REQUEST_METHOD'] ?? null,
        'isAjax' => $isAjax,
        'hasSessionUser' => isset($_SESSION["user_id"]),
        'rawPostId' => $_POST["id"] ?? null,
        'rawGetId' => $_GET["id"] ?? null
    ],
    'timestamp' => round(microtime(true) * 1000)
]) . PHP_EOL, FILE_APPEND);
// #endregion

function respondDelete($isAjax, $status, $message, $redirectParam = 'error') {
    // #region agent log
    @file_put_contents(__DIR__ . '/debug-3cb009.log', json_encode([
        'sessionId' => '3cb009',
        'runId' => 'pre-fix',
        'hypothesisId' => 'H3',
        'location' => 'remove_school.php:10',
        'message' => 'Delete endpoint response path',
        'data' => [
            'isAjax' => $isAjax,
            'status' => $status,
            'message' => $message,
            'redirectParam' => $redirectParam
        ],
        'timestamp' => round(microtime(true) * 1000)
    ]) . PHP_EOL, FILE_APPEND);
    // #endregion

    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['status' => $status, 'message' => $message]);
        exit();
    }

    header("Location: views/saved_schools.php?{$redirectParam}=" . urlencode($message));
    exit();
}

if (!isset($_SESSION["user_id"])) {
    // #region agent log
    @file_put_contents(__DIR__ . '/debug-3cb009.log', json_encode([
        'sessionId' => '3cb009',
        'runId' => 'pre-fix-3',
        'hypothesisId' => 'H9',
        'location' => 'remove_school.php:31',
        'message' => 'Delete blocked: user not authenticated',
        'data' => [
            'isAjax' => $isAjax,
            'method' => $_SERVER['REQUEST_METHOD'] ?? null
        ],
        'timestamp' => round(microtime(true) * 1000)
    ]) . PHP_EOL, FILE_APPEND);
    // #endregion

    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit();
    }
    header("Location: views/login.php");
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"] ?? 'GET';
$rawId = null;

if ($requestMethod === "POST" && isset($_POST["id"])) {
    $rawId = $_POST["id"];
} elseif ($requestMethod === "GET" && isset($_GET["id"])) {
    // Keep GET as fallback for compatibility with older links.
    $rawId = $_GET["id"];
}

if ($rawId === null || !is_numeric($rawId)) {
    // #region agent log
    @file_put_contents(__DIR__ . '/debug-3cb009.log', json_encode([
        'sessionId' => '3cb009',
        'runId' => 'pre-fix',
        'hypothesisId' => 'H2',
        'location' => 'remove_school.php:57',
        'message' => 'Invalid institution id payload',
        'data' => [
            'requestMethod' => $requestMethod,
            'rawId' => $rawId
        ],
        'timestamp' => round(microtime(true) * 1000)
    ]) . PHP_EOL, FILE_APPEND);
    // #endregion

    respondDelete($isAjax, 'error', 'Invalid school', 'error');
}

$student_id = $_SESSION["user_id"];
$institution_id = (int) $rawId;

$sql = "DELETE FROM saved_schools WHERE student_id = ? AND institution_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id, $institution_id]);

// #region agent log
@file_put_contents(__DIR__ . '/debug-3cb009.log', json_encode([
    'sessionId' => '3cb009',
    'runId' => 'pre-fix',
    'hypothesisId' => 'H4',
    'location' => 'remove_school.php:77',
    'message' => 'Delete query executed',
    'data' => [
        'studentId' => $student_id,
        'institutionId' => $institution_id,
        'rowCount' => $stmt->rowCount()
    ],
    'timestamp' => round(microtime(true) * 1000)
]) . PHP_EOL, FILE_APPEND);
// #endregion

if ($stmt->rowCount() > 0) {
    respondDelete($isAjax, 'success', 'Ecole supprimee avec succes', 'success');
}

respondDelete($isAjax, 'error', 'Ecole introuvable ou deja supprimee', 'error');
?>
