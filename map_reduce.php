<?php
$action = $_GET['action'];
if ($action == 'insert_map_reduce'){
	insert_map_reduce();
} else if ($action=='using_map_reduce'){
	using_map_reduce();
} else if ($action == 'using_map_reduce_find_modify'){
	using_map_reduce_find_modify();
}

/**
 * Map Reduce is a fairly popular approach used to distribute computing across many threads or nodes
 * Map Reduce = distrubuted + multi-threads (nodes)
 * Map Reduce is designed to handle extremely large data sets and does a great job by doing so.	NO GUARANTEE SPEED
 */

/**
 * Map Reduce is a framework for processing problems across large data sets using many nodes for massive parallelization
 * Inspired by functional programming
 * It primarily consists od a map function to be run many times in parallel and a reduce function that takes the output
 * from all maps and "reduces" them down to a single value for each key - in array case, set of values for each key
 * 
 */

/**
 * In MongoDB, Only 2 methods are required: the MAP method and REDUCE method
 * 
 */

function insert_map_reduce(){
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;
	
	$ck = array(
	    'first_name'=>'Clark',
	    'last_name'=>'Kent',
	    'address' =>'344 Clinton St., Apt. #3B',
		'city' =>'Metropolis',
		'state' => 'IL',
		'zip'=>'62960',
		'superpowers'=> array('superhuman strength', 'invulnerability','flight','superhuman speed','heat vision'),
	);
	
	$addresses->insert($ck);
	echo 'Inserted Already';
}
function using_map_reduce_find_modify(){
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;
	
	$result = $db->command(
	    array(
	      'findAndMofify'=>'addresses',
	  	  'query'=> array('first_name'=>'Clark'),
	   	  'update'=>array('$inc'=>array('state','BS')),
	      'upsert'=>1,
	      'new'=>1
	   )
	);
	
	echo "Updated !";
}
function using_map_reduce(){
	
	$m = new Mongo();

	// select a database
	$db = $m->comedy; // Database name
	$addresses = $db->addresses;
	
	/**
	 * Defining Map Function :
	 * It is important to recognize that the MapReduce functions are written in JavaScript and run on Server. The Map function references the variable this 
	 * to inspect the current document. Inside a map function, emit(key,value) is called once for every value wanting to be fed to the reducer. In most cases,
	 * this will only be one time ( as the example here), but if we wanted to count superpowers ( or for a blog, tags), we would call it multiple times (or
	 * even no times if no superpowers existed).
	 */
	$map = new MongoCode(
	  '
	     function(){
	        emit(this.state,1);
	     }
	  '
	);
	
	
	/**
	 * Defining Reduce Functions
	 * Like map function, it's written in JavaScript. The reduce function takes an array of all the emitted values and reduces them into a single value.
	 * This is commonly used to aggregate data to produce things like sums. Please note we are using a nowdoc, here to enclose the function in a string
	 * 
	 * return sum : It is easy to miss that the value returned by the reduce function matches the structure as the document emitted by the map function
	 * 
	 */
	$reduce = new MongoCode(
	  '
	     function(k,vals){
	        var sum = 0;
	        for(var i in vals){
	           sum += vals[i]; 
	        }
	        return sum;
	     }
	  '
	);
	
	
	/**
	 * It has 4 possible values : "inline"=>1, "replace"=>collectionName, "reduce"=>collectionName, and "merge"=>collectionName. Inline causes the command
	 * to return the data itself instead of a cursor object. replace replaces the output collection entirely (drop and create). merge keeps existing values 
	 * and replaces them with new values when keys match. reduce keeps existing values and uses the reduce function to reduce them to a single value when keys
	 * match
	 */
	$mr = $db->command(  
	   array(
	      'mapreduce'=>'addresses',
	  	  'map'=>$map,
	   	  'reduce'=>$reduce,
	      'out'=>array('merge'=>'stateCounts'),
	   )
	);
	
	
	/**
	 * 
	 * Because we used one of the methods that creates a collection, we need to perform a find on the collection, then iterate over the cursor find returns
	 * 
	 */
	$states = $db->selectCollection($mr['result'])->find();
	foreach($states as $state){
		echo '<br/>' . $state['value'] . ' heros live in ' . $state['_id'] . '<br/>';
	}	
}
?>