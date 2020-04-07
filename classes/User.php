<?php

class User
{
  private $_db,
    $_sessionName,
    $_isLoggedIn,
    $_data;

  // GET DB INSTANCE AND SESSION NAME
  public function __construct($user = null)
  {
    $this->_db = DB::getInstance();
    $this->_sessionName = Config::get('session/session_name');

    if (!$user) {
      if (Session::exists($this->_sessionName)) {
        $user = Session::get($this->_sessionName);

        if ($this->find($user)) {
          $this->_isLoggedIn = true;
        } else {
          echo 'User not exist';
        }
      }
    } else {
      $this->find($user);
    }
  }

  // REGISTER - inserts into login table
  public function create($table, $fields = array())
  {
    if (!$this->_db->insert($table, $fields)) {
      throw new Exception('There was a problem inserting the data into table '.$table);
    }
  }

  // FIND THE USER WITH GIVEN CREDENTIALS IN LOGIN TABLE
  public function find($user = null)
  {
    if ($user) {
      // $field = (is_numeric($user)) ? 'id' : 'username';
      $data = $this->_db->get('login', 'l_webmail', '=', $user);
      if ($data->count()) {
        $this->_data = $data->first();
        return true;
      }
    }
    return false;
  }

  // LOGIN THE USER IF CORRECT CREDENTIALS FOUND IN LOGIN TABLE
  public function login($username = null, $password = null)
  {
    $user = $this->find($username); // print_r($this->_data);
    if ($user) {
      if ($this->data()->l_password === $password) {
        Session::put($this->_sessionName, $this->data()->l_webmail);
        // ECHO 'OK';
        return true;
      }
    }
    return false;
  }

  // LOGOUT IF USER CLICKS LOGOUT OR IF SESSION EXPIRES
  public function logout()
  {
    Session::delete($this->_sessionName);
  }

  public function data()
  {
    return $this->_data;
  }

  public function isLoggedIn()
  {
    return $this->_isLoggedIn;
  }
}
