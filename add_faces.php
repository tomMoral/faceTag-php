<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    include 'class/database.php';
    $db = new Database();
    $continue = 1;
    
    foreach($_FILES["pics"]["name"] as $n => $f){
        $savefile = 'image/'.basename($f);
        echo $savefile;
        if(copy($_FILES["pics"]["tmp_name"][$n], $savefile)){
            $db->query("INSERT IGNORE INTO DB_contents (path, labels ) VALUE (?,?)", array($savefile, ''));
        }
        else{
            $continue = 0;
            echo 'Error for file :'.$savefile;
        }
        
    if($continue == 1)
        header("Location: faceTag.php");
    }
?>
