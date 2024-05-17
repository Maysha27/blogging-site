<?php
session_start();

if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}
require 'function.php';
include 'config.php';
$msg = "";

if (isset($_GET['verification'])) {
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
        $query = mysqli_query($conn, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");

        if ($query) {
            $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
        }
    } else {
        header("Location: index.php");
    }
}

if (isset($_GET['reset'])) {
    $_GET['reset'] = mysqli_real_escape_string($conn, $_GET['reset']);
    include 'forgot-password.php';
    exit(); // Important to stop execution after handling the reset in forgot-password.php
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));

    $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
    $result = mysqli_query($conn, $sql);
    $remember = $_POST['remember'] ?? null;
   if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    if (empty($row['code'])) {
        $_SESSION['SESSION_EMAIL'] = $email;

        // Check for admin role
        if ($row['is_admin'] == 1) {
            // Redirect to admin panel
            $_SESSION['IS_ADMIN'] = true;
            header("Location: admin/index.php");
            exit();
        }

        // Handle "Remember Me" functionality here
        if (!empty($_POST['remember_me'])) {
            // Generate new token values
        
            $token_value = generate_token();
            $token_id = rand(100000, 999999);
        
            // Update user's token in the database
            update_token($row["id"], $token_value, $token_id);
        
            // Set cookies for the token values
            setcookie('token_value', $token_value, time() + (30 * 24 * 3600), '/');
            setcookie('token_id', $token_id, time() + (30 * 24 * 3600), '/');
        }
        header("Location: xyz.php"); 
    }
} else {
    $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
}
header("Location: xyz.php"); 

            
        } 

?>


<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Login Form</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords"
        content="Login Form" />
    <!-- //Meta tag Keywords -->

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--/Style-CSS -->
    <link rel="stylesheet" href="style2.css" type="text/css" media="all" />
    <!--//Style-CSS -->

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

    <style>

.w3l-mockup-form button {
    font-size: 18px;
    color: #fff;
    width: 100%;
    background: #061810;
    border: none;
    padding: 14px 15px;
    font-weight: 500;
    transition: .3s ease;
    -webkit-transition: .3s ease;
    -moz-transition: .3s ease;
    -ms-transition: .3s ease;
    -o-transition: .3s ease;
}
    .left_grid_info {
    background-color: transparent; /* Light gray background color */
    padding: 20px; /* Add some spacing around the profile picture */
    text-align: center;
    }
    
    /* Style for the profile picture */
    .left_grid_info img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #061810; 
    }
    
    /* Style for the content-wthree (Login form) */
    .content-wthree {
    background: #1e2420; /* White background for the login form */
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
    border-radius: 5px;
    }
    
    /* Optional: Style for the username */
    .content-wthree h2 {
    font-size: 24px;
    margin-bottom: 20px;
    }
    
    /* Optional: Style for the recent logins section */
    .recent-logins {
    background: transparent; /* Dark background for recent logins section */
    border-top: 1px solid #555; /* Darker border at the top */
    }
    
    /* Optional: Style for each recent login entry */
    .recent-login-entry {
    display: flex;
    align-items: center;
    margin: 10px 0;
    }
    
    /* Optional: Style for the user's profile picture in recent logins */
    .recent-login-entry img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
    }
    
    /* Optional: Style for the user's name in recent logins */
    .recent-login-entry .user-name {
    font-weight: 600;
    }
    /* Style for the profile picture container */
    .left_grid_info {
    background-color: transparent; /* Dark background color */
    padding: 20px; /* Add some spacing around the profile picture */
    text-align: center;
    }
    
    /* Style for the profile picture */
    .left_grid_info img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #00c16e; /* Green border for the profile picture */
    }
   </style>
        
</head>

<body>
<header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                <a href="index.php"><img src="admin/assets/img/logo.png" alt="CraftHub"></a>
                <a href="index.php"><span class="d-none d-lg-block">CraftHub</span></a>
                </div>
                
                <ul class="nav-list">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <div class="login-register">
                    <a href="index.php">Login</a>
                    <a href="register.php">Register</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <div class="recent-logins">
                              <h3>Recent Logins</h3>
                                <!-- Repeat this entry for each recent login -->
                                <div class="recent-login-entry">
                                    <img img src='images/image.jpg' alt="User's Profile Picture">
                                    
                                </div>
                                <div class="recent-login-entry">
                                    <span class="user-name">Maysha Yesmin</span>
                                </div>
                            <!-- Repeat for additional logins -->
                            </div>
                        <?php
                            if (isset($_SESSION['SESSION_EMAIL'])) {
                                $email = $_SESSION['SESSION_EMAIL'];
                                $sql = "SELECT * FROM users WHERE email='$email'";
                                $result = mysqli_query($conn, $sql);

                                if ($result && mysqli_num_rows($result) === 1) {
                                    $row = mysqli_fetch_assoc($result);
                                    $profilePicture = $row['profile_picture'];

                                    if (!empty($profilePicture)) {
                                        echo "<img src='$profilePicture' alt='Profile Picture' />";
                                    }
                                }
                            } else {
                                
                            }
                            ?>
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Login Now</h2>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" style="margin-bottom: 2px;" required>
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember_me">
                                <label for="remember">Remember me</label>
                            </div>
                            <p><a href="forgot-password.php" style="margin-bottom: 15px; display: block; text-align: right;">Forgot Password?</a></p>
                            <button name="submit" name="submit" class="btn" type="submit">Login</button>
                        </form>
                        <div class="social-icons">
                            <p>Create Account! <a href="register.php">Register</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //form -->
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
        
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>

</body>

</html>