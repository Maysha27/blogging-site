<?php
session_start();
include '../config.php';

include('../function.php');

// Fetch categories
$categories = getCategories($conn);

// Check if the user is not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
  header("Location: ../index.php");
  die();
}

// Check if the user is an admin
if (!isset($_SESSION['IS_ADMIN']) || $_SESSION['IS_ADMIN'] == false) {
  // Redirect to the user page
  header("Location: ../xyz.php");
  die();
}

// Query to get the total number of posts
$postCountQuery = "SELECT COUNT(*) AS totalPosts FROM posts";
$postCountResult = mysqli_query($conn, $postCountQuery);
$postCountRow = mysqli_fetch_assoc($postCountResult);
$totalPosts = $postCountRow['totalPosts'];

// Query to get the total number of categories
$categoryCountQuery = "SELECT COUNT(*) AS totalCategories FROM category";
$categoryCountResult = mysqli_query($conn, $categoryCountQuery);
$categoryCountRow = mysqli_fetch_assoc($categoryCountResult);
$totalCategories = $categoryCountRow['totalCategories'];

// Handle post creation
if (isset($_POST['create_post'])) {
    $title = mysqli_real_escape_string($conn, $_POST['post_title']);
    $content = mysqli_real_escape_string($conn, $_POST['post_content']);

    // Assuming category_id is a fixed value for now; you may modify this as needed
    foreach ($categories as $category) {
        $category_id =$category['id'];
        // echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
    }; // Change this based on your category structure

    $sql = "INSERT INTO posts (title, content, category_id) VALUES ('$title', '$content', $category_id)";
    mysqli_query($conn, $sql);
    // You may add a success message or redirect the user to the post, etc.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard-Admin Panel</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include_once('header.php'); ?>
  
  <!-- ======= Sidebar ======= -->
  <?php include_once('sidebar.php'); ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
  <div class="row row-cols-md-4">

    <!-- TotalPost Card -->
<div class="col">
    <div class="card info-card sales-card">
        <div class="card-body">
            <h5 class="card-title">Total Posts</h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cloud"></i>
                </div>
                <div class="ps-3">
                    <h6><?php echo $totalPosts; ?></h6>
                </div>
            </div>
        </div>
    </div>
</div><!-- End TotalPost -->

    <!-- TotalComment Card -->
    <div class="col">
      <div class="card info-card revenue-card">
        <div class="card-body">
          <h5 class="card-title">Total Comments</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-chat-left-dots"></i>
            </div>
            <div class="ps-3">
              <h6>3,264</h6>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End TotalComment Card -->

    <!-- TotalCategory Card -->
<div class="col">
    <div class="card info-card customers-card">
        <div class="card-body">
            <h5 class="card-title">Total Categories</h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-tags"></i>
                </div>
                <div class="ps-3">
                    <h6><?php echo $totalCategories; ?></h6>
                </div>
            </div>
        </div>
    </div>
</div><!-- End TotalCategory -->

    <!-- Stock Card -->
    <div class="col">
      <div class="card info-card customers-card">
        <div class="card-body">
          <h5 class="card-title">Stock</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-people"></i>
            </div>
            <div class="ps-3">
              <h6>1244</h6>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Stock Card -->

  </div>


</section>


  </main><!-- End #main -->

  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <!-- <script src="assets/vendor/tinymce/tinymce.min.js"></script> -->
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>

</body>

</html>