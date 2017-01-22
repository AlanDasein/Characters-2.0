<?php

class Connection {

    private $user;
    private $password;
    private $database;
    private $host;
    private $tech;

    public function __construct($_user, $_password, $_database, $_host = "localhost", $_tech = "mysql") {

        $this->user = $_user;
        $this->password = $_password;
        $this->database = $_database;
        $this->host = $_host;
        $this->tech = $_tech;

    }

    protected function connect() {

        if($this->tech == "mysql") {return new PDO("mysql:host=".$this->host.";dbname=".$this->database.";charset=utf8", $this->user, $this->password);}

        if($this->tech == "pgsql") {return new PDO("pgsql:dbname=".$this->database.";host=".$this->host, $this->user, $this->password);}

        if($this->tech == "sqlite") {return new PDO("sqlite:".$this->database);}

        if($this->tech == "firebird") {return new PDO("firebird:dbname=".$this->database, "SYSDBA", "masterkey");}

        if($this->tech == "informix") {return new PDO("informix:DSN=InformixDB", $this->user, $this->password);}

        if($this->tech == "oracle") {return new PDO("oci:dbname=".$this->database.";charset=UTF-8", $this->user, $this->password);}

        if($this->tech == "odbc") {return new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=".$this->database.";Uid=Admin");}

        if($this->tech == "dblib") {return new PDO("dblib:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);}

        if($this->tech == "ibm") {return new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".$this->database."; HOSTNAME=".$this->host.";PORT=56789;PROTOCOL=TCPIP;", $this->user, $this->password);}

        return false;

    }

    public function query($_type, $_request, $_values) {

        try {

            $db = $this->connect();

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $db->beginTransaction();

            $stmt = $db->prepare($_request);

            $stmt->execute($_values);

            $id = $_type == "INSERT" ? $db->lastInsertId() : 0;

            $db->commit();

            if($_type == "SELECT") {

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(empty($results)) {return false;}
                else {

                    $db = null;

                    return $results;

                }

            }
            else {return $_type == "INSERT" ? $id : $stmt->rowCount();}

        }
        catch(PDOException $e) {

            if(!empty($db)) $db->rollback();

            die($e->getMessage());

        }

    }

}