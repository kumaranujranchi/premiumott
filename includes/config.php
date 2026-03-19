<?php
// Configuration File

// 1. Payment Settings
$upi_id    = "abhisheksha2828@okhdfcbank";
$payee_name = "Premium OTT Store";

// 2. Security
// Secret key for Webhook (keep this same as in your SMS App URL)
$webhook_secret = "MY_SECURE_KEY";

// 3. USD → INR Exchange Rate
// Fallback rate used when the live API is unavailable. Update as needed.
$usd_inr_rate_fallback = 84;

/**
 * Returns the current USD → INR exchange rate.
 * Fetches a live rate from frankfurter.dev (free, no API key).
 * Result is cached for 6 hours in the system temp directory.
 * Falls back to $fallback on any network / parse failure.
 */
function getUsdToInrRate(float $fallback = 84): float {
    $cacheFile = sys_get_temp_dir() . '/premiumott_usd_inr.cache';
    $cacheTtl  = 6 * 3600; // 6 hours

    // Return cached rate if still fresh
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
        $cached = (float) file_get_contents($cacheFile);
        if ($cached > 1) return $cached;
    }

    // Fetch live rate (3-second timeout so it never slows the page)
    $ctx = stream_context_create(['http' => ['timeout' => 3, 'ignore_errors' => true]]);
    $raw = @file_get_contents('https://api.frankfurter.dev/v1/latest?from=USD&to=INR', false, $ctx);
    if ($raw) {
        $data = json_decode($raw, true);
        $rate = isset($data['rates']['INR']) ? (float) $data['rates']['INR'] : 0;
        if ($rate > 1) {
            @file_put_contents($cacheFile, (string) $rate);
            return $rate;
        }
    }

    return $fallback;
}
?>