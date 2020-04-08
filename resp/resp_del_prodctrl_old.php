<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	$inputid		= $_REQUEST['fld_del_inputid'];
	$model_name		= $_REQUEST['fld_del_model'];
	
	try {
		$get_rejectid = $db->Execute("select file_name from tb_rejection where inputid = '$inputid'"); // get file_name first
		
		while (!$get_rejectid->EOF){
			
			$file = $get_rejectid->fields['0'];
			
			 
			  if(is_file($file)){
				unlink($file); // hapus file yang ada di dalam folder uploaded
			  }
			
	 
			$get_rejectid->MoveNext();
			
		}
		$get_rejectid->Close();
		
		$del_rejection 	= $db->Execute("delete from tb_rejection where inputid = '$inputid'");
		$del_quality 	= $db->Execute("delete from tb_inqual where inputid = '$inputid' and model_name = '$model_name'");
		$del_rejection->Close();
		$del_quality->Close();
		
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
				'msg': 'Successfully delete data'
			}";
	}
	$db->Close();
	$db=null;
?>