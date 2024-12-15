<?php

include '../connect.php';

session_start();

if(isset($_COOKIE['GS'])) {
    $stmtToken = $db->prepare("DELETE FROM login_token WHERE user_id = ?");
    $stmtToken->execute(array($_SESSION['id']));
}


setcookie('GS', '1', time()-3600 , '/');

setcookie('GS_', '1', time()-3600,'/');

session_unset();

session_destroy();

header('location: ../register.php');