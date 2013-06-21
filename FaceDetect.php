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
    
    $cmd .= "python face_detect.py $files_cmd 2>&1";
    exec( $cmd , $output);

    $res['next'] = 0;
    $res['html'] = "";
    include 'class/database.php';
    $dbh = new Database();
    $res['count'] = 0;
    $res['out'] = "";
    foreach ($output as $n => $f)
        if($f[0] == '@'){
             $res['html'] .= "<img src='".substr($f, 2)."'/>";
             $dbh->query("INSERT IGNORE INTO DB_contents (path, labels) VALUES (?,?)",
                          array(substr($f, 2), ""));
             $res['count']++;
         }
        else
            $res['out'] .= $f."<br/>";
     return $res;
}

if(isset($_POST['files'])){
    $files = json_decode($_POST['files']);    
    header('Content-Type: application/json');
    echo json_encode(face_detect($files));
}

?>
