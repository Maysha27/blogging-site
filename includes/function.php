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

function is_logged_in()
{
	if(!empty($_SESSION['SES']) && is_array($_SESSION['SES'])){

		if(!empty($_SESSION['SES']['id']))
			return true;
	}

	//check for a cookie
	$cookie = $_COOKIE['SES'] ?? null;
	if($cookie && strstr($cookie, ":")){
		$parts = explode(":", $cookie);
		$token_key = $parts[0];
		$token_value = $parts[1];

		$query = "select * from users where token_key = '$token_key' limit 1";
		$row = query($query);
		if($row)
		{
			$row = $row[0];
			if($token_value == $row['token_value'])
			{
				$_SESSION['SES'] = $row;
				return true;
			}
		}
	}

	
	return false;
}
function isAdminByEmail($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT is_admin FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['is_admin'] == 1;
    } else {
        return false;
    }
}
?>