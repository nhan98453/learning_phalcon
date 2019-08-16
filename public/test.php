<?php

require '../vendor/autoload.php';  
// Creating Connection  
$con = new MongoDB\Client("mongodb://localhost:27017");  

// Creating Database  
$db = $con->test;  

// Creating Document  
$collection = $db->employee;  


$newdata = array('$set' => ["name" => "Smith"]);

$record = $collection->find(["name" => "Peter2"]); 

$record->update($newdata);

// Fetching Record  
//$record = $collection->find( [ 'email' =>'peter2@gmail.com'] );  
$record = $collection->find();  

foreach ($record as $employe) {  
	echo $employe['name'], ': ', $employe['email']."<br>";  
}  
?>  