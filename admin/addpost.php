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
if (!isset($_SESSION['IS_ADMIN']) || $_SESSION['IS_ADMIN'] == 0) {
    // Redirect to t    he admin page
    header("Location: ../index.php");
    die();
  }

 
// Handle post creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_post'])) {
    $title = mysqli_real_escape_string($conn, $_POST['post_title']);
    $content = mysqli_real_escape_string($conn, $_POST['post_content']);

    // Assuming category_id is a fixed value for now; you may modify this as needed
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

    $sql = "INSERT INTO posts (title, content, category_id) VALUES ('$title', '$content', $category_id)";
    $run = mysqli_query($conn, $sql);
    $post_id = mysqli_insert_id($conn);

    $image_name = $_FILES['post_image']['name'];
    $img_tmp = $_FILES['post_image']['tmp_name'];
    foreach ($image_name as $index => $img) {
        if (move_uploaded_file($img_tmp[$index], '../images/' . $img)) {
            $sql = "INSERT INTO images (post_id, image) VALUES ($post_id,'$img')";
            $run = mysqli_query($conn, $sql);
        }
    }

    // Redirect to the post detail page
    header("Location: index.php?managepost");
    exit();
}
// Handle post creation
// Handle post creation
// if (isset($_POST['create_post'])) {
//     $title = mysqli_real_escape_string($conn, $_POST['post_title']);
//     $content = mysqli_real_escape_string($conn, $_POST['post_content']);

//     // Assuming category_id is a fixed value for now; you may modify this as needed
//     $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

//     $sql = "INSERT INTO posts (title, content, category_id) VALUES ('$title', '$content', $category_id)";
//     $run = mysqli_query($conn, $sql);
//     $post_id = mysqli_insert_id($conn);

//     $image_name = $_FILES['post_image']['name'];
//     $img_tmp = $_FILES['post_image']['tmp_name'];
//     foreach ($image_name as $index => $img) {
//         if (move_uploaded_file($img_tmp[$index], '../images/' . $img)) {
//             $sql = "INSERT INTO images (post_id, image) VALUES ($post_id,'$img')";
//             $run = mysqli_query($conn, $sql);
//         }
//     }
//     header('location: index.php?managepost');
//     // You may add a success message or redirect the user to the post, etc.
// }

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
<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include_once('header.php'); 
  ?>

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-house"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link " href="addpost.php">
          <i class="bi bi-house"></i>
          <span>Add Post</span>
        </a>
      </li><!-- End post Nav -->

      <li class="nav-item">
        <a class="nav-link " href="addpost.php?managepost">
          <i class="bi bi-house"></i>
          <span>Manage Post</span>
        </a>
      </li><!-- End post Nav -->
      

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-house"></i>
          <span>Manage Comments</span>
        </a>
      </li><!-- End comments Nav -->

      <li class="nav-item">
        <a class="nav-link " href="addpost.php?managecategory">
          <i class="bi bi-house"></i>
          <span>Manage Category</span>
        </a>
      </li><!-- End category Nav -->

      <li class="nav-item">
        <a class="nav-link " href="addpost.php?managemenu">
          <i class="bi bi-house"></i>
          <span>Manage Menu</span>
        </a>
      </li><!-- End menu Nav -->

    </ul>

  </aside><!-- End Sidebar-->
  
  <main id="main" class="main">
 
  <!-- start -->


<section class="section dashboard">
        <div class="row">
            <div class="col-lg-9 col-md-12">
            <?php
            if(isset($_GET['managepost'])){
                ?>
                <div class="pagetitle">
        <h1>Posts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Post</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <table class="table table-stripped table-advance table-hover">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Post Title</th>
                                <th>Post Category</th>
                                <th>Post Date</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                    $posts = getAllPost($conn);
                                    $count = 1;
                                    foreach($posts as $post) {
                            ?>            
                            <tr>
                                <td><?=$count?></td>
                                <td><?=$post['title']?></td>
                                <td><?=getCategory($conn,$post['category_id'])?></td>
                                <td><?=date('F jS, Y',strtotime($post['created_at']))?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-danger" href="removepost.php?id=<?=$post['id']?>">Remove<i class="icon_close_alt2"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-info" href="viewcomments.php?post_id=<?= $post['id'] ?>">View Comments</a>
                                        <a class="btn btn-success" href="addcomment.php?post_id=<?= $post['id'] ?>">Add Comment</a>
                                    </div>
                                </td>
                            </tr>            
                                    <?php
                                    $count++;
                                    }
                                    ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    <div>
                <?php
            }
               else if(isset($_GET['managemenu'])){
                    ?>
                <!-- MenuPage -->
    <div class="pagetitle">
        <h1>Menu</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Menu</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
   
    <!-- Button trigger modal -->
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuModal">Add Menu</button>

    <!-- Modal -->

        <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="menuModalLabel">Add New Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                <!-- menu Form -->
                        <form role="form" method="post" action="addmenu.php">
                            <div class="mb-3">
                                <label for="menuName" class="form-label">Menu Name</label>
                                <input type="text" class="form-control" id="menuName" name="menu-name" placeholder="Enter Menu Name">
                            </div>
                            <div class="mb-3">
                            <label for="menuLink" class="form-label">Menu Link</label>
                            <input type="text" class="form-control" id="menuLink" name="menu-link" placeholder="Enter Menu Link">
                            </div>
                            <button type="submit" name="addmenu" class="btn btn-primary">Add</button>
                        </form>
                    <!-- End menu Form -->
                    </div>
                </div>
            </div>
        </div>

                
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <table class="table table-stripped table-advance table-hover">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Menu</th>
                                <th>Link</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                    $menus = getMenu($conn);
                                    $count = 1;
                                    foreach($menus as $menu) {
                            ?>            
                            <tr>
                                <td><?=$count?></td>
                                <td><?=$menu['name']?></td>
                                <td><?=$menu['action']?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-danger" href="removemenu.php?id=<?=$menu['id']?>">Remove<i class="icon_close_alt2"></i></a>
                                    </div>
                                </td>
                            </tr>            
                                    <?php
                                    $count++;
                                    }
                                    ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    <div>
                <!-- start submenu-->
    <div>                            
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submenuModal">
            Add SubMenu
        </button>

        <!-- Modal -->

        <div class="modal fade" id="submenuModal" tabindex="-1" aria-labelledby="submenuModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="submenuModalLabel">Add New SubMenu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- menu Form -->
                        <form role="form" method="post" action="addmenu.php">
                            <div class="mb-3">
                                <label for="submenuName" class="form-label">Parent Menu</label>
                                <select type="select" class="form-control" id="parent_id" name="parent-id" placeholder="Enter Parent Menu Id">
                                   <?php
                                   $mlist = getAllMenu($conn);
                                   foreach($mlist as $m){
                                    ?>
                                        <option value="<?=$m['id']?>" ><?=$m['name']?></option>
                                    <?php
                                   }
                                   ?>
                                
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="submenuName" class="form-label">SubMenu Name</label>
                                <input type="text" class="form-control" id="submenuName" name="submenu-name" placeholder="Enter SubMenu Name">
                            </div>
                            <div class="mb-3">
                            <label for="menuLink" class="form-label">Menu Link</label>
                            <input type="text" class="form-control" id="submenuLink" name="submenu-link" placeholder="Enter SubMenu Link">
                            </div>
                            <button type="submit" name="addsubmenu" class="btn btn-primary">Add</button>
                        </form>
                    <!-- End submenu Form -->
                    </div>
                </div>
            </div>
        </div>

    
                
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <table class="table table-stripped table-advance table-hover">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Sub Menu</th>
                                <th>Parent Menu</th>
                                <th>Link</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $submenus = getAllSubMenu($conn);
                            $count = 1;
                            foreach($submenus as $submenu) {
                            ?>
                            <tr>
                                <td><?=$count?></td>                                
                                <td><?=$submenu['name']?></td>
                                <td><?=getMenuName($conn,$submenu['parent_menu_id'])?></td>
                                <td><?=$submenu['action']?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-danger" href="removemenu.php?id=<?=$submenu['parent_menu_id']?>">Remove<i class="icon_close_alt2"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $count++;
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>

        </div>
    </div>
                    <?php
                } else if(isset($_GET['managecategory'])) {
                ?>
                <div class="pagetitle">
        <h1>Categories</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Category</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
    Add Category
</button>

<!-- Modal -->

<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Category Form -->
                <form role="form" method="post" action="addct.php">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="category-name" placeholder="Enter Category Name">
                    </div>
                    <button type="submit" name="addct" class="btn btn-primary">Add</button>
                </form>
                <!-- End Category Form -->
            </div>
        </div>
    </div>
</div>
    
                
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                        
                            <table class="table table-stripped table-advance table-hover">
                                <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Action</th>
                                    </tr>
                                    <?php
                                    $categories = getCategories($conn);
                                    $count = 1;
                                    foreach($categories as $ct) {
                                    ?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=$ct['name']?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-danger" href="removect.php?id=<?=$ct['id']?>">Remove<i class="icon_close_alt2"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>


                    
                    <?php
                } 
    else{
                    
                    ?>
    <div class="pagetitle">
        <h1>Add Post</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Add Category</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <form method="post" action="" id="createPostForm" enctype="multipart/form-data" class="form-horizontal">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add Post</h5>
                <!-- title -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <label >Post Title</label>
                        <input type="text" class="form-control" name="post_title" required>
                    </div>
                </div>
        
                      <!-- Quill Editor Full -->
                <label >Post Content</label>
                <div class="quill-editor-full">
                
                </div>
                
                <!-- End Quill Editor Full -->

                </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label >Select Post Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <?php
                                    // Loop through categories to generate options
                                        foreach ($categories as $category) {
                                            echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                      
                        </div>
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label >Upload Photos(max-5)</label>
                                <input type="file" class="form-control" name="post_image[]" accept="image[]" accept="image/*" multiple/>
                            </div>
                      
                        </div>
                    </div>
                      
                    <button type="submit" class="btn btn-primary" name="create_post">Create Post</button>
                </form>
            <?php
                }
                ?>
            </div>
        </div>
    </section>
</main>


  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

</body>

</html>