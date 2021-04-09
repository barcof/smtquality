<?php
	include 'adodb/adodb.inc.php';
	include 'adodb/adodb-exceptions.inc.php';
	include 'adodb/adodb-errorpear.inc.php';
	
	
	$dbasetype = 'odbc_mssql';
    $user = 'sa';
    $pass = 'JvcSql@123';
    $dbase = 'db_imquality';
	$server = "Driver={SQL Server};Server=SVRDBN\JEINSQL2012;Database=$dbase;app=SMTQUALITY_IQRS";

    $db = ADONewConnection($dbasetype);
    $db->Connect($server, $user, $pass);
?>