<?php
	include '../connection.php';
	// include 'adodb/adodb.inc.php';
	// include 'adodb/adodb-exceptions.inc.php';

	$dbtrc =& ADONewConnection('odbc_mssql');
	$dsn = "Driver={SQL Server};Server=SVRDBN\JEINSQL2012TRC;Database=SMTPROS;";
	$dbtrc->Connect($dsn,'sa','JvcSql@123');
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;

	$boardid = isset($_REQUEST['boardid']) ? $_REQUEST['boardid'] : '';
	$model_name = isset($_REQUEST['model_name']) ? $_REQUEST['model_name'] : '';
	$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';
	$pcb_name = isset($_REQUEST['pcb_name']) ? $_REQUEST['pcb_name'] : '';
	$process = isset($_REQUEST['process']) ? $_REQUEST['process'] : '';

	if($boardid == '') {
		/**	run query **/
			$rs 			= $dbtrc->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model_name}' and partloc = '{$location}' and board = '{$pcb_name}'");
			$return 		= array();
		//	-----***-----  //
	} else {
		/**	run query **/
			// GET MODEL NAME, PCB NAME, LOCATION FROM TB_INQUAL (IM_QUALITY SVRDBN)
			$sql = $db->Execute("SELECT model_name,pcb_name,loc FROM tb_inqual a LEFT JOIN tb_rejection b ON a.inputid = b.inputid WHERE b.fld_result = '{$boardid}'");
				$model = $sql->fields[0];
				$pcbname = $sql->fields[1];
				$location = $sql->fields[2];
			$sql->Close();
			
			// GET PART NUMBER FROM TBLMOUNTERFIND (SMTPROS SVRDBNTRC)
			$rs 			= $dbtrc->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model}' and partloc = '{$location}' and board = '{$pcbname}'");
			$return 		= array();
		//	-----***-----  //
	}
	
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['partno'] = $rs->fields['0'];
		
		$rs->MoveNext();
	}
	
	$o = array( "success"=>true, "rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$dbtrc->Close();
	$dbtrc=null;
?>