<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form | Twitter</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <!-- Title section -->
        <div class="title">Registration</div>
        <div class="content">
            <!-- Registration form -->
            <form action="./config/register.php" method="post">
                <div class="user-details">
                    <!-- Input for first Name -->
                    <div class="input-box">
                        <span class="details">First Name</span>
                        <input type="text" name="firstname" id="firstname" placeholder="Enter your first name" required 
                        value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
                    </div>
                    <?php if(isset($_SESSION['errors']['firstname'])){ ?>
                        <p class="alert alert-danger">
                            <?php echo $_SESSION['errors']['firstname']; ?>
                        </p>
                    <?php } ?>

                    <!-- Input for last Name -->
                    <div class="input-box">
                        <span class="details">last Name</span>
                        <input type="text" name="lastname" id="lastname" placeholder="Enter your last name" required 
                        value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>">
                    </div>
                    <?php if(isset($_SESSION['errors']['lastname'])){ ?>
                        <p class="alert alert-danger">
                            <?php echo $_SESSION['errors']['lastname']; ?>
                        </p>
                    <?php } ?>

                    <!-- Input for Username -->
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" name="username" id="username" placeholder="Enter your username" required
                        value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                    </div>
                    <?php if(isset($_SESSION['errors']['username'])){ ?>
                        <p class="alert alert-danger">
                            <?php echo $_SESSION['errors']['username']; ?>
                        </p>
                    <?php } ?>

                    <!-- Input for Email -->
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="email" name="email" id="email" placeholder="Ex: TwitterClone@gmail.com" required
                        value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    </div>
                    <?php if(isset($_SESSION['errors']['email'])){ ?>
                        <p class="alert alert-danger">
                            <?php echo $_SESSION['errors']['email']; ?>
                        </p>
                    <?php } ?>

                    <!-- Input for Phone Number -->
                    <div class="input-box">
                        <span class="details">Phone Number</span>
                        <input type="number" name="phone" id="phone" placeholder="Enter your number" required>
                    </div>
                    
                    <!-- Input for Password -->
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>

                    <!-- Input for Confirm Password -->
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="gender-details">
                    <!-- Radio buttons for gender selection -->
                    <input type="radio" name="gender" id="dot-1">
                    <input type="radio" name="gender" id="dot-2">
                    <input type="radio" name="gender" id="dot-3">
                    <span class="gender-title">Gender</span>
                    <div class="category">
                        <!-- Label for Male -->
                        <label for="dot-1">
                            <span class="dot one"></span>
                            <span class="gender">Male</span>
                        </label>
                        <!-- Label for Female -->
                        <label for="dot-2">
                            <span class="dot two"></span>
                            <span class="gender">Female</span>
                        </label>
                        <!-- Label for Prefer not to say -->
                        <label for="dot-3">
                            <span class="dot three"></span>
                            <span class="gender">Prefer not to say</span>
                        </label>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" value="Register">
                    <div style="text-align: center;">
                        <p>Do you have an account? <a href="login.php">login</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    // بعد عرض الأخطاء تأكد من مسحها من الـ session
    unset($_SESSION['errors']);
    ?>

</body>

</html>
