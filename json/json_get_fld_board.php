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
	$model 		= @$_REQUEST['fld_model'];
	/**	run query **/
			$q_getline = "SELECT mchname FROM tb_mcname WHERE mchno = '{$mch}'";
			$getline	= $db->Execute($q_getline);
			$line		= @$getline->fields['0'];
			$getline->Close();
			
			// query get model for line other than SMT or JAR
			$q_select = "SELECT * FROM GET_IQRSFIELD_BOARD ('{$line}', '{$fld_date}', '{$model}')";
			$rs 		= $conn->Execute($q_select);
			$return 	= array();
			
			
			
	//	-----***-----  //
	
	
	for ($i = 0; !$rs->EOF; $i++) {
		
        $return[$i]['noid'] = $i;
		$return[$i]['pwb_name']	= trim($rs->fields['0']);
		$return[$i]['pwb_no']	= trim($rs->fields['1']);
		$return[$i]['process']	= trim($rs->fields['2']);
		
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