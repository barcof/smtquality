<?php
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	if (getenv('HTTP_CLIENT_IP')){
        $userip = getenv('HTTP_CLIENT_IP');
	}
    else if(getenv('HTTP_X_FORWARDED_FOR')){
        $userip = getenv('HTTP_X_FORWARDED_FOR');
	}
    else if(getenv('HTTP_X_FORWARDED')){
        $userip = getenv('HTTP_X_FORWARDED');
	}
    else if(getenv('HTTP_FORWARDED_FOR')){
        $userip = getenv('HTTP_FORWARDED_FOR');
	}
    else if(getenv('HTTP_FORWARDED')){
       $userip = getenv('HTTP_FORWARDED');
	}
    else if(getenv('REMOTE_ADDR')){
        $userip = getenv('REMOTE_ADDR');
	}
    else{
        $userip = 'UNKNOWN';
	}
	
	$inputid	= $_REQUEST['fld_inputid'];
	$partno		= $_REQUEST['fld_part'];
	$selectqty	= $_REQUEST['fld_selectqty'];
	$ngqty		= $_REQUEST['fld_repairqty'];
	$repairby	= $_REQUEST['fld_repby'];
	$howtorepair= $_REQUEST['fld_howto'];
	$checkby	= $_REQUEST['fld_checkby'];
	$result		= $_REQUEST['fld_res'];
	$desc		= $_REQUEST['fld_desc'];
	$pic		= $_REQUEST['fld_pic'];
	$file		= $_FILES['fld_photo']['name'];
	$tmpfile	= $_FILES['fld_photo']['tmp_name'];
	$dir		= $_SERVER["DOCUMENT_ROOT"]."/iqrs/uploaded/";
	$reel		= $_REQUEST['fld_reel'];
	$getdate	= date('Y-m-d H:i:s');
	$isvalid	= false;
	
	
	try {
		
		//if no file uploaded
		if($_FILES["fld_photo"]["error"] == 4) {
			
			$filename = null;
			
			$rs = $db->Execute("exec InsertRejection '{$inputid}', '{$partno}', '{$selectqty}', '{$ngqty}', '{$repairby}', '{$howtorepair}', '{$checkby}', '{$result}', '{$desc}', '{$pic}',
					'{$reel}', '{$filename}', '{$getdate}', '{$userip}'");
					
			$rs->Close();
			
			$var_msg = 1;
			
		} else {
			
			$allowed =  array('gif','png' ,'jpg', 'jpeg');
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if(in_array($ext,$allowed)) {
				
				$filename = $dir.$file;
				
				if (move_uploaded_file($tmpfile, $dir.$file)){
					
					$rs = $db->Execute("exec InsertRejection '{$inputid}', '{$partno}', '{$selectqty}', '{$ngqty}', '{$repairby}', '{$howtorepair}', '{$checkby}', '{$result}', '{$desc}', '{$pic}',
					'{$reel}', '{$filename}', '{$getdate}', '{$userip}'");
					
					$rs->Close();
					
					$var_msg = 1;
					
				} else {
					
					$var_msg = 3;
					
				}
				
			} else {
				
				$var_msg = 2;
				
			}
			
		}
		/*if(!in_array($ext,$allowed)) {
			
			$var_msg = 2;
			
		}else {
			
			$filename = $dir.$file;
			
			if (move_uploaded_file($tmpfile, $dir.$file)){
				
				$rs = $db->Execute("exec InsertRejection '{$inputid}', '{$partno}', '{$selectqty}', '{$ngqty}', '{$repairby}', '{$checkby}', '{$result}', '{$desc}', '{$pic}',
				'{$filename}', '{$getdate}', '{$userip}'");
				
				$rs->Close();
				
				$var_msg = 1;
				
			} else {
				
				$var_msg = 3;
				
			}
		}*/
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
			break;
		case 2:
			echo "{
					'success': false,
					'msg': 'Incorrect extention of file, must be (*.jpg, *.jpeg, *.png, *.gif)'
				}";
			break;
		case 3:
			echo "{
					'success': false,
					'msg': 'Failed to upload file'
				}";
			break;
	}
	$db->Close();
	$db=null;
	
	/*
	echo 'Model Number 		='.$model.'<br>';
	echo 'Date				='.$date.'<br>';
	echo 'Group				='.$group.'<br>';
	echo 'Shift				='.$shift.'<br>';
	echo 'Board				='.$board.'<br>';
	echo 'Lot Number		='.$lotno.'<br>';
	echo 'Lot Qty 			='.$lotqty.'<br>';
	echo 'Pcb 				='.$pcb.'<br>';
	echo 'Pwb 				='.$pwb.'<br>';
	echo 'Process 			='.$proc.'<br>';
	echo 'Ai				='.$ai.'<br>';
	echo 'Problem Code 		='.$prcode.'<br>';
	echo 'Location			='.$location.'<br>';
	echo 'NG				='.$ng.'<br>';
	echo 'Board ke			='.$boardke.'<br>';
	echo 'Board NG Qty 		='.$boardqty.'<br>';
	echo 'Point NG Qty 		='.$pointqty.'<br>';
	echo 'SVI Status		='.$svi.'<br>';
	echo 'Output			='.$output.'<br>';
	echo 'Point 			='.$point.'<br>';
	echo 'Total Point		='.$totpoint.'<br>';
	echo 'Cleaning Board	='.$cleanboard.'<br>';
	
	echo "exec InsertInqual '{$date}','{$group}','{$shift}','{$board}','{$model}','{$lotno}','{$lotqty}','{$pcb}','{$pwb}','{$proc}','{$ai}','{$prcode}','{$location}','{$ng}','{$boardke}','{$boardqty}','{$pointqty}','{$svi}','{$output}','{$point}','{$totpoint}','{$cleanboard}','{$getdate}','{$userip}'";
	*/
?>