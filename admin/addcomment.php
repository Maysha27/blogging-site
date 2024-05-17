<?php
// addcomment.php
session_start();

include '../config.php';
include('../function.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

    $sql = "INSERT INTO comments (comment, name, post_id) VALUES ('$comment', '$name', $post_id)";
    $run = mysqli_query($conn, $sql);

    // Redirect back to view comments after adding
    header("Location: viewcomments.php?post_id=$post_id");
    exit();
}
?>

<!-- Your HTML form to add comments goes here -->
<!-- addcomment.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comment</title>
</head>

<body>
    <h2>Add a Comment</h2>
    
    <form action="addcomment.php" method="POST">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="comment">Your Comment:</label>
        <textarea id="comment" name="comment" rows="4" required></textarea>
        
        <input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>">
        
        <button type="submit" name="add_comment">Add Comment</button>
    </form>
</body>

</html>

