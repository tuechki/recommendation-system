<?php
  /*
   * PDO Database Class
   * Connect to database
   * Create prepared statements
   * Bind values
   * Return rows and results
   */
  class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $connection;
    private $stmt;
    private $error;

    public function __construct(){
      // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );

      // Create PDO instance
      try{
        $this->connection = new PDO($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }

    public function beginTransaction() {
          return $this->connection->beginTransaction();
    }

    public function commit() {
          return $this->connection->commit();
    }

    public function rollBack() {
          return $this->connection->rollBack();
    }

    // Prepare statement with query
    public function query($sql){
      $this->stmt = $this->connection->prepare($sql);
    }

    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        // Explicitly set PDO::PARAM_STR for strings
        if (!is_null($type) && $type !== PDO::PARAM_STR && is_string($value)) {
            $type = PDO::PARAM_STR;
        }

        $this->stmt->bindValue($param, $value, $type);
    }


    // Execute the prepared statement
    public function execute(){
      try{
        return $this->stmt->execute();
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }

    // Get result set as array of objects
    public function resultSet(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single(){
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount(){
      return $this->stmt->rowCount();
    }
    public function getLastInsertedId(){
      return $this->connection->lastInsertId();
    }
  
  }