<?php
$action = $_GET['action'];
if ($action == 'using_grid_fs_save_photo'){
	save_photo_using_grid_fs();
}

function save_photo_using_grid_fs(){
	$connection = new Mongo();
	$db = $connection->selectDB('photos');
	$grid = $db->getGridFS();
	
	//The file's location in the File System
	$path = "D:/Study-Working/Working/xampp/xampp/htdocs/MongoDB/MongoDB/";
	$filename = 'abc.jpg';
	$storeFile = $grid->storeFile(
	    $path . $filename,
	    array('metadata'=>array('filename' => $filename)),
	    array('filename'=>$filename)
	);
	echo $storeFile;
}
?>