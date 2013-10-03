<?php
/**
 * Description of class
 *
 * @author Florian
 */
namespace System\Database;
class MySql {    
    public $MySqliObj;
    public $lastQuery;
    public $lastSQLState;
    
    public function __construct($server, $user, $password, $database) {
        $startb = microtime(true);
        $log = $GLOBALS['log'];
        $this->MySqliObj = new \mysqli($server, $user, $password, $database);
        #auf Fehlerfall prüfen
        if(mysqli_connect_errno() != 0)
        {
            echo 'Fehler beim Verbindungsaufbau';
            $log("Connection-Error:");
            die();
            
        }
        
        $this->MySqliObj->query("SET NAMES utf8");
        }
    
    function __destruct() {
        if(mysqli_connect_errno() == 0)        
            $this->MySqliObj->close();
    }
    
    public function query($sqlQuery, $returnMySQLResult = false)
    {
        $log = $GLOBALS['log'];
        #letzte Query in Variable speichern
        #-> Logzwecke
        $this->lastQuery = $sqlQuery;
    
        
        $result = $this->MySqliObj->query($sqlQuery);
        #falls 2. Parameter true ist, geben wir das Ergebniss als MysqliResult zurück,
        #ansonsten wandeln wir es dierekt in ein Array um       
        if($returnMySQLResult == true)
        {
            #Zweck Log den Status der leztten Abfrage setzen
            if($result == false)
                $this->lastSQLState = false;
            else
                $this->lastSQLState = true;
            
            $log($this->lastQuery." -was- ".$this->lastSQLState);
            return $result;
        }
        else
        {
            $return = $this->SQL2Array($result);
            $log($this->lastQuery." -was- ".$this->lastSQLState);
            return $return;
        }
    }
    
    public function queryNoLog($sqlQueryNL, $returnMySQLResult = false)
    {    
         $result = $this->MySqliObj->query($sqlQueryNL);
        #falls 2. Parameter true ist, geben wir das Ergebniss als MysqliResult zurück,
        #ansonsten wandeln wir es dierekt in ein Array um       
        if($returnMySQLResult == true)
        {
            return $result;
        }
        else
        {
            $return = $this->SQL2Array($result);
            return $return;
        }
    }
    
    public function lastSQLError($errorno = false)
    {
        if($errorno == true)
            return $this->MySqliObj->errno; 
        else
            return $this->MySqliObj->error;   
    }
    public function SQL2Array($SQL)
    {     
        if($SQL == false)
        {
            $this->lastSQLState = false;
            return false;
        }
        else if($SQL == true)
        {
              $this->lastSQLState = true;
              return true;
        }
        else if($SQL->num_rows == 0)
        {
                $this->lastSQLState = true;
                return array();
        }
        else
        {
            $return = array();
            while($line = $SQL->fetch_array(MYSQLI_ASSOC))
            {
                array_push($return,$line);
            }
            $this->lastSQLState = true;
            return $return;
        }
    }
}
?>
