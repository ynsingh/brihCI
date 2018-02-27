<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "default";
$active_record = TRUE;

$db['default']['hostname'] = "127.0.0.1";
$db['default']['username'] = "xxxxxx";
$db['default']['password'] = "xxxxxx";
$db['default']['database'] = "";
$db['default']['dbdriver'] = "mysqli";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

$db['login']['hostname'] = "127.0.0.1";
$db['login']['username'] = "xxxxxx";
$db['login']['password'] = "xxxxxx";
$db['login']['database'] = "login";
$db['login']['dbdriver'] = "mysqli";
$db['login']['dbprefix'] = "";
$db['login']['pconnect'] = FALSE;
$db['login']['db_debug'] = FALSE;
$db['login']['cache_on'] = FALSE;
$db['login']['cachedir'] = "";
$db['login']['char_set'] = "utf8";
$db['login']['dbcollat'] = "utf8_general_ci";

$db['brihaspati']['hostname'] = "127.0.0.1";
$db['brihaspati']['username'] = "bgas";
$db['brihaspati']['password'] = "bgas";
$db['brihaspati']['database'] = "brihaspati";
$db['brihaspati']['dbdriver'] = "mysqli";
$db['brihaspati']['dbprefix'] = "";
$db['brihaspati']['pconnect'] = FALSE;
$db['brihaspati']['db_debug'] = FALSE;
$db['brihaspati']['cache_on'] = FALSE;
$db['brihaspati']['cachedir'] = "";
$db['brihaspati']['char_set'] = "utf8";
$db['brihaspati']['dbcollat'] = "utf8_general_ci";

$db['pl']['hostname'] = "127.0.0.1";
$db['pl']['username'] = "bgas";
$db['pl']['password'] = "bgas";
$db['pl']['database'] = "pl";
$db['pl']['dbdriver'] = "mysqli";
$db['pl']['dbprefix'] = "";
$db['pl']['pconnect'] = FALSE;
$db['pl']['db_debug'] = FALSE;
$db['pl']['cache_on'] = FALSE;
$db['pl']['cachedir'] = "";
$db['pl']['char_set'] = "utf8";
$db['pl']['dbcollat'] = "utf8_general_ci";


$db['pico']['hostname'] = "127.0.0.1";
$db['pico']['username'] = "root";
$db['pico']['password'] = "root";
$db['pico']['database'] = "pico";
$db['pico']['dbdriver'] = "mysqli";
$db['pico']['dbprefix'] = "";
$db['pico']['pconnect'] = FALSE;
$db['pico']['db_debug'] = FALSE;
$db['pico']['cache_on'] = FALSE;
$db['pico']['cachedir'] = "";
$db['pico']['char_set'] = "utf8";
$db['pico']['dbcollat'] = "utf8_general_ci";


$db['fees']['hostname'] = "127.0.0.1";
$db['fees']['username'] = "bgas";
$db['fees']['password'] = "bgas";
$db['fees']['database'] = "fees";
$db['fees']['dbdriver'] = "mysqli";
$db['fees']['dbprefix'] = "";
$db['fees']['pconnect'] = FALSE;
$db['fees']['db_debug'] = FALSE;
$db['fees']['cache_on'] = FALSE;
$db['fees']['cachedir'] = "";
$db['fees']['char_set'] = "utf8";
$db['fees']['dbcollat'] = "utf8_general_ci";


/* End of file database.php */
/* Location: ./system/application/config/database.php */
