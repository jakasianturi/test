<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;

require "../../vendor/phpmailer/phpmailer/src/SMTP.php";
require "../../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../../vendor/phpmailer/phpmailer/src/Exception.php";
require "../../libs/aes.php";
include '../../config/app.php';
include '../../config/database.php';
include '../../includes/functions.php';

try {
    
    $username = sanitizeInput($_POST['username']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $email = sanitizeInput($_POST['email']);
    $name = sanitizeInput($_POST['name']);
    $role = "user";


    $a = $_POST['password']; //kunci utk enkripsi PLAIN TEXT
    $io = substr(md5($a), 0, 16); //keyy
    $aes = new Aes($io); // pembuatan objek dari class Aes, dengan parameter kunci dekrip
    $pass_encrypt = bin2hex($aes->encrypt($a));

    
    $conn->beginTransaction();
    $stmt = $conn->prepare("SELECT `username` FROM `tbl_user` WHERE `username` = :username");
    $stmt->execute([
        'username' => $username,
    ]);
    $name_exist = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (empty($name_exist)) {
        $verification_code = rand(100000, 999999);
        

        $insertStmt = $conn->prepare("INSERT INTO `tbl_user` (`name`, `username`, `contact_number`, `email`, `password`, `verification_code`, `role`) VALUES (:name, :username, :contact_number, :email, :password, :verification_code, :role)");

        $insertStmt->bindParam(':name', $name, PDO::PARAM_STR);
        $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $insertStmt->bindParam(':contact_number', $contact_number, PDO::PARAM_INT);
        $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $insertStmt->bindParam(':password', $pass_encrypt, PDO::PARAM_STR);
        $insertStmt->bindParam(':verification_code', $verification_code, PDO::PARAM_INT);
        $insertStmt->bindParam(':role', $role, PDO::PARAM_STR);
        $insertStmt->execute();

        //Server settings

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'erlangbayu7@gmail.com';
        $mail->Password = 'hkee hclw qafm qrvs';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa');
        $mail->addAddress($email);
        $mail->addReplyTo('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: <a href="index.php">' . $verification_code . '</a>';


        // Success sent message alert
        // $mail->send();
        
        $conn->commit();

        $_SESSION['register_user_success'] = "Register Succesfully, Please check your email to verify your account";
        header("Location: ".BASE_URL."");
    } else {
        $conn->rollBack();

        $_SESSION['register_user_exists'] = "User Already Exists";
        header("Location: ".BASE_URL."");
    }
} catch (PDOException $e) {
    $conn->rollBack();
    
    $_SESSION['register_errors'] = $e->getMessage();
    header("Location: " . BASE_URL . "");
}