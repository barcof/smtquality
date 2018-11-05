<?php
	include '../connection.php';
	date_default_timezone_set("Asia/jakarta");
	
	//	declare
	$getstartdt = date_create($_REQUEST['startdt']);
	$getenddt	= date_create($_REQUEST['enddt']);
	$startdt	= date_format($getstartdt, "Y-m-d");
	$enddt		= date_format($getenddt, "Y-m-d");
	
	// select data yang akan di insert ke dalam file CSV
	$rs = $db->Execute("exec GetDownloadField '{$startdt}','{$enddt}'");
	
	//	save file
	$fname = 'Control_Sheet_'.$startdt.'_to_'.$enddt.'.csv';
	//echo $fname;
	
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=$fname");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$fp = fopen("php://output", "w");
	
	$headers = 'Date,Group,Shift,Machine Name, Model Name,Start Serial,Lot No,Lot Qty,PCB Name,PWB No,Process,Problem/Symptom,Location,Magazine No,NG Found By,Board No,Board NG Qty,Point NG Qty,Problem Input Date,Part No,Qty Select,Qty NG,Repaired By,Checked By,Serial No, Board ID,Description,PIC,Repair Input Date'."\n";
	fwrite($fp,$headers);
	
	while(!$rs->EOF)
	{
		$newdate 	= date_create($rs->fields['18']);
		
		//$ceknewdate2 = isset($newdate2)?$newdate2:'';
	   fputcsv($fp, array(	$rs->fields['0'], 
							$rs->fields['1'], 
							$rs->fields['2'], 
							$rs->fields['3'], 
							$rs->fields['4'], 
							$rs->fields['5'], 
							$rs->fields['6'],
							$rs->fields['7'], 
							$rs->fields['8'], 
							$rs->fields['9'], 
							$rs->fields['10'], 
							$rs->fields['11'], 
							$rs->fields['12'], 
							$rs->fields['13'], 
							$rs->fields['14'], 
							$rs->fields['15'], 
							$rs->fields['16'],
							$rs->fields['17'],
							$inputdate	= date_format($newdate, "Y-m-d H:i:s"),
							$rs->fields['19'], 
							$rs->fields['20'], 
							$rs->fields['21'], 
							$rs->fields['22'], 
							$rs->fields['23'], 
							$rs->fields['24'], 
							$rs->fields['25'], 
							trim($rs->fields['26']), 
							$rs->fields['27'],
							$rs->fields['28']));
	   
	   $rs->MoveNext();
	} 
	fclose($fp);
	$rs->Close();
	
	$db->Close();
	$db=null;
?>