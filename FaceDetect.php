<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function face_detect($files_list){
    $cmd = "export DYLD_LIBRARY_PATH='';";
    $files_cmd = "";
    $res = array();
    
    foreach($files_list as $n => $f)
        if($f->status == 1)
            $files_cmd .= (($n == 0)?"'":"' '") . $f->filename;
    $files_cmd .= "'";
    
    $cmd .= "python face_detect.py $files_cmd ";
    $files = exec( $cmd , $output);

    $res['next'] = 0;
    $res['html'] = "";
   include 'database.php';
   $dbh = new Database();
   
   foreach ($files as $output){
       $res['html'] .= "<img src='$f'/>";
       $dbh->query("INSERT IGNORE INTO DB_contents (path, labels) VALUES (?,?)",
                    array($f, ""));
    }
    return $res;
}

if(isset($_POST['files'])){
    $files = json_decode($_POST['files']);    
    header('Content-Type: application/json');
    echo json_encode(face_detect($files));
}

?>
