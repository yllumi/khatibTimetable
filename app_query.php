<?php 

session_start();

require('app/bootstrapper.php');

// define tables name
$node_table = 'node';
$column_table = 'column';
$row_table = 'row';
$matrix_table = 'matrix';

// open database
$dbfile = ASSETPATH.'saved/'.$_SESSION['filename'].$_SESSION['file_ext'];
$dbhandle = new SQLite3($dbfile);
if (!$dbhandle) die ($error);

// call function
if(isset($_GET['f'])){
	if(isset($_GET['j']))
		call_user_func($_GET['f'], $dbhandle, $_GET['j']);
	else
		call_user_func($_GET['f'], $dbhandle);
};

$dbhandle->close();

/* ================= FUNCTIONS =================== */

// Data Query

function get_dataTable($db, $json =false){
	$table = $_POST['table'];
	$data = $db->query("SELECT rowid, code, title, desc FROM $table");
	$arr = array();
	while($row = $data->fetchArray(SQLITE3_ASSOC)){
		$arr[] = $row;
	}
	
	if($json)
		echo json_encode($arr);
	else
		echo _loop_datatable($arr, $table);
}

function insert_data($db){
	$data['code'] = $_POST['code'];
	$data['title'] = $_POST['title'];
	$data['desc'] = $_POST['desc'];

	$table = $_POST['table'];
	$db->exec("INSERT INTO $table (code, title, desc) VALUES ('{$data['code']}', '{$data['title']}', '{$data['desc']}')");
	$data['id'] = $db->lastInsertRowID();

	if($data['id'])
		echo json_encode($data);
	else
		echo json_encode(array('status'=>false));
}

function update_data($db, $id){
	$data['code'] = $_POST['code'];
	$data['title'] = $_POST['title'];
	$data['desc'] = $_POST['desc'];
	$data['id'] = $id;

	$table = $_POST['table'];
	$db->exec("UPDATE $table SET code = '{$data['code']}', title = '{$data['title']}', desc = '{$data['desc']}' WHERE rowid = {$id}");

	if($db->changes() > 0){
		$data['status'] = 'success';
		echo json_encode($data);
	} else {
		$data['status'] = 'false';
		echo json_encode($data);
	}
}

function delete_data($db, $id){
	$table = $_POST['table'];
	$del = $db->prepare("DELETE FROM $table WHERE rowid = :id");
	$del->bindValue(':id', $id);
	$result = $del->execute();

	if($result !== false){
		$data['status'] = 'success';
	} else {
		$data['status'] = 'false';
	}
	echo json_encode($data);
}

// helper function
function _loop_datatable($arr, $table = 'node'){
	$tpl = '';
	$i = 1;
	foreach($arr as $row){
		$tpl .= "<tr rel='".$row['rowid']."'>
		<td>".$i."</td>
		<td class='code'>".$row['code']."</td>
		<td class='title'>".$row['title']."</td>
		<td class='desc'>".$row['desc']."</td>
		<td class='option'>
		<a rel='tooltip' href='#' data-table='".$table."' class='btn btn-mini btn-success update-data' title='simpan perubahan' style='display:none'><i class='icon-save'></i></a>
		<a rel='tooltip' href='#' class='btn btn-mini cancel-update' title='batal edit' style='display:none'><i class='icon-minus-sign'></i></a>
		<a rel='tooltip' href='".$row['rowid']."' class='btn btn-mini btn-warning edit-data' title='edit'><i class='icon-pencil'></i></a>
		<a rel='tooltip' href='".$row['rowid']."' data-table='".$table."' class='btn btn-mini btn-danger delete-data' title='hapus'><i class='icon-remove'></i></a>
		</td>
		</tr>";
		$i++;
	}
	return $tpl;
}

// table function
function loop_head_table($db){
	$data = $db->query("SELECT rowid, code, title FROM column");
	$column = '<tr id="row0" data-row-id="0"><td class="yaxis">&nbsp;</td>';
	while($row = $data->fetchArray(SQLITE3_ASSOC)){
		$column .= '<td><span rel="tooltip" data-id="'.$row['rowid'].'" title="'.$row['title'].'">'.$row['code'].'</span></td>';
	}
	$column .= '</tr>';

	echo $column;
}

function loop_main_table($db){
	$data = $db->query("SELECT rowid, code, title FROM row");
	$numcols = $db->querySingle("SELECT count(rowid) as count FROM column");
	$i = 1;
	$rows = '';
	while($row = $data->fetchArray(SQLITE3_ASSOC)){
		$rows .= '<tr id="row'.$i.'" data-row-id="'.$row['rowid'].'" data-order-id="'.$i.'">';
		$rows .= '<th class="yaxis">
				<span rel="tooltip" title="'.$row['title'].'">
				<!--<a title="random data in this row" data-id="'.$row['rowid'].'" href="#"><i class="icon-retweet"></i></a>-->
				'.$row['code'].'</span></th>';
		
		for($j=1; $j<=$numcols; $j++){
			$rows .= '<td data-column-id="'.$j.'"><span rel="0" data-khatib="empty">&nbsp;</span></td>';
		}

		$rows .= '</tr>';
		$i++;
	}

	echo $rows;
}
function get_matrix($db){
	$matrix = $db->querySingle("SELECT matrix FROM nodes_version ORDER BY rowid DESC");

	// $data = array();
	// while($row = $matrix->fetchArray(SQLITE3_ASSOC)){
	// 	$data[] = json_decode($row['matrix']);
	// }

	if($matrix)
		echo str_replace('\"', '"', $matrix);
	else
		echo json_encode(array('status'=>'false'));	
}

function update_matrix($db){
	$matrix = $_POST['matrix'];
	$matrix = str_replace(",]", "]", $matrix);
	$save = $db->exec("INSERT INTO nodes_version (matrix) VALUES('".$matrix."')");
	echo $matrix;
}

function generate_node($db){
	$numcols = $db->querySingle("SELECT count(rowid) as count FROM column");
	$numrows = $db->querySingle("SELECT count(rowid) as count FROM row");
	$data = $db->query("SELECT rowid, code, title FROM node");

	$master = array();
	while($row = $data->fetchArray(SQLITE3_ASSOC)){
		$master[] = array('rowid'=>$row['rowid'], 'code'=>$row['code'], 'title'=>$row['title']);
	}

	// shuffle the array in the first
	shuffle($master);

	$khatibs = array();
	if($numrows > 1){
		$khatibs[] = $master;		
		for($i=1; $i<$numrows; $i++){
			$temp = array_pop($master);
			array_unshift($master, $temp);
			$khatibs[] = $master;
		}
	}
	shuffle($khatibs);

	$save = $db->exec("INSERT INTO nodes_version (matrix) VALUES('".json_encode($khatibs)."')");
	echo json_encode($khatibs);
}

function get_available_nodes($db){
	// catch data-order-id
	$activerow = $_POST['activerow']-1;
	
	// get available nodes data
	$nodes = $db->query("SELECT rowid, code, title FROM node");

	// get all matrix data
	$json = $db->querySingle("SELECT matrix FROM nodes_version ORDER BY rowid DESC");
	
	$data = array();

	// if there is one matrix
	if($json){
		$matrix = json_decode(str_replace('\"', '"', $json));

		// get only the row we neeed
		$node_in_row = $matrix[$activerow];

		// get only rowid's
		$node_ids = array();
		foreach ($node_in_row as $value) {
			$node_ids[] = $value->rowid;
		}

		// set available and unavailable node
		while($row = $nodes->fetchArray(SQLITE3_ASSOC)){
			if(in_array($row['rowid'], $node_ids))
				$row['available'] = 0;
			else
				$row['available'] = 1;
			$data[] = $row;
		};
	} else { //if matrix not found
		// set all as available node
		while($row = $nodes->fetchArray(SQLITE3_ASSOC)){
			$row['available'] = 1;
			$data[] = $row;
		};
	}

	$output = '';
	foreach ($data as $value) {
		$disabled = ($value['available'] == 1)?'':'disabled';
		$output .= '<div class="input-prepend">
  <span class="add-on">'.$value['code'].'</span>
<button class="span2 btn btn-success '.$disabled.'" data-id="'.$value['rowid'].'" data-code="'.$value['code'].'" data-title="'.$value['title'].'">'.$value['title'].'</button></div>&nbsp;&nbsp;&nbsp;';
	}

	echo $output;
	// print_r($data);
}

function compress_file($db){
	$latest = $db->querySingle("SELECT rowid FROM nodes_version ORDER BY rowid DESC");
	$db->exec("DELETE FROM nodes_version WHERE rowid != $latest");
	$res = $db->changes();

	echo $res;
}