<?php
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = getProduct($pdo, $id);

if (!$product) {
    echo '<div class="container" style="padding:100px 20px;text-align:center;">
            <div style="max-width:480px;margin:0 auto;padding:40px;background:var(--bg-primary);border:1px solid var(--border);border-radius:var(--radius-lg);">
                <h2 style="margin-bottom:20px;">Product not found</h2>
                <a href="index.php" class="btn-primary"><span>Back to Home</span></a>
            </div>
          </div>';
    include 'includes/footer.php';
    exit;
}

include_once 'includes/config.php';
$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$amount   = $product['discounted_price'];
$note     = 'PremiumOTT-' . $order_id;
$upi_url  = "upi://pay?pa={$upi_id}&pn=" . urlencode($payee_name) . "&am={$amount}&tn=" . urlencode($note) . "&cu=INR";
// qrserver.com — same API that worked in the original version
$qr_src   = "https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=" . urlencode($upi_url) . "&ecc=H&margin=1";

$symbol = ($product['currency'] ?? 'USD') === 'INR' ? '₹' : '$';
?>

<style>
/* ─── Payment Page ──────────────────────────── */
.pay-wrap {
    min-height: 70vh;
    padding: 40px 0 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* ── Steps bar ── */
.pay-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin-bottom: 36px;
    width: 100%;
    max-width: 500px;
}
.pp-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}
.pp-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800;
    border: 2px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-muted);
}
.pp-step.done .pp-dot { background: var(--primary); border-color: var(--primary); color: #000; }
.pp-step.curr .pp-dot { background: transparent; border-color: var(--primary); color: var(--primary); }
.pp-step span { font-size: 11px; font-weight: 600; color: var(--text-muted); white-space: nowrap; }
.pp-step.done span, .pp-step.curr span { color: var(--text-secondary); }
.pp-line { flex: 1; height: 2px; background: var(--border); min-width: 30px; max-width: 70px; margin-bottom: 18px; }
.pp-line.done { background: var(--primary); }

/* ── Main card ── */
.pay-card-main {
    width: 100%;
    max-width: 560px;
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
}

/* ── Product strip ── */
.pay-product-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border);
    gap: 12px;
}
.pay-product-name { font-size: 15px; font-weight: 700; color: var(--text-primary); }
.pay-product-type { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
.pay-amount-pill {
    display: flex; align-items: center; gap: 6px;
    background: rgba(61,254,2,.1);
    border: 1px solid rgba(61,254,2,.3);
    color: var(--primary);
    font-size: 20px; font-weight: 900;
    padding: 6px 16px;
    border-radius: 50px;
    flex-shrink: 0;
}

/* ── QR section ── */
.pay-qr-section {
    padding: 32px 24px 24px;
    text-align: center;
    border-bottom: 1px solid var(--border);
}
.pay-qr-label {
    font-size: 18px; font-weight: 800; color: var(--text-primary);
    margin-bottom: 6px;
}
.pay-qr-sub {
    font-size: 13px; color: var(--text-muted);
    margin-bottom: 24px;
}
.qr-frame {
    display: inline-block;
    background: #fff;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 0 0 6px rgba(61,254,2,0.12), 0 8px 32px rgba(0,0,0,0.5);
    position: relative;
}
.qr-frame img { display: block; border-radius: 6px; }
.qr-logo-overlay {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 48px; height: 48px;
    background: #fff;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.upi-id-row {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 16px;
    margin-top: 20px;
    font-size: 13px; font-weight: 700;
    letter-spacing: 0.3px;
}
.upi-copy-btn {
    background: rgba(61,254,2,0.1);
    border: 1px solid rgba(61,254,2,0.3);
    color: var(--primary);
    font-size: 12px; font-weight: 700;
    padding: 4px 10px; border-radius: 6px;
    cursor: pointer; display: flex; align-items: center; gap: 4px;
    transition: opacity .2s;
}
.upi-copy-btn:hover { opacity: .8; }
.upi-apps-row {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; margin-top: 14px; flex-wrap: wrap;
}
.upi-app-tag {
    font-size: 12px; font-weight: 600;
    padding: 4px 12px; border-radius: 20px;
    border: 1px solid var(--border);
    color: var(--text-muted);
}

/* ── How to pay steps ── */
.pay-how {
    display: flex;
    gap: 0;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-secondary);
}
.how-step {
    flex: 1;
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 0 8px; position: relative;
}
.how-step:not(:last-child)::after {
    content: '';
    position: absolute; right: 0; top: 16px;
    width: 1px; height: 24px;
    background: var(--border);
}
.how-num {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: var(--primary); color: #000;
    font-size: 13px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 6px;
}
.how-text { font-size: 11px; color: var(--text-muted); line-height: 1.4; font-weight: 500; }

/* ── Confirm form ── */
.pay-form-section { padding: 28px 24px; }
.pay-form-title {
    font-size: 16px; font-weight: 800; color: var(--text-primary);
    margin-bottom: 4px;
    display: flex; align-items: center; gap: 8px;
}
.pay-form-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 22px; }

.pf-field { margin-bottom: 16px; }
.pf-field label {
    display: block;
    font-size: 12px; font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 7px;
}
.pf-field input {
    width: 100%;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 15px; font-family: inherit;
    padding: 13px 14px;
    outline: none;
    transition: border-color .2s, background .2s;
}
.pf-field input:focus { border-color: var(--primary); background: rgba(61,254,2,.03); }
.pf-field.utr-field input {
    font-family: 'Courier New', monospace;
    font-size: 16px; font-weight: 700;
    letter-spacing: 2px;
    border-color: rgba(61,254,2,.35);
    background: rgba(61,254,2,.04);
}
.pf-hint { font-size: 11px; color: var(--text-muted); margin-top: 5px; }
.pf-submit {
    width: 100%; padding: 15px;
    margin-top: 8px;
    background: var(--primary); color: #000;
    border: none; border-radius: var(--radius-md);
    font-size: 16px; font-weight: 800;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: opacity .2s;
}
.pf-submit:hover { opacity: .88; }
.pf-note { text-align: center; font-size: 11px; color: var(--text-muted); margin-top: 10px; }

@media (max-width: 600px) {
    .pay-card-main { border-radius: 12px; margin: 0 12px; width: calc(100% - 24px); }
    .pay-product-strip { padding: 14px 16px; }
    .pay-qr-section { padding: 24px 16px 20px; }
    .pay-form-section { padding: 22px 16px; }
    .pay-how { padding: 16px 12px; }
}
</style>

<div class="pay-wrap">

    <!-- 4-step progress -->
    <div class="pay-progress">
        <div class="pp-step done">
            <div class="pp-dot"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
            <span>Selection</span>
        </div>
        <div class="pp-line done"></div>
        <div class="pp-step done">
            <div class="pp-dot"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
            <span>Details</span>
        </div>
        <div class="pp-line done"></div>
        <div class="pp-step curr">
            <div class="pp-dot">3</div>
            <span>Payment</span>
        </div>
        <div class="pp-line"></div>
        <div class="pp-step">
            <div class="pp-dot">4</div>
            <span>Access</span>
        </div>
    </div>

    <!-- Main card -->
    <div class="pay-card-main">

        <!-- Product strip -->
        <div class="pay-product-strip">
            <div>
                <div class="pay-product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="pay-product-type"><?php echo htmlspecialchars($product['license_type']); ?></div>
            </div>
            <div class="pay-amount-pill">
                <?php echo $symbol; ?><?php echo number_format((float)$amount, 0); ?>
            </div>
        </div>

        <!-- QR Code section -->
        <div class="pay-qr-section">
            <div class="pay-qr-label">Scan QR to Pay ₹<?php echo number_format((float)$amount, 0); ?></div>
            <div class="pay-qr-sub">Open any UPI app → Scan QR → Pay → Then fill details below</div>
            <div class="qr-frame">
                <img src="<?php echo htmlspecialchars($qr_src); ?>"
                     alt="UPI QR Code"
                     width="280" height="280"
                     style="border-radius:6px;display:block;"
                     onerror="this.outerHTML='<div style=&quot;width:280px;height:280px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#999;font-size:13px;gap:10px;&quot;><span style=&quot;font-size:40px;&quot;>⚠️</span><span style=&quot;text-align:center;&quot;>QR unavailable<br>Use UPI ID below</span></div>'">
            </div>

            <div class="upi-apps-row">
                <span class="upi-app-tag">📱 GPay</span>
                <span class="upi-app-tag">📱 PhonePe</span>
                <span class="upi-app-tag">📱 Paytm</span>
                <span class="upi-app-tag">📱 BHIM</span>
            </div>
        </div>

        <!-- How to pay steps -->
        <div class="pay-how">
            <div class="how-step">
                <div class="how-num">1</div>
                <div class="how-text">Open any<br>UPI app</div>
            </div>
            <div class="how-step">
                <div class="how-num">2</div>
                <div class="how-text">Scan QR &amp;<br>pay ₹<?php echo number_format((float)$amount, 0); ?></div>
            </div>
            <div class="how-step">
                <div class="how-num">3</div>
                <div class="how-text">Note UTR<br>number</div>
            </div>
            <div class="how-step">
                <div class="how-num">4</div>
                <div class="how-text">Fill form<br>below</div>
            </div>
        </div>

        <!-- Confirmation form -->
        <div class="pay-form-section">
            <div class="pay-form-title">
                <i data-lucide="check-circle-2" style="width:18px;height:18px;color:var(--primary);"></i>
                Confirm Payment
            </div>
            <div class="pay-form-sub">After paying, fill in details from your UPI app to confirm your order.</div>

            <form action="process_order.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                <input type="hidden" name="order_id"   value="<?php echo $order_id; ?>">

                <div class="pf-field utr-field">
                    <label>UTR Number *</label>
                    <input type="text" name="transaction_id"
                        placeholder="12-digit UTR e.g. 425612345678"
                        pattern="\d{12,}" title="Enter the 12-digit UTR number"
                        required autocomplete="off">
                    <div class="pf-hint">UPI app → Transaction History → UTR No.</div>
                </div>

                <div class="pf-field">
                    <label>Name on UPI Account *</label>
                    <input type="text" name="upi_payer_name"
                        placeholder="Name as shown in your UPI app"
                        required autocomplete="name">
                </div>

                <button type="submit" class="pf-submit">
                    <i data-lucide="shield-check" style="width:18px;height:18px;"></i>
                    <span>Confirm &amp; Submit</span>
                </button>
                <p class="pf-note">Secure · 256-bit encrypted · 30-day money-back guarantee</p>
            </form>
        </div>

    </div><!-- /pay-card-main -->
</div>

<script>
lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>
