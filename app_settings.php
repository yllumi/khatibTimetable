<?php require('../bootstrapper.php');

	$filename = $_POST['filename'];
	$session->set($filename);

	$settings = array(
			'max_same_column_data' => 1,
			'min_same_column_data' => 1,
			'max_same_row_data' => 1,
			'min_same_row_data' => 1,
			'shift_row' => 1,
			'shift_column' => 1,
			'exchange_type' => 'row_only', // all, row, column, row_column
		);


	write_file(ASSETPATH.'settings.json', json_encode($settings));
?>