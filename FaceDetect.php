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
    $cmd .= "python ~/prog/github/face/benchmark/cluster/face_detect.py -r $files_cmd 2>&1";
    $files = exec( $cmd , $output);
    $res['next'] = 0;
    $res['html'] = "";
    if(count($output) > 1)
        foreach($output as $line)
            $res['html'] .= $line."</br>";
    else{
       include 'database.php';
       $dbh = new Database();
       foreach (preg_split('/::/', $files) as $f){
           $res['html'] .= "<img src='$f'/>";
           $dbh->query("INSERT IGNORE INTO DB_contents (path, labels) VALUES (?,?)",
                        array($f, ""));
       }
    }
           
    return $res;
}

if(isset($_POST['files'])){
    $files = json_decode($_POST['files']);    
    header('Content-Type: application/json');
    echo json_encode(face_detect($files));
    
    
}

?>
