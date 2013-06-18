<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include 'database.php';
$db = new Database(); 
echo "<h1>Face tag : </h1></br>";

$pics = $db->query("SELECT *  FROM DB_contents");

echo "Pics grab :";

foreach($pics as $p){
    echo '<img src="'.$p['path'].'"></br>';
}

?>

<form action="add_faces.php" method="post">
    Files : <input name="pics" type="file" multiple />
    <input type="submit" value="Add"/>
</form>
