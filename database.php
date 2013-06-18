<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of database
 *
 * @author tom
 */
class Database {
    //put your code here
    private $dbname     = "FaceTag";
    private $host       = "localhost";
    private $user       = "Tom";
    private $password   = "naruto";
    private $port       = 8888;

    private $DBH;

    public function __construct() {
        try {
            $this->DBH  = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->user, $this->password);
            
        } catch(PDOException $e) {
            echo $e->getMessage();  
        }
        $this->query("CREATE TABLE IF NOT EXISTS DB_contents (id INT, path TEXT, labels TEXT)");
        $this->query("CREATE TABLE IF NOT EXISTS DB_labels (id INT, label VARCHAR(255))");

    }
    
    public function query($sql, $var=array()){
        $query = $this->DBH->prepare($sql);
        echo "hw";
        if(!$query->execute($var)){
            echo "error";
            print_r($query->errorInfo());
        }
        $res=array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $res[]=$row;     
        }
        return $res;
    }
    public function disconnect() {
        $this->DBH  = null; 
    }
}

?>
