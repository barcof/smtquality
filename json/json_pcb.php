<?php
	include '../connection.php';
		
	$page 	= @$_REQUEST["page"]-1;
	$limit 	= @$_REQUEST["limit"];
	$start	= ($page*$limit)+1;

/*	date_default_timezone_set("Asia/Jakarta");
	$page 		= @$_REQUEST["page"];
	$limit 		= @$_REQUEST["limit"];
	$start		= (($page*$limit)-$limit)+1;
*/	
	/**	run query **/
			$rs 			= $db->Execute(" declare @totalcount as int
												exec DisplayPcb $start, $limit, @totalcount=@totalcount out");
			$totalcount 	= $rs->fields['2'];
			$return 		= array();
			
			
	//	-----***-----  //
	
	
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['pcbno']		= $rs->fields['0'];
		$return[$i]['pcbname']		= $rs->fields['1'];
		
		$rs->MoveNext();
	}
	
	
	$o = array(
		"success"=>true,
		"totalCount"=>$totalcount,
		"rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$db->Close();
	$db=null;
?>