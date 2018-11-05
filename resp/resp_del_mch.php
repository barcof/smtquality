<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$mchno		= $_REQUEST['fld_del_mchno'];
	$mchname	= $_REQUEST['fld_del_mchname'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'Machine NO 		='.$mchno.'\n';
	echo 'Machine Name		='.$mchname.'\n';
	echo "delete from tb_mcname where mchno = '$mchno' and mchname = '$mchname'";
	*/
	
	try {
		$rs = $db->Execute("delete from tb_mcname where mchno = '$mchno' and mchname = '$mchname");
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