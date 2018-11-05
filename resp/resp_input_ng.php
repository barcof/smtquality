<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$ngno		= $_REQUEST['fld_ngno'];
	$ngname		= $_REQUEST['fld_ngname'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'NG NO 		='.$ngno.'\n';
	echo 'NG Name		='.$ngname.'\n';
	echo "exec InsertProdctrl '{$line}','{$date}','{$model}','{$lotno}','{$lotqty}','{$board}','{$shift}','{$group}','{$getdate}'";
	*/
	
	try {
		$rs = $db->Execute("insert into tb_ng values ('$ngno','$ngname')");
		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e) {
		$var_msg = $db->ErrorNo();
	}
	// Message
	switch ($var_msg){
		case $db->ErrorNo();
			$err	= $db->ErrorMsg();
			$error	= str_replace(chr(39), "", $err);
			
			echo "{
				'success': false,
				'msg': '$error'
			}";
			break;
		case 1: 
			echo "{
				'success': true,
				'msg': 'Successfully save data'
			}";
	}
	$db->Close();
	$db=null;
?>