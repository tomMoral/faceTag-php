<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="action.js" type="text/javascript"></script>
        <title>Face Tagger</title>
    </head>


<body>
    <h1>Face tag : </h1></br>   
    <form action="add_faces.php" method="post" enctype="multipart/form-data">
        <input type="file" name="pics[]" multiple>
        <input type="submit" value="Submit">
    </form>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include 'database.php';
$db = new Database();

$labels = $db->query("SELECT *  FROM DB_labels");

echo "<select id='combo' onchange='comboBox(event);'>\n";
    echo "<option value='remove'>Remove</option>\n";
    echo "<option value='add'>Add</option>\n";
    foreach($labels as $lab)
        echo '<option value="'.$lab['id'].'">'.$lab['label']."</option>\n";
echo"</select>\n";

$pics = $db->query("SELECT *  FROM DB_contents");

echo "Pics grab : <button onclick='save()'>Save</button><button onclick='get()'>Get</button></br>";
echo '<div id="pictures">';
echo '<p id=status>There is '.count($pics).' pictures in the db</p>';

$handle_click = '<img class="img_neu" ';
$handle_click .= 'oncontextmenu="return false;" ';
$handle_click .= 'onmousedown="check(event);" ';
$handle_click .= 'src="';

foreach($pics as $p){
    echo $handle_click.$p['path'].'" id='.$p['id'].'>';
}
echo '</div>';

?>

</body>

</html>