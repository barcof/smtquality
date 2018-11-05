<?php 
	include 'connection.php';
	session_start();
?>
<!doctype html>
<html>
	<head>
		<title>SMT Quality Report</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/icon-style.css">
		<link rel="shortcut icon" href="img/iqrs.png">
		<script type="text/javascript" src="../extjs-4.2.2/ext-all.js"></script>
		<link rel="stylesheet" type="text/css" href="../extjs-4.2.2/resources/css/ext-all-gray.css" />
	</head>
	<body>
		<?php 
			include 'header.php';
			include 'menu.php' ;
		?>
		<section>
			<div class="wrapper">
				<?php if(!empty($_REQUEST['page'])){
					$page_dir = 'page';
					$thispages = scandir($page_dir);
					unset($thispages[0], $thispages[1]);

					$page = $_REQUEST['page'];
					if(in_array($page.'.php', $thispages)){
					 include_once('page/'.$page.'.php');
					} else {
							//echo 'Page not found! :(';
					}
				}?>
			</div>
		</section>
	</body>
</html>