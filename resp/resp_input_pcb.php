<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$pcbno		= $_REQUEST['fld_pcbno'];
	$pcbname	= $_REQUEST['fld_pcbname'];
	//$getdate	= date('Y-m-d H:i:s');
	
	
	/*
	echo 'PCB NO 		='.$pcbno.'\n';
	echo 'PCB Name		='.$pcbname.'\n';
	echo "exec InsertProdctrl '{$line}','{$date}','{$model}','{$lotno}','{$lotqty}','{$board}','{$shift}','{$group}','{$getdate}'";
	*/
	
	try {
		$rs = $db->Execute("insert into tb_pcb values ('$pcbno','$pcbname')");
		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e) {
		$var_msg = $db->ErrorNo();
	}
	// Message
	switch ($var_msg){
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
	}
	$db->Close();
	$db=null;
?>