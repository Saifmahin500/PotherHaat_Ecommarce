<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Response JSON হিসেবে পাঠানো হবে
header('Content-Type: application/json; charset=utf-8');

// Strict error reporting (display off, logging on)
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_PARSE);

ob_start();

try {

    // Unauthorized check
    if (empty($_SESSION['admin_logged_in'])) {
        throw new Exception('Unauthorized');
    }

    require_once __DIR__ . '/../../config/dbconfig.php';
    require_once __DIR__ . '/../../config/class_user.php';

    // Request method validation
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid Request');
    }

    // Input validation
    $id = (int)($_POST['id'] ?? 0);
    $text = trim($_POST['reply'] ?? '');

    if ($id <= 0 || $text === '') {
        throw new Exception('Missing Data');
    }

    // Database connection
    $database = new Database();
    $conn = $database->dbConnection();

    // Fetch message
    $st = $conn->prepare("SELECT * FROM contact_message WHERE id = ?");
    $st->execute([$id]);
    $msg = $st->fetch(PDO::FETCH_ASSOC);

    if (!$msg) {
        throw new Exception('Message Not Found!');
    }

    $toEmail = $msg['email'];
    $subject = 'Reply: ' . ($msg['subject'] ?: 'Your Inquiry');

    // HTML email content
    $html = '
        <div style="font-family: Arial; font-size: 14px; line-height: 1.6; color: #333;">
            <p>Hi ' . htmlspecialchars($msg['name']) . ',</p>
            <p>' . nl2br(htmlspecialchars($text)) . '</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="font-size: 12px; color: #777;">
                This is a reply to your message submitted on ' . htmlspecialchars($msg['created_at']) . '.
            </p>
        </div>';

    $user = new USER();
    $ok = $user->sendMail($toEmail, $subject, $html);

    if (!$ok) {
        $err = $_SESSION['mailError'] ?? 'Email failed';
        throw new Exception($err);
    }

    // Update database
    $up = $conn->prepare("UPDATE contact_message SET is_replied = 1, reply_text = ?, replied_at = NOW() WHERE id = ?");
    $up->execute([$text, $id]);

    ob_end_clean();
    echo json_encode([
        'ok' => true,
        'message' => 'Reply sent successfully!'
    ]);
} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'debug' => [
            'post_data' => $_POST,
            'session' => $_SESSION,
            'method' => $_SERVER['REQUEST_METHOD']
        ]
    ]);
}
