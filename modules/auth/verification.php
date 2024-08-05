<?php
session_start();
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']);
    $verification_code = $_POST['verification_code'];
}

if (!empty($verification_code)) {
    $stmt = $conn->prepare("SELECT `id`, `name`, `email` FROM `tbl_user` WHERE `username` = :username and `verification_code` = :verification_code");
    $stmt->execute(['username' => $user_id, 'verification_code' => $verification_code]);

    $check_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($check_user)) {
        $stmt = $conn->prepare("UPDATE `tbl_user` SET `verification_code` = NULL WHERE `username` = :username");
        $stmt->execute(['username' => $username]);

        $_SESSION['user_verified'] = true;
        header("Location: " . BASE_URL . "/modules/user/index.php");
        exit();
    } else {
        $_SESSION['verification_error'] = "Invalid verification code";
        header("Location: " . BASE_URL . "/modules/auth/login.php");
        exit();
    }
}

?>
<?php
require __DIR__ . '/../../includes/header.php';
?>
<div class="main">
    <div class="verification-container">
        <div class="verification-form" id="loginForm">
            <?php
            $message = "";
            $alert = "success";
            $show_alert = false;
            if (isset($_SESSION['verification_error'])) {
                $message = $_SESSION['verification_error'];
                $alert = "danger";
                $show_alert = true;
                unset($_SESSION['verification_error']);
            }
            ?>
            <?php if ($show_alert) { ?>
                <div class="alert alert-<?= $alert; ?> mb-3" role="alert">
                    <?= $message; ?>
                </div>
            <?php } ?>
            <h2 class="text-center">Verification OTP</h2>
            <p class="text-center">Please ask the admin for a verification code.</p>
            <form action="<?= BASE_URL; ?>/modules/auth/verification.php" method="POST">
                <input type="text" class="form-control text-center mb-3" name="username" placeholder="Username" value="<?= $username ?>">
                <input type="number" class="form-control text-center" id="verification_code" placeholder="Verification Code" name="verification_code">
                <button type="submit" class="btn btn-secondary login-btn form-control mt-4" name="verify">Verify</button>
            </form>
        </div>

    </div>

</div>
<?php
require __DIR__ . '\includes\footer.php';
?>