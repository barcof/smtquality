<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$inputid	= $_REQUEST['inputid'];
	$model		= $_REQUEST['model'];
	$date		= $_REQUEST['dateid'];
	$group		= $_REQUEST['group'];
	$shift		= $_REQUEST['shift'];
	$mch		= $_REQUEST['mch'];
	$stserial	= $_REQUEST['stserial'];
	$lotno		= $_REQUEST['lotno'];
	$lotqty		= $_REQUEST['lotqty'];
	$pcb		= $_REQUEST['pcb'];
	$pwb		= $_REQUEST['pwb'];
	$proc		= $_REQUEST['process'];
	$ai			= $_REQUEST['ai'];
	$smt		= $_REQUEST['smt'];
	$location	= $_REQUEST['location'];
	$magazineno	= $_REQUEST['magazineno'];
	$ng			= $_REQUEST['ng'];
	$boardke	= $_REQUEST['boardke'];
	$boardqty	= $_REQUEST['boardqty'];
	$pointqty	= $_REQUEST['pointqty'];
	//$getdate	= date('Y-m-d H:i:s');
	
	try {
		
		$getmch			= $db->Execute("select mchno from tb_mcname where mchname = '{$mch}'");
		$mchno			= $getmch->fields['0'];
		$getmch->Close();
		/*
		$getpcb			= $db->Execute("select pcbno from tb_pcb where pcbname = '{$pcb}'");
		$pcbno			= $getpcb->fields['0'];
		$getpcb->Close();
		$getai			= $db->Execute("select aino from tb_ai where ainame = '{$ai}'");
		$aino			= $getai->fields['0'];
		$getai->Close();
		*/
		
		$getproblemno 	= $db->Execute("select problemno from tb_prcode where problemname = '{$smt}'");
		$problemno 		= $getproblemno->fields['0'];
		$getproblemno->Close();
		
		$getng			= $db->Execute("select ngno from tb_ng where ngname = '{$ng}'");
		$ngno			= $getng->fields['0'];
		$getng->Close();
		
		//echo 'Machine No 	:'.$mchno;
		//echo 'PCB No 		:'.$pcbno;
		//echo 'Problem No 	:'.$problemno;
		/*echo "update tb_inqual set dateid = '{$date}', fld_group = '{$group}', fld_shift = '{$shift}', mch_name = '{$mchno}', model_name = '{$model}',  lot_no = '{$lotno}',
								lot_qty = '{$lotqty}', pcb_name = '{$pcbno}', pwb_no = '{$pwb}', fld_proc = '{$proc}', ai = '{$aino}', smt = '{$problemno}', loc = '{$location}',
								ng = '{$ngno}', board_ke = '{$boardke}', board_ng_qty = '{$boardqty}', point_ng_qty = '{$pointqty}', svi_stat = '{$svi}', fld_output = '{$output}',
								fld_point = '{$point}', tot_point = '{$totpoint}', cleaning_board = '{$cleaning}'
				where inputid = '{$inputid}'";
		*/		
		$rs = $db->Execute("update tb_inqual set dateid = '{$date}', fld_group = '{$group}', fld_shift = '{$shift}', mch_name = '{$mchno}', model_name = '{$model}', start_serial = '{$stserial}', lot_no = '{$lotno}',lot_qty = '{$lotqty}', pcb_name = '{$pcb}', pwb_no = '{$pwb}', fld_proc = '{$proc}', smt = '{$problemno}', loc = '{$location}', magazineno = '{$magazineno}' ,ng = '{$ngno}', board_ke = '{$boardke}', board_ng_qty = '{$boardqty}', point_ng_qty = '{$pointqty}' where inputid = '{$inputid}'");
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