<?php

require __DIR__ . '/../../config/app.php';

session_start();
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['role']);
unset($_SESSION['name']);

header("Location: ".BASE_URL."");
exit();