<?php

$lab = $_POST['label'];

include 'class/database.php';
$db = new Database();

$pics = $db->query("SELECT *  FROM DB_contents WHERE labels LIKE ?", array("%,$lab:_%"));
$lab_txt = $db->query("SELECT label FROM DB_labels WHERE id=?", array($lab));
$lab_txt = $lab_txt[0]['label'];
    
$img_p = "";
$img_n = "";

$filename = "database/".$lab_txt."_".date("d-m-y_H:i").".csv";
$file_handle = fopen($filename, "w+");
$count_p = 0; $count_n = 0;
foreach($pics as $p){
    if(preg_match("/,$lab:1/", $p['labels'])){
        $d = "1";
        $count_p ++;
        $img_p .= "<img class='true' src='".$p['path']."'>";
    }
    else{
        $d = "0";
        $count_n ++;
        $img_n .= "<img class='false' src='".$p['path']."'>";
    }
    $data = $p['path']."\t".$d."\n";
    fwrite($file_handle, $data);
}
echo "There is ".($count_n+$count_p)."pictures for the label $lab_txt<br/>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Positive example : $count_p<br/>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Negative example : $count_n<br/>";
echo "write the db in the file $filename<br/>";
echo $img_p.'<br/>';
echo $img_n.'<br/>';
?>
