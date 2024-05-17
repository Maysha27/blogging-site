<?php
session_start();

include '../config.php';
include '../function.php'; // Make sure this path is correct

// Check if the user is not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: ../index.php");
    die();
}

// Check if the user is an admin
if (!isset($_SESSION['IS_ADMIN']) || $_SESSION['IS_ADMIN'] == 0) {
    header("Location: ../index.php");
    die();
}

// Check if post_id is set
if (!isset($_GET['post_id'])) {
    echo "Post ID not provided.";
    die();
}

$post_id = $_GET['post_id'];
$post = getPostById($conn, $post_id);

if (!$post) {
    echo "Post not found.";
    die();
}

// Fetch comments for the post
$comments = getCommentsByPostId($conn, $post_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content here -->
</head>

<body>

    <!-- Your existing HTML and navigation bar -->

    <main id="main" class="main">

        <!-- Display post details -->
        <section class="section">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $post['title'] ?></h5>
                            <p class="card-text"><?= $post['content'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Display comments -->
        <section class="section">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Comments</h5>
                            <ul>
                                <?php foreach ($comments as $comment) : ?>
                                    <li>
                                        <strong><?= $comment['name'] ?>:</strong>
                                        <?= $comment['comment'] ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <!-- Add Comment form -->
        <section class="section">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Comment</h5>
                            <!-- Your form to add a comment -->
                            <!-- Modify the action attribute based on your setup -->
                            <form method="post" action="addcomment.php">
                                <input type="text" name="post_id" value="<?= $post_id ?>">
                                <!-- Other comment form fields go here -->
                                <button type="submit" class="btn btn-primary" name="add_comment">Add Comment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>

</html>
