<?php
#If you set this to true, the content will be hidden for Internet Explorer :D
#Just to be sure
define('DISABLE_INERNET_EXPLORER', false);

#Fehlerreport
error_reporting(E_ERROR);
#error_reporting(E_ALL);

require_once 'plugin_activation.php';

define('DB_SERVER', "127.0.0.1");
define('DB_USER',"root");
define('DB_Password',"");
define('DB_NAME',"testac");

#Here start the configuration for the Real-Time-Chat.. aka websocket-chat
define('WEBSOCKET_PORT', 8023);
define('WEBSOCKET_MASTER_PASSWORD', "123456789");
define('WEBSOCKET_SERVER_IP', "127.0.0.1");
define('MAX_CLIENTS', 3);

#Zeigt detailierte Zeiten wenn der GETparameter time gesetzt ist
if(isset($_GET['time']))
    define('SHOW_DETAILED_TIMES', TRUE);
else
    define('SHOW_DETAILED_TIMES', FALSE);

#Beim Login überprüfen ob eine email-verifizierung besteht
#1 ist ausgeschaltet
#define('EMAIL_VERIFICATION', true);

define('HTML_TITLE', "Das ist ein Titel");
date_default_timezone_set('Europe/Berlin');
?>