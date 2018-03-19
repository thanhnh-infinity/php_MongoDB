<?php
$action = $_GET['action'];

if ($action == 'add'){
	addDocument();
} else if ($action == 'list'){
	viewList();
} else if ($action == 'address'){
	viewAddress();
} else if ($action == 'find_array'){
	find_array();
} else if ($action == 'condition'){
	condition();
} else if ($action == 'index'){
	index();
} else if ($action == 'agg_distinct'){
	distinct();
}


function distinct(){
    $m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;
	
	print_r($db->command(
	     array("distinct" => "addresses","key"=>"first_name")
	    ));
}

/**
 * Working with multiple documents : Permits update and delete multiple records in same time
 */
function index(){
	  /**
      * Finding use $and
      */
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;
	$addresses->ensureindex(array('_id' => 1));
	
	$cursors = $addresses->find();
	echo "<br/>Tim kiem su dung SAU KHI SU DUNG INDEX <br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	print_r($addresses->find()->explain());
	echo "<br/>Ket thuc tim kiem <br/>";
}

/**
 * Su dung Condition
 * $or
 * $nor
 * $and
 * $not
 * $exists
 * Enter description here ...
 */
function condition(){
     /**
      * Finding use $and
      */
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;
	
	$cursors = $addresses->find(array('$and'=>array(array('state'=>'Hanoi'),array('first_name'=>'Mai'))));
	echo "<br/>Tim kiem su dung AND <br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";
}
function find_array(){
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;


	/**
	 * Su dung cong thuc tim kiem IN  ($in)
	 * Enter description here ...
	 * @var unknown_type
	 */
	$status = $_GET['state'];
	$cursors = $addresses->find(array('state'=> array('$in'=> array('Hanoi','Saigon'))));
	echo "<br/>Tim kiem su dung IN <br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";

	/**
	 * Su dung cong thuc time kiem NOT IN  ($nin)
	 */
	$cursors = $addresses->find(array('first_name'=> array('$nin'=> array('Thanh','Nguyen Hai 2'))));
	echo "<br/>Tim kiem su dung NOT IN <br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";

	/**
	 * Su dung cong thuc tim kiem ALL  ($all)
	 * Enter description here ...
	 * @var unknown_type
	 */
	$cursors = $addresses->find(array('state'=> array('$all'=> array('Hanoi','Saigon'))));
	echo "<br/>Tim kiem su dung ALL <br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";


	/**
	 * Su dung cong thuc tim kiem MATCHING ARRAY
	 * Enter description here ...
	 * @var unknown_type
	 */
	$cursors = $addresses->find(array('state'=> array('Hanoi','SaiGon')));
	echo "<br/>Tim kiem su dung MATCHING ARRAY <br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";


	/**
	 * Su dung cong thuc $slice 
	 * 
	 */
	$cursors = $addresses->find(array(),array('state'=> array('$slice' => array(2,3))));
	echo "<br/>Tim kiem su dung SLICE voi 1 Parameter<br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";

	
	/**
	 * Su dung cong thuc $size
	 * Truy van theo truong nao co so luong cac phan tu duoc dac ta bang size
	 */
	$cursors = $addresses->find(array('state'=> array('$size' => 0 )));
	echo "<br/>Tim kiem su dung SIZE<br/>";
	foreach($cursors as $obj){
		echo "<br/>" . $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "<br/>Ket thuc tim kiem <br/>";
	

}
function addDocument(){
	// connect
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name

	// select a collection (analogous to a relational database's table)
	$collection = $db->cartoons; // Same as table
	$addresses = $db->addresses;

	/*
	 * Database => table => columns => rows
	 * Database => collections => documents
	 */

	// add a record
	$obj = array( "title" => "Thanh Nguyen Hai", "author" => "Mr" );
	$collection->insert($obj);

	// add another record, with a different "shape"
	$obj = array( "title" => "IT", "online" => true );
	$collection->insert($obj);


	// Add address
	$address = array(
       	'first_name' => 'Thanh',
       	'last_name' => 'Nguyen Hai 2',
       	'address' => '125 Hoang Quoc Viet',
    	'city' => 'Hanoi',
    	'state' => 'Hanoi',
    	'zip' => '10000'
    	);

    	$addresses->save($address, array('safe'=>true));

    	$pk = $address['_id'];
    	print_r ($pk);
    	echo "Added " . $pk .  " At " . $pk->getTimestamp();

}
function viewAddress(){
	// connect
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;


	$id = new MongoId('4fc5a1a36e6d8de812000029');
	$cursor = $addresses->find(array('_id' => $id));
	echo "Dat tim duoc <br/>";
	foreach ($cursor as $obj) {
		echo $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "Ket thuc tim <br/>";

	/* upadte */
	$addresses->update(
	array('_id'=> $id),
	array('$set' => array('status'=>'Em la co gai dep nhat the gian nay - '))
	);


	/** Tim kiem theo ID **/
	$id = new MongoId('4fc5a1a36e6d8de812000029');
	$cursor = $addresses->find(array('_id' => $id));
	echo "Dat tim duoc <br/>";
	foreach ($cursor as $obj) {
		echo $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  . $obj["status"] .  "<br/>"  ;
	}
	echo "Ket thuc tim <br/>";


	echo "Tiep tuc bat dau tim - Tim kiem theo cac truong khac ID <br/>";
	$cursor = $addresses->find(array('last_name' => 'Nguyen Hai 2'));
	foreach ($cursor as $obj) {
		echo $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}
	echo "Ket thuc tim <br/>";

	//Select table
	$cursor = $addresses->find();


	// iterate through the results
	foreach ($cursor as $obj) {
		echo $obj['_id'] . ":" . $obj["first_name"] . ":" . $obj["last_name"] . ":" . $obj["address"] . ":" . $obj["city"] . ":"  .  "<br/>"  ;
	}


	/* Delete */
	echo "bat dau xoa <br/>";
	$criteria = array('_id'=>new MongoId('4fc5a4636e6d8de81200002f'));
	$addresses->remove($criteria,array('justOne'=>true));
	echo "Da xoa <br/>";
}
function viewList(){
	// connect
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name

	// select a collection (analogous to a relational database's table)
	$collection = $db->cartoons; // Same as table

	/*
	 * Database => table => columns => rows
	 * Database => collections => documents
	 */
	// find everything in the collection
	$cursor = $collection->find();
	print_r($collection->find()->explain());
	// iterate through the results
	foreach ($cursor as $obj) {
		echo $obj["title"] . ":" . $obj["author"] . ":" . ":" . $obj["title"] . ":" . $obj["online"] . "<br/>"  ;
	  
	}
}

?>