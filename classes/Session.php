<?php


/*
  SESSION CLASS
*/

class Session{

  // CHECK IF SESSION EXISTS
  public static function exists($name){
    return (isset($_SESSION[$name])) ? true : false;
  }

  // PUTS SOME VALUE TO THE SESSION
  public static function put($name, $value){
    return $_SESSION[$name] = $value;
  }

  // GETS THE VALUE ASSIGNED TO SESSION WITH SOME NAME
  public static function get($name){
    return $_SESSION[$name];
  }

  // DELETES THE SESSION
  public static function delete($name){
    if(self::exists($name)){
      unset($_SESSION[$name]);
    }
  }

  // FLASH ANY MESSAGE FOR A GIVEN SESSION NAME
  public static function flash($name, $string = ''){
    if(self::exists($name)){
      $session = self::get($name);
      self::delete($name);
      return $session;
    }
    else{
      self::put($name, $string);
    }
  }
    
}