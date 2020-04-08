<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$rejectid		= $_REQUEST['rejectid'];
	$inputid		= $_REQUEST['inputid'];
	$qtyselect		= $_REQUEST['qtyselect'];
	$qtyng			= $_REQUEST['qtyng'];
	$partno			= $_REQUEST['partno'];
	$repairedby		= $_REQUEST['repairedby'];
	$howtorepair	= $_REQUEST['howtorepair'];
	$checkedby		= $_REQUEST['checkedby'];
	$fld_result		= $_REQUEST['fld_result'];
	$fld_desc		= $_REQUEST['fld_desc'];
	$pic			= $_REQUEST['pic'];
	$reelno			= $_REQUEST['reelno'];
	$ma_serialno	= $_REQUEST['ma_serialno'];
	
	//$getdate	= date('Y-m-d H:i:s');
	
	try {
		
		$rs = $db->Execute("update tb_rejection set qtyselect = {$qtyselect}, qtyng = {$qtyng}, partno = '{$partno}', repairedby = '{$repairedby}', howtorepair = '{$howtorepair}', checkedby = '{$checkedby}', fld_result = '{$fld_result}', fld_desc = '{$fld_desc}', pic = '{$pic}', reelno = '{$reelno}', ma_serialno = '{$ma_serialno}' where inputid = '{$inputid}'");
		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e) {
		$var_msg = $db->ErrorNo();
	}
	
	// Message
	switch ($var_msg)
	{
		case $db->ErrorNo();
			$err	= $db->ErrorMsg();
			$error	= str_replace(chr(39), "", $err);
			
			echo "{
				'success': false,
				'msg': '$error'
			}";
			break;
		case 1: 
			echo "{
				'success': true,
				'msg': 'Successfully save data'
			}";
			break;
	}
	
	
	$db->Close();
	$db=null;
?>