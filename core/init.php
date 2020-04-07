<?php

// START SESSION AT EACH RELOAD OF PAGE 
session_start();

// SETTING THE CONFIGS
$GLOBALS['config'] = array(
  'mysql' => array(
    'host' => 'db',
    'user' => 'user',
    'password' => 'test',
    'db' => 'project'
  ),
  'remember' => array(
    'cookie_name' => 'hash',
    'cookie_expiry' => 604800 
  ),
  'session' => array(
    'session_name' => 'user',
    'token_name' => 'token'
  )
);

spl_autoload_register(function($class){
  require_once 'classes/' .$class. '.php';
});

require_once 'functions/sanitize.php';

