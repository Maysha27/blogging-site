<?php
session_start();
include 'config.php';

include('function.php');

//pagination
if(isset($_GET['page'])){
    $page=$_GET['page'];
}
else {
    $page=1;
}

$post_per_page=5;
$result=($page-1)*$post_per_page;

// Check if the user is not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

// Check if the user is an admin
// if (isset($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN'] == true) {
//     // Redirect to the admin page
//     header("Location: admin/index.php");
//     die();
// }

// Fetch user information
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
}

// Include the getCategories function here

// Fetch categories
$categories = getCategories($conn);

// Handle post creation
if (isset($_POST['create_post'])) {
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
    header('location: index.php?managepost');
    // You may add a success message or redirect the user to the post, etc.
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Blog</title>

</head>

<body>
    <?php 
    include_once('includes/navbar.php');
    ?> 
    <div class="container m-auto mt-3 row">
        <div class="col-8">
            <?php 
            if(isset($_GET['search'])){
                $keyword= $_GET['search'];
                $postQuery="SELECT * FROM posts WHERE title LIKE '%$keyword%' ORDER BY id DESC LIMIT $result,$post_per_page";

            }
            else{
                $postQuery="SELECT * FROM posts ORDER BY id DESC LIMIT $result,$post_per_page";
            }

            $runPQ = mysqli_query($conn,$postQuery);
            while($post=mysqli_fetch_assoc($runPQ)){
            ?>
            <div class="card mb-3" style="max-width: 800px;">
                <a href="post.php?id=<?=$post['id']?>" style="text-decoration:none; color:black">
                    <div class="row g-0">
                        <div class="col-md-5"
                            style="background-image: url('images/<?=getPostThumb($conn,$post['id'])?>');background-size: cover">
                            <!-- <img src="https://images.moneycontrol.com/static-mcnews/2020/04/stock-in-the-news-770x433.jpg" alt="..."> -->
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title"><?=$post['id']?>. <?=$post['title']?></h5>
                                <p class="card-text text-truncate"><?=$post['content']?></p>
                                <p class="card-text"><small class="text-muted">Posted on
                                        <?=$post['created_at']?></small></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php
            }
            ?>
        </div>

        <div class="col-4">
            <?php include_once('includes/sidebar.php'); ?>
            <!-- Post Creation Form -->
            <form method="post" action="" id="createPostForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="post_title" class="form-label">Post Title</label>
                    <input type="text" class="form-control" id="post_title" name="post_title" required>
                </div>
                <div class="mb-3">
                    <label for="post_content" class="form-label">Post Content</label>
                    <textarea class="form-control" id="post_content" name="post_content" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <?php
                    // Loop through categories to generate options
                    foreach ($categories as $category) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="picture">Picture (optional):</label>
                    <input type="file" id="picture" name="picture" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary" name="create_post">Create Post</button>
            </form>
            <!-- Post Creation Form -->
        </div>
    </div>

    <div>
        <!-- <h4>Related Posts</h4> -->
        <?php
        if(isset($_GET['search'])){
            $keyword=$_GET['search'];
            $q="SELECT * FROM posts WHERE title LIKE '%$keyword%'";
        }else{
            $q= "SELECT * FROM posts";  
        }
        $r=mysqli_query($conn,$q);
        $total_posts= mysqli_num_rows($r);
        $total_pages= ceil($total_posts/$post_per_page);
        ?>  
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php
                if($page>1){
                    $switch="";
                }else {
                    $switch="disabled";
                }
                if($page<$total_pages){
                    $nswitch="";
                }else {
                    $nswitch="disabled";
                }
                ?>
                <li class="page-item <?=$switch?>">
                    <a class="page-link"
                        href="?<?php if(isset($_GET['search'])){echo "search=$keyword&";} ?>?page=<?=$page-1?>" tabindex="-1"
                        aria-disabled="true">Previous</a>
                </li>
                <?php
                for($opage=1;$opage<=$total_pages;$opage++){
                ?>
                <li class="page-item"><a class="page-link"
                        href="? <?php if(isset($_GET['search'])){echo "search=$keyword&";} ?>page=<?=$opage?>"><?=$opage?></a>
                </li>
                <?php
                }
                ?>

                <li class="page-item <?=$nswitch?>">
                    <a class="page-link"
                        href="? <?php if(isset($_GET['search'])){echo "search=$keyword&";} ?>page=<?=$page+1?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <?php 
    include_once('includes/footer.php');
    ?> 

    <!-- Include this script to handle the form submission -->
    <!-- Add this at the end of your <body> tag -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>

</body>

</html>
