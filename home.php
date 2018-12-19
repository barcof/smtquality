<?php 
	include 'connection.php';
	session_start();
	
	if(!isset($_SESSION['iqrs_username'])){
	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<head>';
	echo '<link rel="stylesheet" type="text/css" href="css/style.css"/><link rel="shortcut icon" href= "img/icon.png"/>';
	echo '<link rel="shortcut icon" href="img/iqrs.png">';
	echo '<style>';
	echo 'body {
				background: #008080;
				background-size: cover;
				}
			p {font-size: 18px}
			h1,p {color: rgba(255,255,255,0.75);text-align:center;
				margin-top: 100px}';
	echo '</style>';
	echo '</head>';
	echo '<body>';
	echo '<h1> ACCESS DENIED </h1>';
	echo '<p>You are not authorized to see this page. Please <a href="index.php">login</a> !</p>';
	echo '</body>';
	echo '</html>';
	} else {
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
				<script type="text/javascript" src="../framework/extjs-4.2.2/ext-all.js"></script>
				<link rel="stylesheet" type="text/css" href="../framework/extjs-4.2.2/resources/css/ext-all-gray.css" />
			</head>
			<body>
				<?php 
					include 'header.php';
					include 'menu.php' ;
				?>
				<section>
					<div class="wrapper">
						<audio id="nopart" controls="controls" hidden="hidden"><source src="sound/PART_TIDAK_ADA.mp3" type="audio/mp3"></audio>
						<audio id="wrongpart" controls="controls" hidden="hidden"><source src="sound/Part_Tidak_Sama.mp3" type="audio/mp3"></audio>
						<div id="section">
							
						</div>
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
<?php		
	}
?>