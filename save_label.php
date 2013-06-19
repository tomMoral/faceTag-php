<?php
    $lab = $_POST['label'];
    $list_t = substr($_POST['list_t'],0,-1);
    $list_n = substr($_POST['list_n'],0,-1);

    $pos = strval($lab);
    $neg = $pos . ':0';
    $pos .= ":1";
    include 'database.php';
    $db = new Database();
    $db->query("UPDATE DB_contents SET labels = CONCAT(CONCAT(labels, ','), ?) WHERE id IN (".$list_t.");" , array($pos));
    $db->query("UPDATE DB_contents SET labels = CONCAT(CONCAT(labels, ','), ?) WHERE id IN (".$list_n.");" , array($neg));

?>
