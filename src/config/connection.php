<?php

/*
    Singleton Connection
*/
class Connection {

    private static $instance = null;
    private $connection = null;

    private function __construct()
    {
        try {
            $this->connection = new mysqli($_ENV['MYSQL_HOST_IP'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE'], '3306');
        } catch (mysqli_sql_exception $e) {
            throw $e;
        } 
            
    }

    public static function getInstance()
    {

        if(!self::$instance){
            self::$instance = new Connection();
        }
   
        return self::$instance;
 
    }

    public function getConnection()
    {
      return $this->connection;
    }
}
?>
