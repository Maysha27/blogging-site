<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (empty($email)) {
    $error = "Email is required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Email is valid, continue with authentication or further processing
    // ...
    // Your code for authentication or further processing goes here
    // ...
     echo "email adress is valid";
  }
}
?>