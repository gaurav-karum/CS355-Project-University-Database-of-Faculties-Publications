<?php

/* 

  FUNCTIONS TEST


  $user = DB::getInstance();
  $user->query("SELECT * FROM login");   
  $user->get('login', array('l_webmail','=','test@iitp.ac.in'));  
  $user->delete('login', array('l_webmail','=','test@iitp.ac.in')); 
  $userInsert->insert('table_name', array(
    'column1' => 'value1'
  )); 
  $userUpdate->update('login', 'test1@iitp.ac.in', array(
    'l_username' => 'newtestuser1',
    'l_password' => 'newtestpass1'
  )); 

  if (!$userUpdate->count()) {
    echo 'No user';
  } 
  else {
    echo 'OK';
    foreach($user->results() as $user){
      echo "<h6>$user->l_username $user->l_webmail</h6>";
    }
  } 

*/

class DB
{

  // variables
  private static $_instance = null;
  private $_pdo,
    $_query,
    $_error = false,
    $_results,
    $_count = 0;

  /*
    
    CONNECT WITH DB

  */

  private function __construct(){
    // try catch
    try {
      $dsn = 'mysql:dbname=project;host=db'; // see docker-compose file
      $user = 'user';
      $password = 'test';
      $this->_pdo = new PDO($dsn, $user, $password); // php PDO documentation

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /*
    
    GET AN INSTANCE
  
  */
  
  public static function getInstance()
  {
    if (!isset(self::$_instance)) {
      self::$_instance = new DB();
    }
    return self::$_instance;
  }

  
  /*
    
    QUERY EXECUTE - Takes sql query and executes it

  */
  
  public function query($sql, $params = array())
  {
    $this->_error = false;
    
    if ($this->_query = $this->_pdo->prepare($sql)) {
      $x = 1;
      if (count($params)) {
        foreach ($params as $param) {
          $this->_query->bindValue($x, $param);
          $x++;
        }
      }
      //echo "<br>"; var_dump($this->_query) ;

      if ($this->_query->execute()) {
        $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
        $this->_count = $this->_query->rowCount();
        //echo "EXECUTED";
        //echo "Result<br>";
        //echo "Rowcount : ";
        //echo $this->_count;
     
      } else {
        $this->_error = true;
      }
    }
    return $this;
  }

  /* 
  
    SELECT QUERY MAKER

    SELECT QUERY 0 MAKER - 1 WHERE CONDITION
    SELECT QUERY 1 MAKER - 2 WHERE CONDITION
  
  */

  // SELECT QUERY0 MAKER - 1 WHERE CONDITION
  public function action($action, $table, $col1,$op1, $val1)
  {
    $sql = "{$action} FROM {$table} WHERE {$col1} {$op1} '{$val1}'";
    //echo $sql;
    if($this->query($sql)){
      return $this;
    }
    return false;
  }

  // SELECT QUERY1 MAKER - 2 WHERE CONDITION
  public function action1($action, $table, $col1,$op1, $val1, $col2,$op2, $val2)
  {
    $sql = "{$action} FROM {$table} WHERE {$col1} {$op1} '{$val1}' AND {$col2} {$op2} '{$val2}'";
    // echo $sql;
    if($this->query($sql)){
      return $this;
    }
    return false;
  }

  /*
    
    QUERY FUNCTIONS

    SELECT QUERY 0, 1
    DELETE QUERY
    INSERT QUERY
    UPDATE QUERY
    RETURN FIRST ROW OF RESULT
    RETURN ROWCOUNT OF RESULT
    RETURN ERROR 


  */

  // SELECT QUERY 0 - 1 WHERE CONDITION
  public function get($table, $col1, $op1, $val1)
  {
    return $this->action("SELECT *", $table, $col1, $op1, $val1);
  }

  // SELECT QUERY 1 - 2 WHERE CONDITION 
  public function get1($table, $col1, $op1, $val1, $col2, $op2, $val2)
  {
    return $this->action1("SELECT *", $table, $col1, $op1, $val1, $col2, $op2, $val2);
  }
  
  // DELETE QUERY 
  public function delete($table, $col1, $op1, $val1)
  {
    return $this->action('DELETE', $table, $col1, $op1, $val1);
  }

  // INSERT QUERY
  public function insert($table, $fields = array())
  {

    $keys = array_keys($fields);
    $values = null;
    $x = 1;

    foreach ($fields as $field) {
      $values .= '?';
      if ($x < count($fields)) {
        $values .= ', ';
      }
      $x++;
    }

    $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

    if (!$this->query($sql, $fields)->error()) {
      return true;
    } 
    return false;
  }

  // UPDATE QUERY ON LOGIN
  public function update($table, $condition, $mail, $fields = array())
  {
    $x = 1;
    $set = '';

    foreach ($fields as $name => $value) {
      $set .= "{$name} = ?" ;
      if ($x < count($fields)) {
        $set .= ', ';
      }
      $x++;
    }
 
    $sql = "UPDATE {$table} SET {$set} WHERE {$condition} = '{$mail}'";
  
    if (!$this->query($sql, $fields)->error()) {
      return true;
    } 
    return false;
  }

  // QUERY RESULT 
  public function results()
  {
    return $this->_results;
  }

  // FIRST RESULT OF SELECT QUERY
  public function first()
  {
    return $this->results()[0];
  }

  // RETURN ROWCOUNT OF SELECT QUERY RESULT
  public function count()
  {
    return $this->_count;
  }
  // RETURN ERROR COUNT
  public function error()
  {
    return $this->_error;
  }
}



    // backup - action1
    // if (count($where) === 6) {
    //   $operators = array('=', '>', '<', '>=', '<=');

    //   // 3 params of where array that is passed for performing the query
    //   $field1 = $where[0];
    //   $field2 = $where[3];
    //   $operator1 = $where[1];
    //   $operator2 = $where[4];
    //   $value1 = $where[2];
    //   $value2 = $where[5];
    //   echo $value1. $value2;

    //   if (in_array($operator1, $operators) && in_array($operator2, $operators)) {
    //     $sql = "{";
    //     echo "<br>"; echo $sql;
    //     if ($this->query($sql, array($value1, $value2))) {
    //       return $this;
    //     }
    //   }
    // }