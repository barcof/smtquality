<?php
session_start();

	unset($_SESSION["iqrs_username"]);
	unset($_SESSION["iqrs_password"]);
/*$_SESSION = array();
if(session_destroy()) // Destroying All Sessions
{*/
header('location: index.php'); // Redirecting To Home Page
//}
?>