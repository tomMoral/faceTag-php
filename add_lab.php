<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if(isset($_POST['label'])){
    include 'database.php';
    $dbh = new Database();
    
    $lab = $_POST['label'];
    
    $dbh->query("INSERT IGNORE INTO DB_labels (label) VALUES (?)", array($lab));
    
    $res = array();
    $res['label'] = $lab;
    $res['id'] = $dbh->getId();
    echo json_encode($res);
    
}

?>
