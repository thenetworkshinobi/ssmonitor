<?php


class dbConnect
{
    private $dbhost = "192.168.100.239";
    //private $dbhost = "td-jgriffith-l";
    private $dbname = "ssmonitor";
    private $dbuser = "ssadminuser";
    private $dbpassword = "Password1";
    public $dbh = null;
    
    public function connect()
    {
        $this->dsn  = "mysql:host=" . $this->dbhost . ";dbname=" . $this->dbname;

        try {
            $this->dbh = new PDO($this->dsn, $this->dbuser,$this->dbpassword);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $this->dbh;
    }
}