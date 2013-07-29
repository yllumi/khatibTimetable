<?php 
	session_start();

	require('app/bootstrapper.php');

	$filename = $_POST['filename'];
	$file_ext = $_POST['ext'];
	$_SESSION['filename'] = $filename;
	$_SESSION['file_ext'] = $file_ext;

	$data = array(
			'max_same_column_data' => 1,
			'min_same_column_data' => 1,
			'max_same_row_data' => 1,
			'min_same_row_data' => 1,
			'shift_row' => 1,
			'shift_column' => 1,
			'exchange_type' => 'row_only', // all, row, column, row_column
		);


	write_file(ASSETPATH.'saved/'.$filename.$file_ext, json_encode($data));
?>