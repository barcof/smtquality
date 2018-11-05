<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$aino		= $_REQUEST['fld_aino'];
	$ainame		= $_REQUEST['fld_ainame'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'AI NO 		='.$aino.'\n';
	echo 'AI Name		='.$ainame.'\n';
	*/
	
	try {
		$rs = $db->Execute("insert into tb_ai values ('{$aino}','{$ainame}')");
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
			break;
	}
	$db->Close();
	$db=null;
?>