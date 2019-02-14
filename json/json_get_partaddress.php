<?php
	include '../connection.php';
	// include 'adodb/adodb.inc.php';
	// include 'adodb/adodb-exceptions.inc.php';

	$dbtrc =& ADONewConnection('odbc_mssql');
	$dsn = "Driver={SQL Server};Server=SVRDBN\JEINSQL2012TRC;Database=SMTPROS;";
	$dbtrc->Connect($dsn,'sa','JvcSql@123');

	$dboutset = ADONewConnection('odbc');
	$dsnoutset = "OCS_OUTSET";
	$dboutset->Connect($dsnoutset,'SYSDBA','masterkey');
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;

	$model_name = isset($_REQUEST['model_name']) ? $_REQUEST['model_name'] : '';
	$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';
	$pcb_name = isset($_REQUEST['pcb_name']) ? $_REQUEST['pcb_name'] : '';
	$stserial = isset($_REQUEST['stserial']) ? $_REQUEST['stserial'] : '';

	/**	run query **/
		$rs 		= $dbtrc->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model_name}' AND partloc = '{$location}' AND board = '{$pcb_name}' AND stserial = '{$stserial}' ");
		$partno 	= $rs->fields['0'];
		$getaddress = $dboutset->Execute(" SELECT loc FROM addrs WHERE partnumber = '{$partno}' ");
		$return 	= array();
	//	-----***-----  //
	
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['partno'] = $rs->fields['0'];
		$return[$i]['partaddress'] = trim($getaddress->fields['0']);
		
		$rs->MoveNext();
	}
	
	$o = array( "success"=>true, "rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$getaddress->Close();
	$dbtrc->Close();
	$dbtrc=null;
	$dboutset->Close();
	$dboutset=null;
?>