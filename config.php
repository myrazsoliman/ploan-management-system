<?php
define("db_host", "127.0.0.1");
define("db_user", "root");
define("db_pass", "");
define("db_name", "db_lms");

class db_connect
{
    public $host = db_host;
    public $user = db_user;
    public $pass = db_pass;
    public $name = db_name;
    public $conn;
    public $error;

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->user,$this->pass, $this->name,3306);

        if (! $this->conn) {
            $this->error = "Fatal Error: Can't connect to database" . $this->conn->conn_error();
            return false;
        }
    }

}
