<?php

namespace Custom;

require_once __DIR__ . '/../config/dbConfig.php';

class DB
{
    private $password;
    private $dbUser;
    private $dbName;

    public function __construct()
    {
        $this->dbName = DB_NAME;
        $this->dbUser = DB_USER;
        $this->password = DB_PASSWORD;
    }

    public function connect()
    {
        return new \PDO('mysql:dbname=bmi-tool;host=localhost', $this->dbUser, $this->password);
    }

}