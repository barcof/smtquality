<?php
	include '../connection.php';
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$Ymd = gmdate("d-m-Y");
		$wkt = date('H:i:s');
	// ================= //

	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;
	$levelid = $_SESSION['iqrs_userlevel']

	//$mchname 	= isset($_REQUEST['src_mch']) ? $_REQUEST['src_mch'] : '';
	//$modelname 	= isset($_REQUEST['src_model']) ? $_REQUEST['src_model'] : '';
	//$stserial 	= isset($_REQUEST['src_stserial']) ? $_REQUEST['src_stserial'] : 0;
	//$lotno 		= isset($_REQUEST['src_lotno']) ? $_REQUEST['src_lotno'] : '';
	//$pcbname	= isset($_REQUEST['src_pcbname']) ? $_REQUEST['src_pcbname'] : '';
	//$pwbno		= isset($_REQUEST['src_pwbno']) ? $_REQUEST['src_pwbno'] : '';
	//$proc		= isset($_REQUEST['src_proc']) ? $_REQUEST['src_proc'] : '';
	$boardid	= isset($_REQUEST['src_boardid']) ? $_REQUEST['src_boardid'] : '';

	/**	run query **/

			//$rs 			= $db->Execute(" declare @totalcount as int
			//									exec DisplayInqual $start, $limit, '{$mchname}', '{$modelname}', {$stserial}, '{$lotno}', //'{$pcbname}', '{$pwbno}', '{$proc}', @totalcount=@totalcount out");
			$rs 			= $db->Execute(" declare @totalcount as int
												exec DisplayInqual_debug $start, $limit, '$boardid', '$levelid' @totalcount=@totalcount out");
			$totalcount 	= $rs->fields['22'];
			$return 		= array();
			//echo 'declare @totalcount as int
			//exec DisplayInqual '.$start.', '.$limit.', '.$boardid.', @totalcount=@totalcount out';
	//	-----***-----  //


	for ($i = 0; !$rs->EOF; $i++) {

		$return[$i]['inputid']		= trim($rs->fields['0']);
		//$return[$i]['inputid']		= $Ymd;
		$return[$i]['dateid']		= trim($rs->fields['1']);
		$return[$i]['group']		= trim($rs->fields['2']);
		$return[$i]['shift']		= trim($rs->fields['3']);
		$return[$i]['mch']			= trim($rs->fields['4']);
		$return[$i]['model_name']	= trim($rs->fields['5']);
		$return[$i]['start_serial']	= trim($rs->fields['6']);
		$return[$i]['serial_no']	= trim($rs->fields['7']);
		$return[$i]['lot_no']		= trim($rs->fields['8']);
		$return[$i]['lot_qty']		= trim($rs->fields['9']);
		$return[$i]['pcb_name']		= trim($rs->fields['10']);
		$return[$i]['pwb_no']		= trim($rs->fields['11']);
		$return[$i]['process']		= trim($rs->fields['12']);
		//$return[$i]['ai']			= trim($rs->fields['13']);
		$return[$i]['smt']			= trim($rs->fields['13']);
		$return[$i]['loc']			= trim($rs->fields['14']);
		$return[$i]['magazineno']	= trim($rs->fields['15']);
		$return[$i]['ng']			= trim($rs->fields['16']);
		$return[$i]['boardid']		= trim($rs->fields['17']);
		$return[$i]['boardke']		= trim($rs->fields['18']);
		$return[$i]['boardqty']		= (float)trim($rs->fields['19']);
		$return[$i]['pointqty']		= (float)trim($rs->fields['20']);
		$return[$i]['inputdate']	= $rs->fields['21'];
		/*$newdate					= date_create($rs->fields['20']);
		$inputdate					= date_format($newdate, "Y-m-d H:i:s");
		$return[$i]['inputdate']	= $inputdate;*/

		$rs->MoveNext();
	}


	$o = array(
		"success"=>true,
		"totalCount"=>$totalcount,
		"rows"=>$return);

	echo json_encode($o);

	$rs->Close();
	$db->Close();
	$db=null;
?>
