<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");

	$raw_nik = isset($_REQUEST['chk_nik']) ? $_REQUEST['chk_nik'] : '';
	$inputid = isset($_REQUEST['chk_inputid']) ? $_REQUEST['chk_inputid'] : '';
	$boardid = isset($_REQUEST['chk_boardid']) ? $_REQUEST['chk_boardid'] : '';
	$partno = isset($_REQUEST['chk_partno']) ? $_REQUEST['chk_partno'] : '';
	$len_nik = strlen($raw_nik);
	$nik = "";
	if ($len_nik == 5) { $nik = $raw_nik; } else if ($len_nik == 8) { $nik = substr($raw_nik,2,5); } else { $var_msg = 2; }

	try {
		// echo $inputid;
		$rs = $db->Execute("exec InsertValidation '{$inputid}', '{$nik}', '{$boardid}', '{$partno}' ");

		$rs->Close();

		$var_msg = 1;
	}
	catch (exception $e) {
		$var_msg = $db->ErrorNo();
	}
	// Message
	switch ($var_msg){
		case $db->ErrorNo():
			$error = $db->ErrorMsg();
			$rmquote = str_replace("'", "", $error);
			$error_msg = str_replace("[Microsoft][ODBC SQL Server Driver][SQL Server]", "", $rmquote);

			echo "{
				'success': false,
				'msg': '<h2 style=\"color:#b71c1c;line-height:normal\">$error_msg</h2>'
			}";
		break;
		case 1:
			echo "{
				'success': true,
				'msg': '<h2 style=\"color:#43a047;line-height:normal\">Successfully save data</h2>'
			}";
		break;
		case 2:
			echo "{
				'success': false,
				'msg': 'Periksa Ulang NIK, kemudian scan kembali'
			}";
		break;
	}
	$db->Close();
	$db=null;
?>
