<?php
	include 'adodb/adodb.inc.php';
	include 'adodb/adodb-exceptions.inc.php';
	
	
	$dbasetype = 'odbc_mssql';
    $user = 'sa';
    $pass = 'JvcSql@123';
    $dbase = 'SMTPROS';
	$server = "Driver={SQL Server};Server=SVRDBN\JEINSQL2012TRC;Database=$dbase;";

    $db = ADONewConnection($dbasetype);
    $db->Connect($server, $user, $pass);
		
	$page = @$_REQUEST["page"]-1;
	$limit = @$_REQUEST["limit"];
	$start = ($page*$limit)+1;
	
	// $getdate	= date_create($_REQUEST['fld_date']);
	// $fld_date	= date_format($getdate, "Y-m-d");
	$boardid = $_REQUEST['fld_boardid'];
	/**	run query **/
	
		$rs	= $db->Execute("traceability_smt_big24_inqual '{$boardid}'");
		$return = array();

	//	-----***-----  //
	
	
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['model_name']	= trim($rs->fields['0']);
		$return[$i]['start_serial']	= trim($rs->fields['1']);
		$return[$i]['prod_no']		= trim($rs->fields['2']);
		$return[$i]['lot_size']		= trim($rs->fields['3']);
		$return[$i]['pcb_name']		= trim($rs->fields['4']);
		$return[$i]['pwb_no']		= trim($rs->fields['5']);
		$return[$i]['process']		= trim($rs->fields['6']);
		
		$rs->MoveNext();
	}
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	
	$db->Close();
	$db=null;
?>