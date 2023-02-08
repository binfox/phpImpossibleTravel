<?php

class datenbank {

    private static $MySqlIcon = false;
    private static $schemaname='';

    public function __construct() {
        if (!datenbank::$MySqlIcon) {
            $Configs = include('config/config.php');
	    datenbank::$schemaname=$Configs->database;
            datenbank::$MySqlIcon = new mysqli($Configs->host, $Configs->username, $Configs->password, $Configs->database);
            if (datenbank::$MySqlIcon->connect_error) {
                die('Connect Error (' . datenbank::$MySqlIcon->connect_errno . ') ' . datenbank::$MySqlIcon->connect_error);
            }
        }
    }

    public function __destruct() {
        $this->CleanUp_DB();
    }

    public static function CleanUp_DB() {
        if (datenbank::$MySqlIcon != false) {
            datenbank::$MySqlIcon->close();
        }
        datenbank::$MySqlIcon = false;
    }

    public function exec_sql_insert($SqlString) {
        $Result = datenbank::$MySqlIcon->query($SqlString);
	if ($Result == false) {
            echo "ERROR: can not insert:<br/>".$SqlString.'<br/>';
        }
        return datenbank::$MySqlIcon->insert_id;
    }

    public function exec_sql($SqlString,$commit=false) {
        $Result = datenbank::$MySqlIcon->query($SqlString);
        if ($commit) {datenbank::$MySqlIcon->commit();}
        if ($Result == false) {
            echo "ERROR: can not:<br/>".$SqlString.'<br/>';
        }
        return $Result;
    }

    protected function start_transaktion() {
        datenbank::$MySqlIcon->begin_transaction();
    }

    protected function commit_transaktion() {
        datenbank::$MySqlIcon->commit();
    }

}
