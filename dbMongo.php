<?php
$conn = new Mongo();
$db = $conn->selectDB('test');
$db->numbers->drop();
for($i=0 ; $i < 250000;$i++){
	$db->numbers->save(array('num'=> $i));
}
echo 'ADDED . <br/>';
$results = $db->numbers->find()->limit(2);
foreach($results as $document){
	print_r ($document);
}
echo "<br/>Sap xep giam dan<br/>";
/* Sort */
$results = $db->numbers->find()->limit(2)->skip(20)->sort(array('num'=> -1));
foreach($results as $document){
	print_r ($document);
}
echo "<br/>Test Viec so sanh :<br/>";
echo "<br/>Danh sach cac so nho hon 15<br/>";
/** Cac bieu thuc so sanh */
/*
 * $gt  >
 * $lt  <
 * $gte >=
 * $lte <=
 */

$results = $db->numbers->find(
    array(
       'num' => array('$lt'=>15)
    )
);
foreach($results as $document){
	print_r ($document) . "<br/>";
}


/**
 * Working with Array
 */
/**
 * $all 
 * $in 
 * $nin
 * $size
*/

/**
 * Test Indexes
 */
echo "<br/>Test KHONG Indexes voi ham explain() (de y thoi gian run) <br/>";
print_r($db->numbers->find(
							array('num'=> array('$gt' => 50000, '$lt' =>50002)))->explain());
echo "<br/>Ket thuc test KHONG INDEXES<br/>";


/**
 * Test co indexes
 */
echo "<br/>Test CO  Indexes voi ham explain() (de y thoi gian run)<br/>";
$db->numbers->ensureindex(array('num'=> -1));
print_r($db->numbers->find(
							array('num'=> array('$gt' => 50000, '$lt' =>50002)))->explain());
echo "<br/>Ket thuc test CO INDEXES<br/>";

?>