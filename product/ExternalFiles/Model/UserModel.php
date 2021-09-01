<?php
//require_once ('const_local.php');
require_once (dirname(__FILE__) . '/../const.php');
class UserModel
{
    public $dbh;

    public function connect()
    {
        try {
            $this->dbh = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPASS);
            $this->dbh->exec('set names utf8');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}