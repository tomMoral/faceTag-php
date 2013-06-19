<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include 'database.php';
$db = new Database(); 

$lab = $_POST['label'];

$pics = $db->query("SELECT * FROM DB_contents WHERE labels NOT LIKE ?", array("%,$lab:_%"));
$count = $db->query("SELECT count(id) as qte  FROM DB_contents");
$pics['tot'] = count($pics);
$pics['count'] = $count[0]['qte'];
echo json_encode($pics);
?>
