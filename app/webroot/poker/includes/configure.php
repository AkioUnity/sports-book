<?php
  $pathToDbConfig = dirname(dirname(__FILE__))."/../../Config/database.php";
  require_once($pathToDbConfig);
  $DBConfig = new DATABASE_CONFIG();
  $databaseServer = $DBConfig->default['host'].':'.$DBConfig->default['port'];
  $databaseUsername = $DBConfig->default['login'];
  $databasePassword = $DBConfig->default['password'];
  $databaseName = $DBConfig->default['database'];

  define('DB_SERVER', $databaseServer);
  define('DB_SERVER_USERNAME', $databaseUsername);
  define('DB_SERVER_PASSWORD', $databasePassword);
  define('DB_DATABASE', $databaseName);
  define('ADMIN_USERS', '');
?>