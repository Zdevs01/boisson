<?php
$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');

// Dynamically define base URL to work across different environments
if(!defined('base_url')) define('base_url', 'http://' . $_SERVER['HTTP_HOST'] . '/boisson/');

// Define the base application directory
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );

// Define developer data
if(!defined('dev_data')) define('dev_data',$dev_data);

// Define database connection details
if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
if(!defined('DB_NAME')) define('DB_NAME',"boisson");

?>
