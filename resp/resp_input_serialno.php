<?php
	session_start();
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	if (getenv('HTTP_CLIENT_IP')){
        $userip = getenv('HTTP_CLIENT_IP');
	}
    else if(getenv('HTTP_X_FORWARDED_FOR')){
        $userip = getenv('HTTP_X_FORWARDED_FOR');
	}
    else if(getenv('HTTP_X_FORWARDED')){
        $userip = getenv('HTTP_X_FORWARDED');
	}
    else if(getenv('HTTP_FORWARDED_FOR')){
        $userip = getenv('HTTP_FORWARDED_FOR');
	}
    else if(getenv('HTTP_FORWARDED')){
       $userip = getenv('HTTP_FORWARDED');
	}
    else if(getenv('REMOTE_ADDR')){
        $userip = getenv('REMOTE_ADDR');
	}
    else{
        $userip = 'UNKNOWN';
	}
	
	$serialno	= $_REQUEST['fld_serialno'];
	$inputid	= $_REQUEST['inputid'];
	$picupdate	= $_SESSION['iqrs_pic'];
	$getdate	= date('Y-m-d H:i:s');
	
	
	try {
		$rs = $db->Execute("update tb_inqual_new set serial_no='{$serialno}', pic_update='{$picupdate}', updatedate='{$getdate}' 
							where inputid='{$inputid}'");
		
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