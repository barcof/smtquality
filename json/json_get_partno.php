<?php
	// include '../connection.php';
	include 'adodb/adodb.inc.php';
	include 'adodb/adodb-exceptions.inc.php';

	$db =& ADONewConnection('odbc_mssql');
	$dsn = "Driver={SQL Server};Server=SVRDBN\JEINSQL2012TRC;Database=SMTPROS;";
	$db->Connect($dsn,'sa','JvcSql@123');
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;

	$model_name = isset($_REQUEST['model_name']) ? $_REQUEST['model_name'] : '';
	$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';
	$pcb_name = isset($_REQUEST['pcb_name']) ? $_REQUEST['pcb_name'] : '';
	$process = isset($_REQUEST['process']) ? $_REQUEST['process'] : '';

/*	date_default_timezone_set("Asia/Jakarta");
	$page 		= @$_REQUEST["page"];
	$limit 		= @$_REQUEST["limit"];
	$start		= (($page*$limit)-$limit)+1;
*/	
	/**	run query **/
			$rs 			= $db->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model_name}' and partloc = '{$location}' and board = '{$pcb_name}'");
			$return 		= array();
	//	-----***-----  //
	
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['partno'] = $rs->fields['0'];
		
		$rs->MoveNext();
	}
	
	$o = array( "success"=>true, "rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$db->Close();
	$db=null;
?>