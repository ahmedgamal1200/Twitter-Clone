<?php

session_start();

require_once('../connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname =  $_POST['firstname'];
    $lastname =   $_POST['lastname'];
    $email =      $_POST['email'];
    $phone  =     $_POST['phone'];

    $userId = $_SESSION['id'];

    $passmessages = []; // this is default value 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $currentpass = $_POST['currentpass'];
        $newpass  =   $_POST['newpass'];
        $confirmpass = $_POST['confirmpass'];


        // $confmessages = [];
        // التحقق من أن كلمة المرور الجديدة وتأكيد كلمة المرور متطابقين

        if ($newpass !== $confirmpass) {
            $_SESSION['confmessages'] = "* The new password and confirm password do not match.";
            header('location: ../profile.php');
            exit();
        } unset($_SESSION['confmessages']);


        //to get password saved in database
        $stmt = $db->prepare("SELECT password FROM users WHERE userid = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // التحقق من أن كلمة المرور الحالية المدخلة تتطابق مع المخزنة في قاعدة البيانات
        if (!password_verify($currentpass, $user['password'])) {
            $_SESSION['currentmessages'] = "* The current password is incorrect.";
            header('location: ../profile.php');
            exit();
        }unset($_SESSION['currentmessages']);

        //if all rules is true.. added the new pass in datapass

        $newpassHashed = password_hash($newpass, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password = :newpass WHERE userid = :id";
        $updateStmt = $db->prepare($query);
        $updateStmt->execute([
            ':newpass' => $newpassHashed,
            ':id' => $userId,
        ]);
        

        // رسالة النجاح
        $_SESSION['succMessage'] = "Password updated successfully!";
        header('location: ../profile.php');
        // exit();
    }



    $query = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone where userid = :id";
    $stmt = $db->prepare($query);

    $stmt->execute([
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        ':phone' => $phone,
        ':id' => $userId,
    ]);

    $_SESSION['succMessage'] = 'Profile updated successfully!';

    header('location: ../profile.php');
    exit();
};
