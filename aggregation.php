<?php
$action = $_GET['action'];
if ($action == 'insert_group_command'){
	insert_group_command();
} else if ($action = 'using_group_command'){
	view_group_command();
}
/**
 * Group Command that ra la mot hinh thuc dua code cua MongDB vao trong Code cua PHP 
 * Enter description here ...
 */
function view_group_command(){
	$connection = new Mongo();
	$db = $connection->selectDB('comedy');
	$animal = $db->animal;
	
	/**
	 * This example is simply grouping each animal into a class : Su dung code MongoDB de dua nhom cac con vat
	 */
	$reduce = new MongoCode('
	    function(doc, counter){
	    	counter.items.push(obj.name);
	    }
	    '
	);

	$group_animal = $animal->group(
	   array('class'=>1),
	   array('items' => array()),
	   $reduce
	);
	
	echo '<br/>' .json_encode($group_animal['retval']) . '<br/>';
	
	/**
	 * 
	 */
	
	$reduce = new MongoCode(
	   '
	       function(doc, counter){
	           counter.count++;
	       }
	   '
	);
	
	$group_animal = $animal->group(  
	   array('class'=>1),
	   array('count'=>0),
	   $reduce
	);
	
	echo '<br/>' .json_encode($group_animal) . '<br/>';
}

function insert_group_command(){
	$connection = new Mongo();
	$db = $connection->selectDB('comedy');
	
	$animal = $db->animal;
	
	$animal->drop();
	
	$animal->save(array('class'=>'mammal','name'=>'kangaroo'));
	$animal->save(array('class'=>'mammal','name'=>'seal'));
	$animal->save(array('class'=>'mammal','name'=>'dog'));
	$animal->save(array('class'=>'bird','name'=>'eagle'));
	$animal->save(array('class'=>'bird','name'=>'ostrich'));
	$animal->save(array('class'=>'bird','name'=>'emu'));
	$animal->save(array('class'=>'reptile','name'=>'snake'));
	$animal->save(array('class'=>'reptile','name'=>'turtle'));
	$animal->save(array('class'=>'amphibian','name'=>'frog'));
	
	echo "Inserted Already !";
}
?>