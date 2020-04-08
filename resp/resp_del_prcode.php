<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$prno		= $_REQUEST['fld_del_prno'];
	$prname		= $_REQUEST['fld_del_prname'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'Problem NO 		='.$prno.'\n';
	echo 'Problem Name		='.$prname.'\n';
	echo "delete from tb_prcode where problemno = '$prno' and problemname = '$prname'";
	*/
	
	try {
		$rs = $db->Execute("delete from tb_prcode_new where problemno = '$prno' and problemname = '$prname'");
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