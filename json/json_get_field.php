<?php
	include '../connection.php';

	$conn  = newADOConnection('odbc');
	$dsn = "OCS_IM";
	$conn->Connect($dsn,'SYSDBA','masterkey');
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;
	
	$getdate	= date_create(@$_REQUEST['fld_date']);
	$fld_date	= date_format($getdate, "Y-m-d");
	$mch		= @$_REQUEST['fld_mch'];
	$getField	= @$_REQUEST['get_field'];
	$model 		= @$_REQUEST['fld_model'];
	/**	run query **/
			$q_getline = "SELECT mchname FROM tb_mcname WHERE mchno = '{$mch}'";
			$getline	= $db->Execute($q_getline);
			$line		= @$getline->fields['0'];
			$getline->Close();
			
			if ((substr($line,0,3) == 'SMT') || (substr($line,0,3) == 'JAR')) {
				$q_select = "SELECT * FROM GET_IQRSFIELD ('{$line}', '{$fld_date}')";
				
			} else {
				// query get model for line other than SMT or JAR
				$q_select = "SELECT * FROM GET_IQRSFIELDSUBCON ('{$line}', '{$fld_date}', '{$getField}', '{$model}')";
			}
				$rs 		= $conn->Execute($q_select);
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
		"q_getline"=>$q_getline,
		"q_select"=>$q_select,
		"rows"=>$return);
	
	echo json_encode($o);
	
	
	
	$rs->Close();
	$db->Close();
	$conn->Close();
	$db=null;
	$conn=null;
?>