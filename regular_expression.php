<?php
$action = $_GET['action'];
if ($action == 'insert_data'){
	insert_data();
} else if ($action == 'create_regex'){
	create_regular_expression();
}

function insert_data(){
	$m = new Mongo();
	// select a database
	$db = $m->comedy; // Database name
	$colors = $db->colors;
	
	$colors->drop();
	
	/* Insert data to colors document */
	$colors->save(array('color'=>'red'));
	$colors->save(array('color'=>'blue'));
	$colors->save(array('color'=>'green'));
	$colors->save(array('color'=>'purple'));
	$colors->save(array('color'=>'orange'));
	$colors->save(array('color'=>'turquoise'));
	$colors->save(array('color'=>'black'));
	$colors->save(array('color'=>'brown'));
	$colors->save(array('color'=>'teal'));
	$colors->save(array('color'=>'silver'));
	$colors->save(array('color'=>'tan'));
	$colors->save(array('color'=>'navy'));
	$colors->save(array('color'=>'yellow'));
	$colors->save(array('color'=>'indigo'));
	
	$colors->ensureIndex('color');
	
	echo "Da Insert !";
}

function create_regular_expression(){
	$m = new Mongo();
	// select a database
	$db = $m->comedy; // Database name
	$colors = $db->colors;
    	
	echo '<br/>Using B-Tree ! <br/>';
	print_r ($colors->find(array('color' => new MongoRegex('/^b/')))->explain());
	
	echo '<br/>Create Regular Expression with ^b! <br/>';
	
	$cursor = $colors->find(array('color' => new MongoRegex('/^b/')));
	foreach($cursor as $record){
		print_r($record);
	}
	
	echo '<br/>Using B-Tree ! <br/>';
	print_r ($colors->find(array('color' => new MongoRegex('/e$/')))->explain());
	echo '<br/>Create Regular Expression with ^e! <br/>';
	$cursor = $colors->find(array('color' => new MongoRegex('/e$/')));
	foreach($cursor as $record){
		print_r($record);
	}
	
}
?>