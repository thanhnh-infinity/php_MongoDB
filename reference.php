<?php
$action = $_GET['action'];
if ($action == 'insert_manual_reference'){
	insert_data_and_manual_reference();
} else if ($action == 'insert_dbref'){
	insert_data_and_dbRef();
}

function insert_data_and_dbRef(){
	$m = new Mongo();
	// select a database
	$db = $m->comedy; // Database name
	$articles = $db->articles;
	
	$articles->drop();
	
	/* Create Object 1 WITH primary key reference*/
	$id = new MongoId();
	$post = array(
	    'title'=>'MongoDB and PHP',
	    'text'=>'MongoDB and PHP are like PB and J. Good alone, great together',
		'related'=> array($db->createDBRef('articles',$id)),
	);
	
	$db->articles->insert($post);
	
	$post2 = array(
	    '_id' => $id,
	    'title'=>'JAVA and PHP',
	    'text'=>'JAVA and PHP are like PB and J. Good alone, great together',
		'related'=> array($db->createDBRef('articles',$post)),
	);
	
	$articles->insert($post2);
	
	/* Query to mapp and reference */
	echo '<br/>In tat cac cac doi tuong<br/>';
	$cursor = $articles->find();
	foreach($cursor as $record){
		print_r ($record);
	}
	
	echo '<br/>Access DBRefs<br/>';
	$cursor = $articles->getDBRef($post['related'][0]);
	print_r ($cursor);
}
function insert_data_and_manual_reference(){
	$m = new Mongo();
	// select a database
	$db = $m->comedy; // Database name
	$articles = $db->articles;
	
	$articles->drop();
	
	/* Create Object 1 WITH primary key reference*/
	$post = array(
	    'title'=>'MongoDB and PHP',
	    'text'=>'MongoDB and PHP are like PB and J. Good alone, great together',
		'author'=>'spf13',
	);
	
	$articles->insert($post,true);
	$id = new MongoId();
	$post = array(
	     '_id' => $id,
	     'title'=>'JAVA and PHP',
	     'text'=>'JAVA and PHP are like PB and J. Good alone, great together',
		 'author'=>'thanhnh',
	);
	
	$articles->insert($post);
	
	/* Create Object 2 : Passive Reference */
	$users = $db->users;
	$users->drop();
	$user = array(
	    '_id' => 'spf13',
	    'name'=> 'Steve Francia',
	);
	$users->insert($user);
	
	
	/* Query using Reference */
	$cursor = $articles->find(array('author'=> $user['_id']));
	foreach($cursor as $record){
		print_r ($record);
	}
	
	
}
?>