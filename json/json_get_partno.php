<?php
	include '../connection.php';

	$dbtrc =& ADONewConnection('odbc_mssql');
	$dsn = "Driver={SQL Server};Server=SVRDBN\JEINSQL2012TRC;Database=SMTPROS;";
	$dbtrc->Connect($dsn,'sa','JvcSql@123');
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;

	$boardid = isset($_REQUEST['boardid']) ? $_REQUEST['boardid'] : '';
	// // PARAMETER WHEN GET PART NUMBER ON INPUT
	$model_name = isset($_REQUEST['model_name']) ? $_REQUEST['model_name'] : '';
	$loc = isset($_REQUEST['loc']) ? $_REQUEST['loc'] : '';
	$pcb_name = isset($_REQUEST['pcb_name']) ? $_REQUEST['pcb_name'] : '';
	$process = isset($_REQUEST['process']) ? $_REQUEST['process'] : '';
	$start_serial = isset($_REQUEST['start_serial']) ? $_REQUEST['start_serial'] : '';

	if($boardid == '') {
		// EXECUTE ON INPUT FORM
			$rs = $dbtrc->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model_name}' and partloc = '{$loc}' and board = '{$pcb_name}' and stserial = '{$start_serial}' ");
			$return = array();
	} else {
		// EXECUTE ON INPUT FOLLOW UP
		// GET MODEL NAME, PCB NAME, LOCATION FROM TB_INQUAL (IM_QUALITY SVRDBN)
			$sql = $db->Execute("SELECT model_name,pcb_name,loc,start_serial FROM tb_inqual a LEFT JOIN tb_rejection b ON a.inputid = b.inputid WHERE b.fld_result = '{$boardid}'");
				$model = $sql->fields[0];
				$pcbname = $sql->fields[1];
				$location = $sql->fields[2];
				$stserial = $sql->fields[3];
			$sql->Close();
			
			// GET PART NUMBER FROM TBLMOUNTERFIND (SMTPROS SVRDBNTRC)
			$rs = $dbtrc->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model}' and partloc = '{$location}' and board = '{$pcbname}' and stserial = '{$stserial}'");
			$return = array();
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