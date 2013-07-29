<?php 
	session_start();

	require('app/bootstrapper.php');

	function check_session(){	
		if(isset($_SESSION['filename'])){
			$filename = $_SESSION['filename'];
			echo json_encode(array('filename' => $filename));
		} else
			echo 0;
	}

	function sess_destroy(){
		session_destroy();
		header('location:index.php');
	}

	$function = $_GET['f'];
	call_user_func($function);
?>
