<?php

session_start();

require_once './connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    // مسار حفظ الصورة
    $uploadDir = 'uploads/profile_image/';
    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileName = basename($_FILES['profile_image']['name']);
    $uploadFilePath = $uploadDir . uniqid() . '_' . $fileName; // جعل اسم الملف فريد

    // التحقق من نوع الملف المسموح
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (in_array($_FILES['profile_image']['type'], $allowedTypes)) {

        // التأكد من وجود مجلد التحميل
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // إذا لم يكن المجلد موجودًا، سيتم إنشاؤه
        }

        // رفع الصورة
        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            // تحديث مسار الصورة في قاعدة البيانات
            // $userId = $_SESSION['user']['id'];
            $db = new PDO('mysql:host=localhost;dbname=gostalker', 'root', '');
            $stmt = $db->prepare("UPDATE users SET profile_image = :profileImage");
            $stmt->execute([
                'profileImage' => $uploadFilePath,
            ]);



            // تحديث الصورة في الجلسة
            $_SESSION['user']['profile_image'] = $uploadFilePath;
        } else {
            $errors[] = "Error uploading file.";
        }
    } else {
        $errors[] = "Invalid file type. Only JPEG, PNG, and JPG are allowed.";
    }



    if(!empty($errors)){
        foreach ($errors as $error) {
            echo "<p class= alert alert-info>$error</p>";
        }
    }else{
        echo  "<p class = 'alert alert-success'>Profile picture updated successfully!</p>";

    }


    //delete image
    if(isset($_POST['delete_image'])) {
        // $_SESSION['user']['profile_image'] = 'https://th.bing.com/th/id/OIP.b-VXMyLRKFeTc9B0RNFAXwHaHa?rs=1&pid=ImgDetMain';

        $stmt = $db->prepare("UPDATE users SET profile_image = NULL WHERE userid = :userId");
        
        $stmt->execute([
            'userId' => $_SESSION['id'],
        ]);
        header("location: profile.php"); // إعادة توجيه الصفحة لتحديث الصورة
        exit;
    }
}

$userId = $_SESSION['id'];

//استرجاع بيانات المستخدم من الداتا بيز

$query = "SELECT * FROM users WHERE userid = :id";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch((PDO::FETCH_ASSOC));



// display success message to updated profile
if(isset($_SESSION['succMessage'])){
    echo "<div class='alert alert-success'>" . $_SESSION['succMessage'] . "</div>";
    unset($_SESSION['succMessage']);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Edit</title>
</head>
<link rel="stylesheet" href="./front_back/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<body>
    <?php if (!empty($message)): ?>
        <p class="alert alert-danger"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>


    <div class="container rounded bg-white mt-5">

        <div class="row">
            <div class="col-md-4 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <!-- صورة البروفايل -->
                    <div class="profile-picture-container">
                        <img style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover" 
                        src="<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? 'https://th.bing.com/th/id/OIP.b-VXMyLRKFeTc9B0RNFAXwHaHa?rs=1&pid=ImgDetMain'); ?>" width="90" id="profilePic" onclick="document.getElementById('profileImageInput').click();">
                    </div>
                    <span class="font-weight-bold"><?php echo '@' . $user['username']; ?></span>
                    <p class="text-black-50"><?php echo $user['email']; ?></p>

                    <!-- فورم لتغيير الصورة -->
                    <form action="profile.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profile_image" id="profileImageInput" style="display:none;" accept="image/*" onchange="this.form.submit();">
                    </form>

                    <!-- زر لحذف الصورة -->
                    <form action="profile.php" method="POST" style="margin-top: 10px;">
                        <button type="submit" name="delete_image" class="btn btn-outline-danger">Delete Photo</button>
                    </form>
                </div>
            </div>
        </div>

        <form action="./config/update-data.php" method="POST">
            <div class="col-md-8" style=text-align:center>
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-row align-items-center back"><i class="fa fa-long-arrow-left mr-1 mb-1"></i>
                            <a href="./home.php"><h6>Back to home</h6></a>
                        </div>
                        <h6 class="text-right">Edit Profile</h6>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" placeholder="change first name" value="<?php echo $user['firstname']; ?>">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="change last name" value="<?php echo $user['lastname']; ?>">
                        </div>
                    </div>

                    <!-- </div> -->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="username">UserName</label>
                            <input type="text" name="username" id="username" class="form-control" disabled value="<?php echo $user['username']; ?>">
                            <p style="color: red">* You can not change your username forever</p>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="change your E-mail" value="<?php echo $user['email'] ?>">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="phone">Phone</label>
                            <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone number" value="<?php echo $user['phone']; ?>">
                        </div>
                    </div>
                    <form method="POST" action="profile.php">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="currentpass">Enter Current Password</label>
                            <input type="password" class="form-control" name="currentpass" id="oldpass" placeholder="Enter your current password" >
                        </div>
                    </div>
                    </form>
                    <?php 
                    if(!empty($_SESSION['currentmessages'])){
                        echo "<p style='color: red'>" . $_SESSION['currentmessages'] . "</p>";
                        unset($_SESSION['currentmessages']);   
                    } ?>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="newpass">New Password</label>
                            <input type="password" name="newpass" id="newpass" class="form-control" placeholder="Enter New Password">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="confirmpass">Confirm New Password</label>
                            <input type="password" name="confirmpass" id="confirmpass" class="form-control" placeholder="Confirm Password">
                        </div>
                    </div>
                    <?php
                                if(!empty($_SESSION['confmessages'])){
                                echo "<p style='color: red'>" . $_SESSION['confmessages'] . "</p>";
                                unset($_SESSION['confmessages']);
                                }
                        ?>

                    <div class="mt-5 text-right"><button class="btn btn-primary profile-button" type="submit">Save Profile</button></div>
                </div>
            </div>
    </div>
    </form>

    <script src="./front_back/script.js"></script>

</body>

</html>