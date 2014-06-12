<?php 
	session_start();

	require('app/bootstrapper.php');

	// call function
	if(isset($_GET['f'])){
		if(isset($_GET['j']))
			call_user_func($_GET['f'], $_GET['j']);
		else
			call_user_func($_GET['f']);
	};

	function new_file(){
		$filename = $_POST['filename'];
		$file_ext = $_POST['ext'];
		$_SESSION['filename'] = $filename;
		$_SESSION['file_ext'] = $file_ext;

		$db = read_file('db');
		write_file(ASSETPATH.'saved/'.$filename.$file_ext, $db);

		// open database
		$dbhandle = new SQLite3(ASSETPATH.'saved/'.$filename.$file_ext);
		$latest = $dbhandle->querySingle("SELECT rowid FROM nodes_version ORDER BY rowid DESC");
		$dbhandle->exec("DELETE FROM nodes_version WHERE rowid != $latest");
		$dbhandle->close();
	}

	function clone_file(){
		$filename = $_POST['filename'];
		$file_ext = $_POST['ext'];
		$_SESSION['filename'] = $filename;
		$_SESSION['file_ext'] = $file_ext;

		$db = read_file('db');
		write_file(ASSETPATH.'saved/'.$filename.$file_ext, $db);
	}

	function open_file($file = false){
		$filename = ($file)? $file : $_POST['filename'];
		$file_ext = $_POST['ext'];
		$_SESSION['filename'] = $filename;
		$_SESSION['file_ext'] = $file_ext;

		// open database
		$dbhandle = new SQLite3(ASSETPATH.'saved/'.$filename.$file_ext);
		$latest = $dbhandle->querySingle("SELECT rowid FROM nodes_version ORDER BY rowid DESC");
		$dbhandle->exec("DELETE FROM nodes_version WHERE rowid != $latest");
		$dbhandle->close();

		echo $filename.$file_ext;
	}

	function catch_file(){
		$output_dir = "app/assets/saved/";

		if(isset($_FILES["myfile"])){
			$ext = pathinfo($_FILES['myfile']['name']);
			//Filter the file types , if you want.
			if ($_FILES["myfile"]["error"] > 0){
				// error
			} else if($ext['extension'] != 'sch'){
				$_FILES['myfile']['error'] = 'File tidak valid';
			}else{
				//move the uploaded file to uploads folder;
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $_FILES["myfile"]["name"]);
				$_SESSION['filename'] = $ext['filename'];
				$_SESSION['file_ext'] = '.'.$ext['extension'];
			}

			// echo json_encode($ext);
			echo json_encode($_FILES['myfile']);
		}
	}

	function download_file(){
		require(HELPERPATH.'download_helper.php');

		// open database
		$dbhandle = new SQLite3(ASSETPATH.'saved/'.$_SESSION['filename'].$_SESSION['file_ext']);
		$latest = $dbhandle->querySingle("SELECT rowid FROM nodes_version ORDER BY rowid DESC");
		$dbhandle->exec("DELETE FROM nodes_version WHERE rowid != $latest");
		$dbhandle->close();

		$db = read_file(ASSETPATH.'saved/'.$_SESSION['filename'].$_SESSION['file_ext']);
		force_download($_SESSION['filename'].$_SESSION['file_ext'], $db);
	}

?>