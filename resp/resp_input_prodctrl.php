<?php
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

	$model = $_REQUEST['fld_model'];
	$date = $_REQUEST['fld_date'];
	$group = $_REQUEST['fld_group'];
	$shift = $_REQUEST['fld_shift'];
	$board = $_REQUEST['fld_mch'];
	$st_serial = $_REQUEST['fld_stserial'];
	$lotno = $_REQUEST['fld_lotno'];
	$lotqty	= $_REQUEST['fld_lotqty'];
	$pcb = $_REQUEST['fld_pcbname'];
	$pwb = $_REQUEST['fld_pwb'];
	$proc = $_REQUEST['fld_proc'];
	$prcode	= $_REQUEST['fld_prcode'];
	$location = $_REQUEST['fld_loc'];
	$magazineno	= $_REQUEST['fld_mag'];
	$ng = $_REQUEST['fld_ng'];
	$boardke = isset($_REQUEST['fld_boardke']) ? $_REQUEST['fld_boardke'] : '';
	$boardqty = isset($_REQUEST['fld_boardqty']) ? $_REQUEST['fld_boardqty'] : 0;
	$pointqty = isset($_REQUEST['fld_pointqty']) ? $_REQUEST['fld_pointqty'] : 0;
	$levelid = $_REQUEST['userlevel'];
	$inputstatus = isset($_REQUEST['fld_inputstatus']) ? $_REQUEST['fld_inputstatus'] : 0;
	$getdate = date('Y-m-d H:i:s');

	if ($date == '') {
		$date = date('Y-m-d');
	} else {
		$date = $_REQUEST['fld_date'];
	}

	return;
	
	try {
		$rs = $db->Execute("exec InsertInqual_new '{$date}','{$group}','{$shift}','{$board}','{$model}','{$st_serial}',
					'','{$lotno}','{$lotqty}','{$pcb}','{$pwb}','{$proc}','{$prcode}','{$location}','{$magazineno}',
					'{$ng}','{$boardke}','{$boardqty}','{$pointqty}','{$getdate}','{$userip}','{$levelid}',{$inputstatus}");

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
