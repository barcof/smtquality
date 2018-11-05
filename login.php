<?php
	include 'action_login.php';
	/*
	if (isset($_SESSION['userid']) && $userno === "NO00000001"){
		echo "admin";
	}else{
		echo "bukan";
	}
	*/
	/*if (isset($_SESSION['userid']) && $userno === "NO00000001"){
				//header("location:home.php?page=page_admin");
				header("location:home.php?page=page1");
	}
	else*/
	if(isset($_SESSION['iqrs_username']) && isset($_SESSION['iqrs_password'])){
		header('location:home.php?page=page1');
	}
	else {
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>SMT Quality Report</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="shortcut icon" href="img/iqrs.png">
</head>
<body>
	<div class="login">
		<h1><img src="img/logo-white.png"><p class="title">SMT Quality Report</p>
		Login</h1>

		<form class="form" method="post" action="">

		  <p class="field">
			<input type="text" name="username" placeholder="Username" required/>
			<i class="fa fa-user"></i>
		  </p>

		  <p class="field">
			<input type="password" name="password" placeholder="Password" required/>
			<i class="fa fa-lock"></i>
		  </p>

		  <p class="submit"><input type="submit" name="submit" value="Login"></p>
		  <!-- <a href="home.php?page=dashboard">VIEW ONLY</a> -->
			<p class="error"><?php echo $error; ?></p>

		</form>
	</div> <!--/ Login-->
</body>
</html>
<?php } ?>
