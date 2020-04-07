<?php

/*
  REDIRECT CLASS
*/

class Redirect{

  public static function to($location = null){
    if($location){

      // only for error 404 now
      if(is_numeric($location)){
        // remain at same page but display the error
        switch($location){
          case 404:
            header('HTTP/1.0 404 Not Found');
            include 'errors/404.php';
            exit();
          break;
        }
      }

      // header function
      header('Location: '.$location);
      exit();
    }
  }




}