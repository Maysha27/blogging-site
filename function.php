<?php

if(!$con = mysqli_connect("localhost","root","","login"))
{
	die("Failed to connect");
}

function query($query)
{
	global $con;

	$result = mysqli_query($con, $query);
	if(!is_bool($result) && mysqli_num_rows($result) > 0)
	{
		$res = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$res[] = $row;
		}

		return $res;
	}

	return false;
}

// Function to get a post by its ID
function getPostById($conn, $post_id) {
    $post_id = mysqli_real_escape_string($conn, $post_id);

    $sql = "SELECT * FROM posts WHERE id = $post_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error retrieving post: " . mysqli_error($conn));
    }

    $post = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    return $post;
}

// Function to get comments by post ID
function getCommentsByPostId($conn, $post_id) {
    $post_id = mysqli_real_escape_string($conn, $post_id);

    $sql = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error retrieving comments: " . mysqli_error($conn));
    }

    $comments = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }

    mysqli_free_result($result);

    return $comments;
}

function getUserByEmail($email)
{
    global $con;

    $email = mysqli_real_escape_string($con, $email);
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}
function getCategory($conn,$id){
	$query ="SELECT * FROM category WHERE id=$id";
	$run= mysqli_query($conn,$query);
	$data = mysqli_fetch_assoc($run);
	return $data['name'];
}
function getCategories($conn) {
    $categories = array();

    $query = "SELECT * FROM category";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }

    return $categories;
}

function getImagesByPost($conn,$post_id){
	$query ="SELECT * FROM images WHERE post_id=$post_id";
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}

function getSubMenu($conn, $menu_id){
	$query ="SELECT * FROM submenu WHERE parent_menu_id=$menu_id";
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}

function getSubMenuNo($conn, $menu_id){
	$query ="SELECT * FROM submenu WHERE parent_menu_id=$menu_id";
	$run= mysqli_query($conn,$query);
	return mysqli_num_rows($run);
}

function getComments($conn,$post_id)
{
	$query ="SELECT * FROM comments WHERE post_id=$post_id ORDER BY id DESC";
	
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}

// 

function generate_token() {
    return bin2hex(random_bytes(16));
}

function update_token($user_id, $token_value, $token_key) {
    // Update the user's token in the database
  $query ="UPDATE users SET token_value = '$token_value', token_key = '$token_key' WHERE id = $user_id";

	return query ($query);
    
    
}

function verify_token($user_id, $token_value, $token_key) {
    // Check if the provided token matches the one stored in the database
	$run =mysqli_query ("UPDATE users SET token_value = $token_value, token_key = $token_key WHERE id = $user_id");
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND token_value = ? AND token_key = ?");
    $stmt->execute([$user_id, $token_value, $token_key]);
    return $stmt->fetchColumn();
}

function check_remember_me() {
    if (isset($_COOKIE['token_value']) && isset($_COOKIE['token_key'])) {
        $token_value = $_COOKIE['token_value'];
        $token_key = $_COOKIE['token_key'];

        // Check if the token is valid
        $user_id = verify_token($user_id, $token_value, $token_key);

        // If valid, log in the user
        if ($user_id) {
            // Log in the user using the user_id
            $_SESSION['user_id'] = $user_id;

            // You may also want to regenerate the session ID for security
            session_regenerate_id();

            // Redirect the user to the home page or wherever you want
            header('Location: index.php');
            exit();
        }
    }
}

function getMenu($conn){
	$query ="SELECT * FROM menu";
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}
function getAllSubMenu($conn){
	$query ="SELECT * FROM submenu";
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}
function getAllMenu($conn){
	$query ="SELECT * FROM 	menu";
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}
function getMenuName($conn,$id){
	$query ="SELECT * FROM menu WHERE id=$id";
	$run= mysqli_query($conn,$query);
	$data = mysqli_fetch_assoc($run);
	return $data['name'];
}
function getPostThumb($conn,$id){
	$query ="SELECT * FROM images WHERE post_id=$id";
	$run= mysqli_query($conn,$query);
	$data = mysqli_fetch_assoc($run);
	return $data['image'];
}
function getAllPost($conn){
	$query ="SELECT * FROM 	posts ORDER BY id DESC";
	$run= mysqli_query($conn,$query);
	$data = array();

	while($d=mysqli_fetch_assoc($run)){
		$data[]=$d;
	}
	return $data;
}
?>