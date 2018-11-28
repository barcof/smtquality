<?php
	include '../connection.php';

	$boardid = isset($_REQUEST['boardid']) ? $_REQUEST['boardid'] : '';
	// $model_name = isset($_REQUEST['model_name']) ? $_REQUEST['model_name'] : '';
	// $location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';
	// $pcb_name = isset($_REQUEST['pcb_name']) ? $_REQUEST['pcb_name'] : '';
	// $process = isset($_REQUEST['process']) ? $_REQUEST['process'] : '';

	// if($boardid == '') {
		/**	run query **/
			$rs 			= $db->Execute("SELECT a.inputid, a.loc, c.problemname
											FROM tb_inqual a 
											LEFT JOIN tb_rejection b ON a.inputid = b.inputid
											LEFT JOIN tb_prcode c ON a.smt = c.problemno
											WHERE b.fld_result = '{$boardid}'");
			$return 		= array();
		//	-----***-----  //
	// } else {
	// 	/**	run query **/
	// 		$sql = $db->Execute("SELECT model_name,pcb_name,loc FROM tb_inqual a LEFT JOIN tb_rejection b ON a.inputid = b.inputid WHERE b.fld_result = '{$boardid}'");
	// 			$model = $sql->fields[0];
	// 			$pcbname = $sql->fields[1];
	// 			$location = $sql->fields[2];
	// 		$sql->Close();
		
	// 		$rs 			= $dbtrc->Execute(" SELECT DISTINCT partno FROM tblMounterFind WHERE model = '{$model}' and partloc = '{$location}' and board = '{$pcbname}'");
	// 		$return 		= array();
	// 	//	-----***-----  //
	// }
	
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['inputid'] = $rs->fields['0'];
		$return[$i]['ngloc'] = $rs->fields['1'];
		$return[$i]['symptom'] = $rs->fields['2'];
		
		$rs->MoveNext();
	}
	
	$o = array( "success"=>true, "rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$db->Close();
	$db=null;
?>