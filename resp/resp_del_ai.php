<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$aino		= $_REQUEST['fld_del_aino'];
	$ainame		= $_REQUEST['fld_del_ainame'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'AI NO 		='.$aino.'\n';
	echo 'AI Name		='.$ainame.'\n';
	echo "delete from tb_ai where aino = '$aino' and ainame = '$ainame'";
	*/
	
	try {
		$rs = $db->Execute("delete from tb_ai where aino = '$aino' and ainame = '$ainame'");
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
				'msg': 'Successfully delete data'
			}";
	}
	$db->Close();
	$db=null;
?>