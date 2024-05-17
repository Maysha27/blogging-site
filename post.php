<?php 
session_start();
include 'config.php';
require 'function.php';
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Blog</title>
</head>
<body>
<?php 
    include_once('includes/navbar.php');
?>    
<div>
    <div class="container m-auto mt-3 row">
        <div class="col-8">
            <?php
            $post_id = $_GET['id'];
            $postQuery = "SELECT * FROM posts WHERE id=$post_id ";
            $runPQ = mysqli_query($conn, $postQuery);
            $post = mysqli_fetch_assoc($runPQ);
            ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?=$post['title']?></h5>
                    <span class="badge bg-primary ">Posted on <?=$post['created_at']?></span>
                    <span class="badge bg-danger"><?=getCategory($conn, $post['category_id'])?></span>
                    <div class="border-bottom mt-3"></div>

                    <?php
                    $post_images = getImagesByPost($conn, $post['id']);
                    ?>

                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $c = 1;
                            foreach ($post_images as $image) {
                                if ($c > 1) {
                                    $sw = "";
                                } else {
                                    $sw = "active";
                                }
                                ?>
                                <div class="carousel-item <?=$sw?>">
                                    <img src="images/<?=$image['image']?>" class="d-block w-100" alt="...">
                                </div>
                                <?php
                                $c++;
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <p class="card-text"><?=$post['content']?></p>
                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                        <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                        <a class="a2a_button_facebook"></a>
                        <a class="a2a_button_whatsapp"></a>
                        <a class="a2a_button_linkedin"></a>
                        <a class="a2a_button_google_gmail"></a>
                        <a class="a2a_button_reddit"></a>
                        <a class="a2a_button_tumblr"></a>
                        <a class="a2a_button_x"></a>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Comment on this</button>
                </div>
            </div>
            <!-- Button trigger modal -->

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Your Comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="includes/add_comment.php" method="post">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Name</label>
                                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <div id="emailHelp" class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Comment</label>
                                    <input type="text" class="form-control" name="comment" id="exampleInputPassword1">
                                </div>
                                <input type="hidden" name="post_id" value="<?=$post_id?>">
                                <button type="submit" name="addcomment" class="btn btn-primary">Add Comment</button>
                            </form>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>
            <div>
                <h4>Related Posts</h4>

                <?php
                $pquery = "SELECT * FROM posts WHERE category_id={$post['category_id']} ORDER BY id DESC";
                $prun = mysqli_query($conn, $pquery);
                while ($rpost = mysqli_fetch_assoc($prun)) {
                    if ($rpost['id'] == $post_id) {
                        continue;
                    }
                    ?>
                    <a href="post.php?id=<?=$rpost['id']?>" style="text-decoration:none;color:black">
                        <div class="card mb-3" style="max-width: 700px;">
                            <div class="row g-0">
                                <div class="col-md-5" style="background-image: url('https://images.moneycontrol.com/static-mcnews/2020/04/stock-in-the-news-770x433.jpg');background-size: cover">
                                    <!-- <img src="https://images.moneycontrol.com/static-mcnews/2020/04/stock-in-the-news-770x433.jpg" alt="..."> -->
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <h5 class="card-title"><?=$rpost['title']?></h5>
                                        <p class="card-text text-truncate"><?=$rpost['content']?></p>
                                        <p class="card-text"><small class="text-muted">Posted on<?=$rpost['created_at']?></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
        <?php 
        include_once('includes/sidebar.php');
        ?> 
    </div>
    <?php 
    include_once('includes/footer.php');
    ?> 
    <!-- AddToAny BEGIN -->
    <script async src="https://static.addtoany.com/menu/page.js"></script>
    <!-- AddToAny END -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>    
</body>
</html>
