<?php
	include '../connection.php';
		
	$page 		= @$_REQUEST["page"]-1;
	$limit 		= @$_REQUEST["limit"];
	$start		= ($page*$limit)+1;

	$inputid	= $_REQUEST['inputid'];
	/**	run query **/
			
			$rs 			= $db->Execute(" declare @totalcount as int
												exec DisplayRejection '{$inputid}', $start, $limit, @totalcount=@totalcount out");
			$newdate		= date_create($rs->fields['13']);
			$inputdate		= date_format($newdate, "Y-m-d H:i:s");
			$totalcount 	= $rs->fields['15'];
			$return 		= array();
			
			
	//	-----***-----  //
	
	
	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['rejectid']		= trim($rs->fields['0']);
		$return[$i]['inputid']		= trim($rs->fields['1']);
		$return[$i]['partno']		= trim($rs->fields['2']);
		$return[$i]['qtyselect']	= trim($rs->fields['3']);
		$return[$i]['qtyng']		= trim($rs->fields['4']);
		$return[$i]['repairedby']	= trim($rs->fields['5']);
		$return[$i]['howtorepair']	= trim($rs->fields['6']);
		$return[$i]['checkedby']	= trim($rs->fields['7']);
		$return[$i]['fld_result']	= trim($rs->fields['8']);
		$return[$i]['fld_desc']		= trim($rs->fields['9']);
		$return[$i]['pic']			= trim($rs->fields['10']);
		$return[$i]['file_name']	= trim(substr($rs->fields['11'], 30));
		$return[$i]['reelno']		= trim($rs->fields['12']);
		$return[$i]['mdcode']		= trim($rs->fields['14']);
		$return[$i]['ma_serialno']	= trim($rs->fields['15']);
		$return[$i]['inputdate']	= $inputdate;
		
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