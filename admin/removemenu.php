<?php
    require '../config.php';
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM menu WHERE id=$id";
        mysqli_query($conn,$query);
        $query="DELETE FROM submenu WHERE parent_menu_id=$id";
        mysqli_query($conn,$query);

        header('location: addpost.php?managemenu');
    }

?>