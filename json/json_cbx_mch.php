<?php
	include '../connection.php';
		
	/**	run query **/
			$rs 			= $db->Execute("select * from tb_mcname where mchno>=35");
			$return 		= array();
	//	-----***-----  //
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['mchno']		= $rs->fields['0'];
		$return[$i]['mchname']		= $rs->fields['1'];
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