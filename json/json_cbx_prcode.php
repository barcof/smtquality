<?php
	include '../connection.php';
		
	/**	run query **/
			$rs 			= $db->Execute("select * from tb_prcode_new");
			$return 		= array();
	//	-----***-----  //
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['problemno']		= $rs->fields['0'];
		$return[$i]['problemname']		= $rs->fields['1'];
		$rs->MoveNext();
	}
	
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$db->Close();
	$db=null;
?>