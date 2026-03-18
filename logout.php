<?php
include 'includes/user_auth.php';
session_destroy();
header('Location: index');
exit;
