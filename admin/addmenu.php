<?php
    require '../config.php';

    if(isset($_POST['addmenu'])){
        $menu_name = mysqli_real_escape_string($conn, $_POST['menu-name']);
        $menu_link = mysqli_real_escape_string($conn, $_POST['menu-link']); // Fix the case here

        $query = "INSERT INTO menu (name, action) VALUES ('$menu_name', '$menu_link')";
        mysqli_query($conn, $query);
        header('location: addpost.php?managemenu');
    }

    if(isset($_POST['addsubmenu'])){
        $submenu_name = mysqli_real_escape_string($conn, $_POST['submenu-name']);
        $parent_id = mysqli_real_escape_string($conn, $_POST['parent-id']); // Fix the name here
        $submenu_link = mysqli_real_escape_string($conn, $_POST['submenu-link']); // Fix the case here

        $query = "INSERT INTO submenu (name, action, parent_menu_id) VALUES ('$submenu_name', '$submenu_link', '$parent_id')";
        mysqli_query($conn, $query);
        header('location: addpost.php?managemenu');
    }
?>
