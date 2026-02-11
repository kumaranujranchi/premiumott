<?php
include 'includes/db.php';

// SECURITY: Change this to a hard-to-guess secret!
// You will put this same secret in your SMS Forwarder App URL
// Example: https://yourwebsite.com/webhook.php?secret=MY_SECURE_KEY
$secret_key = "MY_SECURE_KEY";

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_key) {
    http_response_code(403);
    die("Forbidden: Invalid Secret Key");
}

// Get the SMS message from the app
// different apps use different parameter names (e.g., 'text', 'message', 'body')
$message = $_REQUEST['message'] ?? $_REQUEST['text'] ?? $_REQUEST['body'] ?? '';
$sender = $_REQUEST['from'] ?? $_REQUEST['sender'] ?? '';

if (empty($message)) {
    die("No message received");
}

// LOGGING (Optional: for debugging)
file_put_contents('webhook_log.txt', date('Y-m-d H:i:s') . " - Sender: $sender - Msg: $message\n", FILE_APPEND);

// REGEX PATTERNS to find Amount and UTR/Ref No
// Adjust these based on your bank's SMS format
$patterns = [
    // Pattern 1: Credited INR 500.00 ... Ref No 1234567890
    '/Credited(?:\s+INR|\s+Rs\.?)\s*(\d+(?:\.\d{1,2})?).*Ref\s*(?:No)?\s*[:\-\s]*(\d{10,})/i',

    // Pattern 2: Rs. 500.00 credited ... UTR 1234567890
    '/(?:Rs\.?|INR)\s*(\d+(?:\.\d{1,2})?)\s*credited.*UTR\s*[:\-\s]*(\d{10,})/i',

    // Pattern 3: Received Rs. 500 ... UPI Ref 1234567890
    '/Received\s*(?:Rs\.?|INR)\s*(\d+(?:\.\d{1,2})?).*UPI\s*Ref\s*[:\-\s]*(\d{10,})/i',

    // Simple backup: look for 12 digit number (UTR) and an amount
    // '/(\d{12})/' 
];

$utr_found = null;
$amount_found = null;

foreach ($patterns as $pattern) {
    if (preg_match($pattern, $message, $matches)) {
        // usually matches[1] is amount, matches[2] is UTR (based on above patterns)
        // verify which one looks like amount (smaller length) vs UTR (12 digits usually)

        $val1 = $matches[1];
        $val2 = $matches[2];

        if (strlen($val2) > 9) {
            $utr_found = $val2;
            $amount_found = $val1;
        } else {
            $utr_found = $val1;
            $amount_found = $val2;
        }
        break;
    }
}

// If regex failed, we can try a looser search for a 12-digit number (common for UPI)
if (!$utr_found) {
    if (preg_match('/(\d{12})/', $message, $m)) {
        $utr_found = $m[1];
    }
}

if ($utr_found) {
    // Check if this UTR exists in Pending orders
    // We check strict match of UTR, and optionally amount match (recommended)

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE status = 'Pending' AND (transaction_id = ? OR transaction_id IS NULL)");
    // Note: User might not have entered UTR yet if they paid first. 
    // OR we match user-entered UTR.

    // Strategy: 
    // 1. If we have an order with this UTR (user entered it), verify it.
    // 2. If user hasn't entered UTR yet, we can't easily link it unless we match EXACT Amount + Time (risky if multiple same orders).
    // Let's assume User enters UTR on website first, OR matches by Amount if unique pending order.

    // Let's try to find an order with this UTR first
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE transaction_id = ? AND status = 'Pending'");
    $stmt->execute([$utr_found]);
    $order = $stmt->fetch();

    if ($order) {
        // Verify Amount (allow small difference like 1.00 vs 1)
        if (abs($order['total_amount'] - $amount_found) < 1.0) {
            // MATCH FOUND!
            $update = $pdo->prepare("UPDATE orders SET status = 'Processed' WHERE id = ?");
            $update->execute([$order['id']]);
            echo "SUCCESS: Order #{$order['id']} processed for UTR $utr_found";
            exit;
        } else {
            echo "ERROR: UTR matched but Amount mismatch. Msg: $amount_found, Order: {$order['total_amount']}";
        }
    } else {
        // No order found with this UTR. 
        // Could be user hasn't entered it yet.
        // We could store this unassigned payment in a 'payments' table, but keeping it simple for now:
        echo "IGNORED: No pending order found with UTR $utr_found";
    }

} else {
    echo "IGNORED: No UTR found in message";
}
?>