<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($_FILES["pics"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
else
  {
    echo "hw";
    echo $_FILES["pics"]["name"];
  }
?>
