<?php
    $lab = $_POST['label'];
    $list_t = substr($_POST['list_t'],0,-1);
    $list_n = substr($_POST['list_n'],0,-1);
    
    include 'class/database.php';
    $db = new Database();
    if($lab != 'R'){
        $pos = strval($lab);
        $neg = $pos . ':0';
        $pos .= ":1";
        $db->query("UPDATE DB_contents SET labels = CONCAT(CONCAT(labels, ','), ?) WHERE id IN (0".$list_t.");" , array($pos));
        $db->query("UPDATE DB_contents SET labels = CONCAT(CONCAT(labels, ','), ?) WHERE id IN (0".$list_n.");" , array($neg));
    }
    else{
        $db->query("DELETE FROM `FaceTag`.`DB_contents` WHERE `DB_contents`.`id` IN (0".$list_n.")");
    }
?>
