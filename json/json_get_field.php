<?php
	include '../connection.php';
	$conn  = ADONewConnection('odbc');
	$dsn = "OCS_IM";
	$conn->Connect($dsn,'SYSDBA','masterkey');
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;
	
	$getdate	= date_create($_REQUEST['fld_date']);
	$fld_date	= date_format($getdate, "Y-m-d");
	$mch		= $_REQUEST['fld_mch'];
	/**	run query **/
	
			$getline	= $db->Execute("select mchname from tb_mcname where mchno = '{$mch}'");
			$line		= $getline->fields['0'];
			$getline->Close();
			
			
			//echo "select * from get_iqrsfield ('{$line}')";
			$rs 		= $conn->Execute("select * from get_iqrsfield ('{$line}', '{$fld_date}')");
			$return 	= array();
			
			
	//	-----***-----  //
	
	
	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['item_id']		= trim($rs->fields['0']);
		$return[$i]['model_name']	= trim($rs->fields['1']);
		$return[$i]['start_serial']	= trim($rs->fields['2']);
		$return[$i]['prod_no']		= trim($rs->fields['3']);
		$return[$i]['lot_size']		= trim($rs->fields['4']);
		$return[$i]['pcb_name']		= trim($rs->fields['5']);
		$return[$i]['pwb_no']		= trim($rs->fields['6']);
		$return[$i]['process']		= trim($rs->fields['7']);
		
		$rs->MoveNext();
	}
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	
	
	
	$rs->Close();
	$db->Close();
	$conn->Close();
	$db=null;
	$conn=null;
?>