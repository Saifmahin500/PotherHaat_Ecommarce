<?php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (empty($_SESSION['admin_logged_in'])) {
	echo json_encode(['error' => 'Not logged in', 'count' => 0]);
	exit;
}

require __DIR__ . '/../../config/dbconfig.php';

try {
	$database = new Database();
	$conn = $database->dbConnection();

	$stmt = $conn->query("SELECT COUNT(*) AS c FROM contact_message WHERE is_read = 0");
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	echo json_encode(['count' => (int)($row['c'] ?? 0)]);
} catch (Exception $e) {
	echo json_encode(['error' => $e->getMessage()]);
}
