<?php
	include '../connection.php';
	date_default_timezone_set('Asia/Jakarta');

	$today = date("Y-m-d");

	$sdate = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : $today;
	$edate = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $today;

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	/**	run query **/
			$rs = $db->Execute("exec DisplaySummary_new '{$sdate}', '{$edate}'");
			$return = $rs->getAll();
			// $return 		= array();
	//	-----***-----  //
	// for ($i = 0; !$rs->EOF; $i++) {
	// 	$return[$i]['line']				= trim($rs->fields['25']);
	// 	$return[$i]['Missing']			= trim($rs->fields['2']);
	// 	$return[$i]['Wrong Part']		= trim($rs->fields['3']);
	// 	$return[$i]['Wrong Polarity']	= trim($rs->fields['4']);
	// 	$return[$i]['Slanting']			= trim($rs->fields['5']);
	// 	$return[$i]['Shifting']			= trim($rs->fields['6']);
	// 	$return[$i]['Short']			= trim($rs->fields['7']);
	// 	$return[$i]['Dry Joint']		= trim($rs->fields['8']);
	// 	$return[$i]['Floating']			= trim($rs->fields['9']);
	// 	$return[$i]['Over Bonding']		= trim($rs->fields['10']);
	// 	$return[$i]['Others']			= trim($rs->fields['11']);
	// 	$return[$i]['Lay Back']			= trim($rs->fields['12']);
	// 	$return[$i]['Chip Scatter']		= trim($rs->fields['13']);
	// 	$return[$i]['Poor Soldier']		= trim($rs->fields['14']);
	// 	$return[$i]['Manual IC']		= trim($rs->fields['15']);
	// 	$return[$i]['Over Solder']		= trim($rs->fields['16']);
	// 	$return[$i]['Tailing']			= trim($rs->fields['17']);
	// 	$return[$i]['Foreign Material'] = trim($rs->fields['18']);
	// 	$return[$i]['Korosi(Akame/Red Eye)'] = trim($rs->fields['19']);
	// 	$return[$i]['Slip Mounting'] 	= trim($rs->fields['20']);
	// 	$return[$i]['Part Chipping'] 	= trim($rs->fields['21']);
	// 	$rs->MoveNext();
	// }
	
	// while($r=$rs->fetchRow()) {
	// 	$return[] = $rs->fields;
	// }
	
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	
	$rs->Close();
	$db->Close();
	$db=null;
?>