<?php
require('../config.php');
if(isset($_POST['addcomment'])){

    $name=mysqli_real_escape_string($conn,$_POST['name']);
    $comment=mysqli_real_escape_string($conn,$_POST['comment']);
    $post_id=$_POST['post_id'];
    $query="INSERT INTO comments(comment,name,post_id) Values('$comment','$name','$post_id')";
    if(mysqli_query($conn,$query)){
        header("location:../post.php?id=$post_id");
    }else{
        echo "Comment is not added!";
    }

}
?>
