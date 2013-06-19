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
    private $user       = "root";
    private $password   = "naruto";
    private $port       = 8888;

    private $DBH;

    public function __construct() {
        try {
            $this->DBH  = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->user, $this->password);
            
        } catch(PDOException $e) {
            echo $e->getMessage();  
        }
        $this->query("
            CREATE TABLE IF NOT EXISTS DB_contents (
                id int NOT NULL AUTO_INCREMENT, 
                path VARCHAR(255) NOT NULL, 
                labels TEXT,
                PRIMARY KEY (id),
                UNIQUE path (path)
             )"
        );
        $this->query("
            CREATE TABLE IF NOT EXISTS DB_labels (
                id int NOT NULL AUTO_INCREMENT, 
                label VARCHAR(50),
                PRIMARY KEY (id),
                UNIQUE KEY label (label)
             )"
         );
    }
    
    public function query($sql, $var=array()){
        $query = $this->DBH->prepare($sql);
        if(!$query->execute($var)){
            echo "Error!!<br/>";
            print_r($query->errorInfo());
        }
        $res=array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $res[]=$row;     
        }
        return $res;
    }
    
    public function getID(){
        return $this->DBH->lastInsertId();
    }
    
    public function disconnect() {
        $this->DBH  = null; 
    }
}

?>
