<?php
    require '../config.php';
    if(isset($_POST['addct'])){
        $category_name=mysqli_real_escape_string($conn,$_POST['category-name']);
        $query="INSERT INTO category(name) VALUES('$category_name')";
        mysqli_query($conn,$query);
        header('location: addpost.php?managecategory');
    }

?>