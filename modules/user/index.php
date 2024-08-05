<?php
session_start();
include '../../config/app.php';
include '../../config/database.php';
include '../../includes/functions.php';
require "../../libs/aes.php";

if (!isset($_SESSION['user_verified'])) {
    if(!isset($_SESSION['user_id'])) {
        header("Location: ".BASE_URL."");
        exit();
    }
    header("Location: ".BASE_URL."/modules/auth/verification.php");
    exit();
}