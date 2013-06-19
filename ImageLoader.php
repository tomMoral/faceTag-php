<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ERROR);
function save($urls, $query){
    $dirname = "image/".$query.'/';
    if(!file_exists($dirname))
        mkdir($dirname);
    $files = array();
    $success = 0;
    foreach($urls as $url){
        $part = parse_url($url);
        $filename = basename($part['path']);
        $n = 0;
        $ext = '.'.pathinfo($filename, PATHINFO_EXTENSION);
        $base = pathinfo($filename, PATHINFO_FILENAME );
        while(file_exists($dirname.$base.strval($n).$ext)){
            $n ++;
        }
        $filename = $dirname.$base.strval($n).$ext;
        if(copy($url, $filename)){
            $files[] = array('filename' => $filename,
                           'status' => 1);
            $success++;
        }
        else
            $files[] = array('filename' => $filename,
                           'status' => 0);
    }
    $res = array();
    $n = count($files);
    $res['next'] = 0;
    $res['html'] = '';
    
    if($n !=0){
        $res['next'] = 1;
        $res['html'] = "Load $success / $n pics</br>";
        $res['work'] = "files=".json_encode($files);
    }
    return $res;
}

    if(isset($_POST['urls'])){
        $urls = json_decode($_POST['urls']);
        $query = $_POST['query'];
        header('Content-Type: application/json');
        echo json_encode(save($urls, $query));
    }
?>
