<?php
//start session
session_start();

//include database connection
include 'connection.php';

$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
	$error = "Username or Password is invalid";
	}
	else
	{
		// Define $username and $password
		$id 	= $_REQUEST['username'];
		$pass 	= $_REQUEST['password'];

		// untuk decrypt password MD5
		//$sqlselect = "select * from data_user where pass=MD5('$pass') AND userid='$id'";

		$rs = $db->Execute("select * from tb_user where username = '".$id."' and password = '".$pass."'");
		$userid 		= $rs->fields[0];
		$username 	= $rs->fields[1];
		$password 	= $rs->fields[2];
		$level  		= $rs->fields[3];
		$pic    		= $rs->fields[4];

			if (!$rs-> EOF)
			{
				/*if ($level == 2 and $signid == 1) {
					$level = 3;
					$signid = 0;
				}*/
			// Initializing Session
				$_SESSION['iqrs_userid']		= $userid;
				$_SESSION['iqrs_username']	= $username;
				$_SESSION['iqrs_password']	= $password;
				$_SESSION['iqrs_userlevel']	= $level;
				$_SESSION['iqrs_pic']				= $pic;
				//header('location: home.php'); // Redirecting To Other Page
			}
			else {
				$error = "Username or Password is invalid";
			}
		$rs->Close();
	}
}
	$db->Close(); // Closing Connection
?>
