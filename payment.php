<?php
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = getProduct($pdo, $id);

if (!$product) {
    echo '<div class="container" style="padding: 100px 20px; text-align: center;">
            <div class="hound-card" style="max-width: 500px; margin: 0 auto; padding: 40px;">
                <i data-lucide="search-x" style="width: 48px; height: 48px; color: var(--danger); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 20px;">Product not found</h2>
                <a href="index.php" class="btn-primary">
                  <span>Back to Home</span>
                </a>
            </div>
          </div>';
    include 'includes/footer.php';
    exit;
}

$currencyMap = [
    'USD' => '$',
    'INR' => '₹'
];
$symbol = $currencyMap[$product['currency'] ?? 'USD'];
?>

<?php
include_once 'includes/config.php';
$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$amount   = $product['discounted_price'];
$note     = urlencode('PremiumOTT Order #' . $order_id);
$upi_url  = "upi://pay?pa={$upi_id}&pn=" . urlencode($payee_name) . "&am={$amount}&tn={$note}&cu=INR";
?>

<style>
/* ── Payment Page ───────────────────────────── */
.pay-page { padding: 48px 0 80px; }
.pay-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    align-items: start;
    max-width: 960px;
    margin: 0 auto;
}
/* Steps */
.pay-steps { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 40px; }
.pay-step { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.pay-step-dot {
    width: 34px; height: 34px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800;
    background: var(--bg-tertiary); border: 2px solid var(--border); color: var(--text-muted);
}
.pay-step.done .pay-step-dot  { background: var(--primary); border-color: var(--primary); color: #000; }
.pay-step.curr .pay-step-dot  { background: transparent; border-color: var(--primary); color: var(--primary); }
.pay-step span { font-size: 12px; color: var(--text-muted); font-weight: 600; }
.pay-step.done span, .pay-step.curr span { color: var(--text-secondary); }
.pay-step-line { flex: 1; height: 2px; background: var(--border); min-width: 40px; max-width: 80px; margin-bottom: 20px; }
.pay-step-line.done { background: var(--primary); }
/* Cards */
.pay-card {
    background: var(--bg-primary); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden;
}
.pay-card-head {
    padding: 20px 24px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 12px;
}
.pay-card-head-icon {
    width: 36px; height: 36px; border-radius: var(--radius-sm);
    background: var(--primary-light); color: var(--primary);
    display: flex; align-items: center; justify-content: center;
}
.pay-card-body { padding: 24px; }
/* QR section */
.qr-box {
    background: #fff; border-radius: 12px;
    padding: 14px; display: inline-flex;
    align-items: center; justify-content: center;
    box-shadow: 0 2px 20px rgba(0,0,0,.5);
    margin: 0 auto;
}
.qr-box canvas, .qr-box img { display: block; }
.upi-id-copy {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--bg-tertiary); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 10px 14px; margin-top: 16px;
    font-size: 13px; font-weight: 700; letter-spacing: .3px;
}
.copy-btn {
    background: none; border: none; color: var(--primary); cursor: pointer;
    font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 4px;
}
.copy-btn:hover { opacity: .8; }
.amount-tag {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(61,254,2,.1); border: 1px solid rgba(61,254,2,.25);
    color: var(--primary); font-size: 22px; font-weight: 800;
    padding: 10px 20px; border-radius: var(--radius-md); margin-top: 16px;
}
.upi-apps { display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap; }
.upi-app-badge {
    font-size: 11px; font-weight: 700; padding: 4px 10px;
    border-radius: 20px; border: 1px solid var(--border); color: var(--text-muted);
}
/* Confirm form */
.cf-group { margin-bottom: 18px; }
.cf-group label {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px;
}
.cf-group input {
    width: 100%; background: #1a1a1a; border: 1px solid var(--border);
    border-radius: var(--radius-sm); color: var(--text-primary);
    font-size: 14px; font-family: inherit; padding: 12px 14px; outline: none;
    transition: border-color .2s;
}
.cf-group input:focus { border-color: var(--primary); }
.cf-group.highlight input {
    border-color: rgba(61,254,2,.4); background: rgba(61,254,2,.04);
    font-weight: 700; letter-spacing: 1px;
}
.cf-hint { font-size: 11px; color: var(--text-muted); margin-top: 5px; }
.cf-alert-row {
    background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.2);
    border-radius: var(--radius-sm); padding: 12px 14px;
    display: flex; gap: 10px; align-items: flex-start; margin-bottom: 18px;
    font-size: 13px; color: #F59E0B;
}
/* Summary sidebar */
.pay-summary { display: flex; flex-direction: column; gap: 16px; }
.summary-row { display: flex; justify-content: space-between; font-size: 14px; padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--text-secondary); }
.summary-row:last-child { border-bottom: none; }
.summary-row.total { font-size: 17px; font-weight: 800; color: var(--text-primary); padding-top: 14px; }
.summary-discount { color: var(--primary); }
.guarantee-chip {
    display: flex; align-items: center; gap: 10px;
    background: rgba(61,254,2,.06); border: 1px solid rgba(61,254,2,.15);
    border-radius: var(--radius-md); padding: 14px 16px;
    font-size: 13px; color: var(--text-secondary);
}
@media (max-width: 768px) {
    .pay-grid { grid-template-columns: 1fr; }
}
</style>

<div class="pay-page">
    <div class="container">

        <!-- Progress steps -->
        <div class="pay-steps">
            <div class="pay-step done">
                <div class="pay-step-dot"><i data-lucide="check" style="width:16px;height:16px;"></i></div>
                <span>Selection</span>
            </div>
            <div class="pay-step-line done"></div>
            <div class="pay-step done">
                <div class="pay-step-dot"><i data-lucide="check" style="width:16px;height:16px;"></i></div>
                <span>Details</span>
            </div>
            <div class="pay-step-line done"></div>
            <div class="pay-step curr">
                <div class="pay-step-dot">3</div>
                <span>Payment</span>
            </div>
            <div class="pay-step-line"></div>
            <div class="pay-step">
                <div class="pay-step-dot">4</div>
                <span>Access</span>
            </div>
        </div>

        <div class="pay-grid">

            <!-- ── Left col: QR + Summary ── -->
            <div style="display:flex;flex-direction:column;gap:20px;">

                <!-- QR Code Card -->
                <div class="pay-card">
                    <div class="pay-card-head">
                        <div class="pay-card-head-icon"><i data-lucide="scan-line" style="width:18px;height:18px;"></i></div>
                        <div>
                            <div style="font-weight:800;font-size:16px;">Scan & Pay via UPI</div>
                            <div style="font-size:12px;color:var(--text-muted);">GPay · PhonePe · Paytm · Any UPI app</div>
                        </div>
                    </div>
                    <div class="pay-card-body" style="text-align:center;">
                        <div class="qr-box">
                            <div id="qrcode"></div>
                        </div>
                        <div class="amount-tag">
                            <i data-lucide="indian-rupee" style="width:20px;height:20px;"></i>
                            <?php echo number_format($amount, 0); ?>
                        </div>
                        <div class="upi-id-copy">
                            <span id="upiIdText"><?php echo htmlspecialchars($upi_id); ?></span>
                            <button class="copy-btn" onclick="copyUPI()">
                                <i data-lucide="copy" style="width:13px;height:13px;"></i> Copy
                            </button>
                        </div>
                        <div class="upi-apps">
                            <span class="upi-app-badge">📱 GPay</span>
                            <span class="upi-app-badge">📱 PhonePe</span>
                            <span class="upi-app-badge">📱 Paytm</span>
                            <span class="upi-app-badge">📱 BHIM</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="pay-card">
                    <div class="pay-card-head">
                        <div class="pay-card-head-icon"><i data-lucide="receipt" style="width:18px;height:18px;"></i></div>
                        <div style="font-weight:800;font-size:16px;">Order Summary</div>
                    </div>
                    <div class="pay-card-body">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                            <div style="width:42px;height:42px;border-radius:var(--radius-sm);background:<?php echo $product['color']; ?>18;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="package" style="width:20px;height:20px;color:<?php echo $product['color']; ?>"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:14px;"><?php echo htmlspecialchars($product['name']); ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?php echo htmlspecialchars($product['license_type']); ?></div>
                            </div>
                        </div>
                        <div class="summary-row">
                            <span>Original Price</span>
                            <span><?php echo $symbol . number_format($product['original_price'], 0); ?></span>
                        </div>
                        <div class="summary-row summary-discount">
                            <span>Discount (<?php echo $product['discount_percent']; ?>% OFF)</span>
                            <span>− <?php echo $symbol . number_format($product['original_price'] - $product['discounted_price'], 0); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total Payable</span>
                            <span><?php echo $symbol . number_format($product['discounted_price'], 0); ?></span>
                        </div>
                        <div class="guarantee-chip" style="margin-top:16px;">
                            <i data-lucide="shield-check" style="width:20px;height:20px;color:var(--primary);flex-shrink:0;"></i>
                            <span>30-Day Money Back Guarantee · 100% Secure</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Right col: Confirm Payment Form ── -->
            <div>
                <div class="pay-card">
                    <div class="pay-card-head">
                        <div class="pay-card-head-icon"><i data-lucide="check-circle-2" style="width:18px;height:18px;"></i></div>
                        <div>
                            <div style="font-weight:800;font-size:16px;">Confirm Your Payment</div>
                            <div style="font-size:12px;color:var(--text-muted);">Fill in details after completing UPI payment</div>
                        </div>
                    </div>
                    <div class="pay-card-body">

                        <div class="cf-alert-row">
                            <i data-lucide="alert-triangle" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;"></i>
                            <span>Complete the UPI payment first by scanning the QR code, then fill in the details below to confirm your order.</span>
                        </div>

                        <form action="process_order.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="order_id"   value="<?php echo $order_id; ?>">

                            <div class="cf-group highlight">
                                <label><i data-lucide="hash" style="width:12px;height:12px;"></i> UTR / Transaction ID *</label>
                                <input type="text" name="transaction_id"
                                    placeholder="12-digit UTR from payment SMS or app"
                                    pattern="\d{12,}" title="Enter the 12-digit UTR number"
                                    required autocomplete="off">
                                <div class="cf-hint">Find it in your UPI app → Transaction History → UTR / Ref No.</div>
                            </div>

                            <div class="cf-group">
                                <label><i data-lucide="user" style="width:12px;height:12px;"></i> Name on UPI Account *</label>
                                <input type="text" name="upi_payer_name"
                                    placeholder="Name as shown in UPI app"
                                    required autocomplete="name">
                                <div class="cf-hint">Enter the name linked to the account you paid from.</div>
                            </div>

                            <div class="cf-group">
                                <label><i data-lucide="indian-rupee" style="width:12px;height:12px;"></i> Amount Paid (₹) *</label>
                                <input type="number" name="amount_entered"
                                    value="<?php echo $product['discounted_price']; ?>"
                                    step="1" min="1" required>
                                <div class="cf-hint">Should match the exact amount shown on the QR code.</div>
                            </div>

                            <button type="submit" class="btn-primary" style="width:100%;padding:14px;margin-top:4px;">
                                <span>Submit Payment Confirmation</span>
                                <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
                            </button>
                            <p style="text-align:center;font-size:12px;color:var(--text-muted);margin-top:12px;">
                                By submitting, you agree to our Terms &amp; 30-day money-back policy.
                            </p>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /pay-grid -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById('qrcode'), {
    text: <?php echo json_encode($upi_url); ?>,
    width: 220,
    height: 220,
    colorDark: '#000000',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.H
});
lucide.createIcons();
function copyUPI() {
    const txt = document.getElementById('upiIdText').textContent;
    navigator.clipboard.writeText(txt).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.innerHTML = '<i data-lucide="check" style="width:13px;height:13px;"></i> Copied!';
        lucide.createIcons();
        setTimeout(() => {
            btn.innerHTML = '<i data-lucide="copy" style="width:13px;height:13px;"></i> Copy';
            lucide.createIcons();
        }, 2000);
    });
}
</script>

<?php include 'includes/footer.php'; ?>