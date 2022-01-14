<?php
	$dir = scandir('../');
    if (in_array('iqrs', $dir) == FALSE) {
      // echo 'tidak ada';
      include_once '../../adodb/con_iqrs.php';
    } else {
      // echo 'ada';
      include_once '../adodb/con_iqrs.php';
    }
?>