<?php
	include '../connection.php';
	date_default_timezone_set("Asia/Jakarta");
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;

/*	$page 		= @$_REQUEST["page"];
	$limit 		= @$_REQUEST["limit"];
	$start		= (($page*$limit)-$limit)+1;
*/	
	/**	run query **/
			$rs 			= $db->Execute(" declare @totalcount as int
												exec DisplayInqual $start, $limit, @totalcount=@totalcount out");
			$totalcount 	= $rs->fields['21'];
			$return 		= array();
			
			
	//	-----***-----  //
	
	
	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['inputid']		= trim($rs->fields['0']);
		$return[$i]['dateid']		= trim($rs->fields['1']);
		$return[$i]['group']		= trim($rs->fields['2']);
		$return[$i]['shift']		= trim($rs->fields['3']);
		$return[$i]['mch']			= trim($rs->fields['4']);
		$return[$i]['model_name']	= trim($rs->fields['5']);
		$return[$i]['start_serial']	= trim($rs->fields['6']);
		$return[$i]['lot_no']		= trim($rs->fields['7']);
		$return[$i]['lot_qty']		= trim($rs->fields['8']);
		$return[$i]['pcb_name']		= trim($rs->fields['9']);
		$return[$i]['pwb_no']		= trim($rs->fields['10']);
		$return[$i]['process']		= trim($rs->fields['11']);
		$return[$i]['ai']			= trim($rs->fields['12']);
		$return[$i]['smt']			= trim($rs->fields['13']);
		$return[$i]['loc']			= trim($rs->fields['14']);
		$return[$i]['magazineno']	= trim($rs->fields['15']);
		$return[$i]['ng']			= trim($rs->fields['16']);
		$return[$i]['boardke']		= trim($rs->fields['17']);
		$return[$i]['boardqty']		= (float)trim($rs->fields['18']);
		$return[$i]['pointqty']		= (float)trim($rs->fields['19']);
		$return[$i]['inputdate']	= $rs->fields['20'];
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