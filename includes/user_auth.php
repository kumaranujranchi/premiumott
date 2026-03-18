<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Return true if a user is currently logged in.
 */
function isUserLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirect to login page if the user is not logged in.
 * Saves the current URL so the user is returned after login.
 *
 * @param string $redirect  URL to go to after successful login (default: current URL).
 */
function requireUserLogin(string $redirect = ''): void
{
    if (!isUserLoggedIn()) {
        if ($redirect === '') {
            $redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        $loginUrl = '/login.php?redirect=' . urlencode($redirect);
        header('Location: ' . $loginUrl);
        exit;
    }
}
