<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$prno		= $_REQUEST['fld_prno'];
	$prname		= $_REQUEST['fld_prname'];
	//$getdate	= date('Y-m-d H:i:s');
	
	try {
		$rs = $db->Execute("insert into tb_prcode select '{$prno}', '{$prname}'");
		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e) {
		$var_msg = $db->ErrorNo();
	}
	
	// Message
	switch ($var_msg)
	{
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