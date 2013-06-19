<?php

$lab = $_POST['label'];

include 'database.php';
$db = new Database();

$pics = $db->query("SELECT *  FROM DB_contents WHERE labels LIKE ?", array("%,$lab:_%"));
$lab_txt = $db->query("SELECT label FROM DB_labels WHERE id=?", array($lab));
$lab_txt = $lab_txt[0]['label'];

print_r($lab_txt);
echo "Database label $lab_txt : </br>";
foreach($pics as $p){
    echo $p['path']."\t".((preg_match("/,$lab:1/", $p['labels']))?"1":"0")."</br>";
}
?>
