<?php
// removecomment.php
session_start();

include '../config.php';
include('../function.php');

$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Remove the comment
$sql = "DELETE FROM comments WHERE id = $comment_id";
$run = mysqli_query($conn, $sql);

// Redirect back to view comments after removal
header("Location: viewcomments.php?post_id=$post_id");
exit();
?>
