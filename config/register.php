<?php
session_start();
include '../connect.php';
include '../functions.php';

// التحقق من إذا كان الطلب من نوع POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // أخذ البيانات من النموذج (الـ POST)
    $firstname = filter_var(trim($_POST['firstname']), FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_var(trim($_POST['lastname']), FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = $_POST['phone'];
    $pass = $_POST['password'];
    // $profile_image = $_POST['profile_image'];
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    $_SESSION['user'] = [
        // 'id' => $userId,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'password' => $hashedPass,
        // 'profile_image' => $profile_image

        
    ];

    $forbiddenNames = array(
        "settings", "setting", "gostalker", "chat", "messages", "init", "login", "signup", "forget-password",
        "logout", "myprofile", "search", "stalkers", "userprofile", "dashboard", "connect", "confirm-email",
        "conditions", "blocked-users", "anonymousmessages", "404", "change-email", "change-password", "home",
        "sendmessages", "notification", "votes"
    );

    // مصفوفة لتخزين الأخطاء
    $formErrors = array();

    // تحقق من صحة البيانات
    if (strlen($firstname) < 2 || strlen($firstname) > 30) {
        $formErrors[] = ' Your first name must be between 2-30 characters!';
    } elseif (empty($firstname)) {
        $formErrors[] = 'Your first name can not be empty!';
    }

    if (strlen($lastname) < 2 || strlen($lastname) > 30) {
        $formErrors[] = ' Yout last name must be between 2-30 characters!';
    } elseif (empty($lastname)) {
        $formErrors[] = 'Your lsat name can not be empty!';
    }

    if (strlen($username) < 3 || strlen($username) > 40) {
        $formErrors[] = 'Username must be between 3-40 characters!';
    } elseif (empty($username)) {
        $formErrors[] = 'Username can not be empty!';
    }

    if (strlen($pass) < 8) {
        $formErrors[] = 'Password must be at least 8 characters!';
    } elseif (empty($pass)) {
        $formErrors[] = 'Password can not be empty!';
    }

    // تحقق من الأسماء المحجوزة
    if (in_array(strtolower($username), $forbiddenNames)) {
        $formErrors[] = 'This username is forbidden, please choose another one!';
    }

    // تحقق من وجود حروف غير صالحة في الـ Username
    if (preg_match("/[^\w\-.]/", $username)) {
        $formErrors[] = 'Please use valid characters in the username!';
    }

    // تحقق من وجود البريد الإلكتروني
    if (empty($email)) {
        $formErrors[] = 'Email can not be empty!';
    }

    // تحقق من أن اسم المستخدم غير مكرر
    $checkUsername = checkItem("username", "users", $username);
    if ($checkUsername) {
        $formErrors[] = 'This username is already taken!';
    }

    // تحقق من أن البريد الإلكتروني غير مكرر
    $checkEmail = checkItem("email", "users", $email);
    if ($checkEmail) {
        $formErrors[] = 'This email is already taken!';
    }

    // إذا كانت هناك أخطاء، احفظها في الجلسة وارجع للمستخدم إلى الصفحة
    if (!empty($formErrors)) {
        $_SESSION['errors'] = $formErrors;
        header('Location: ../register.php');
        exit();
    }

    // إذا لم تكن هناك أخطاء، أضف البيانات إلى قاعدة البيانات
    $stmt = $db->prepare("INSERT INTO users(firstname, lastname, username, email, phone, password ) VALUES(:fname,  :lname, :zuser, :zemail, :zphone, :zpass)");
    $stmt->execute(array(
        'fname' => $firstname,
        'lname' => $lastname,
        'zuser' => $username,
        'zemail' => $email,
        'zphone' => $phone,
        'zpass' => $hashedPass,
        // 'zprofile_image' => $profile_image,

    ));


    // تسجيل الدخول بعد التسجيل
    $stmt = $db->prepare("SELECT userid, username, email, password FROM users WHERE username = ? AND password = ?");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // إذا تم العثور على المستخدم في قاعدة البيانات، يتم تسجيل الدخول
    if ($count > 0) {
        $_SESSION['username'] = $username; // حفظ اسم المستخدم في الجلسة
        $_SESSION['id'] = $row['userid']; // حفظ ID المستخدم في الجلسة

        // استخدام الـ Token لتسجيل الدخول لاحقًا في خدمات مثل استعادة كلمة المرور أو تغيير البريد الإلكتروني
        $cstrong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
        $stmtToken = $db->prepare("INSERT INTO login_token(token, user_id) VALUES (:ztoken, :zuser_id)");
        $stmtToken->execute(array(
            ':ztoken' => sha1($token),
            ':zuser_id' => $_SESSION['id']
        ));

        setcookie("GS", $token, time() + 60 * 60 * 24 * 7, '/');
        setcookie("GS", 1, time() + 60 * 60 * 24 * 7, '/');

        // التوجيه إلى الصفحة الرئيسية بعد التسجيل
        header('Location: ../home.php');
        exit();
    } else {
        echo 'Incorrect Username or Password!';
    }
}
?>
