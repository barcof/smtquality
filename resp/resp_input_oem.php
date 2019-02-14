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

	$model = isset($_REQUEST['fld_model']) ? $_REQUEST['fld_model'] : NULL;
	$date = $_REQUEST['fld_date'];
	$group = $_REQUEST['fld_group'];
	$shift = $_REQUEST['fld_shift'];
	$board = $_REQUEST['fld_mch'];
	$st_serial = $_REQUEST['fld_stserial'];
	$lotno = $_REQUEST['fld_lotno'];
	$lotqty	= isset($_REQUEST['fld_lotqty']) ? $_REQUEST['fld_lotqty'] : NULL;
	$pcb = isset($_REQUEST['fld_pcb']) ? $_REQUEST['fld_pcb'] : NULL;
	$pwb = isset($_REQUEST['fld_pwb']) ? $_REQUEST['fld_pwb'] : NULL;
	$proc = isset($_REQUEST['fld_proc']) ? $_REQUEST['fld_proc'] : NULL;
	$prcode	= $_REQUEST['fld_prcode'];
	$location = isset($_REQUEST['fld_loc']) ? $_REQUEST['fld_loc'] : NULL;
	$magazineno	= $_REQUEST['fld_mag'];
	$ng = $_REQUEST['fld_ng'];
	$levelid = $_REQUEST['userlevel'];
	$inputstatus = isset($_REQUEST['fld_inputstatus']) ? $_REQUEST['fld_inputstatus'] : 0;
	$boardid = isset($_REQUEST['fld_boardid']) ? $_REQUEST['fld_boardid'] : NULL;
	$getdate = date('Y-m-d H:i:s');
	$scannik = $_REQUEST['fld_nik'];
	$partno = isset($_REQUEST['fld_partno']) ? $_REQUEST['fld_partno'] : NULL;
	$partaddress = isset($_REQUEST['fld_address']) ? $_REQUEST['fld_address'] : NULL;

	if ($date == '') {
		// set default date to date now
		$date = date('Y-m-d');
	} else {
		$date = $_REQUEST['fld_date'];
	}


	try {
		$rs = $db->Execute("exec InsertOEM '{$date}','{$group}','{$shift}','{$board}','{$model}','{$st_serial}',
					'','{$lotno}','{$lotqty}','{$pcb}','{$pwb}','{$proc}','{$prcode}','{$location}','{$magazineno}',
					'{$ng}','{$getdate}','{$userip}','{$levelid}',{$inputstatus},'{$boardid}','{$scannik}','{$partno}','{$partaddress}'");

		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e) {
		$var_msg = $db->ErrorNo();
	}
	// Message
	switch ($var_msg){
		case ($var_msg != 1):
			$err = $db->ErrorMsg();
      		$error = str_replace( "'", "`", $err);
      		$error_msg = str_replace( "[Microsoft][ODBC SQL Server Driver][SQL Server]", "", $error);

			echo "{ 'success': false, 'msg': '<h4 style=\"margin-top:5px;\">$error_msg</h4>' }";
		break;
		case 1:
			echo "{
				'success': true,
				'msg': 'Successfully save data'
			}";
		break;
		// case 2: 
		// 	echo "{
		// 		'success': false,
		// 		'msg' : 'exec InsertOEM $date,$group,$shift,$board,$model,$st_serial,$lotno,$lotqty,$pcb,$pwb,$proc,$prcode,$location,$magazineno,$ng,$boardke,$boardqty,$pointqty,$getdate,$userip,$levelid,$inputstatus,$boardid'
		// 	}";
	}
	$db->Close();
	$db=null;
?>
