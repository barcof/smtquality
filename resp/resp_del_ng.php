<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$ngno		= $_REQUEST['fld_del_ngno'];
	$ngname		= $_REQUEST['fld_del_ngname'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'NG NO 		='.$ngno.'\n';
	echo 'NG Name		='.$ngname.'\n';
	echo "delete from tb_ng where ngno = '$ngno' and ngname = '$ngname'";
	*/
	
	try {
		$rs = $db->Execute("delete from tb_ng where ngno = '$ngno' and ngname = '$ngname'");
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