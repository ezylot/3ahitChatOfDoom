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
define('DB_Password',"123456");
define('DB_NAME',"testac");

#Here start the configuration for the Real-Time-Chat.. aka websocket-chat
define('WEBSOCKET_PORT', 17033);
define('WEBSOCKET_MASTER_PASSWORD', "1234567890");
define('WEBSOCKET_SERVER_IP', "192.168.7.69");
define('MAX_CLIENTS', 30);

//Max Message Length which can be send in the chat
define('MAM_MESSAGE_LENGTH', 512);

#Zeigt detailierte Zeiten wenn der GETparameter time gesetzt ist
if(isset($_GET['time']))
    define('SHOW_DETAILED_TIMES', TRUE);
else
    define('SHOW_DETAILED_TIMES', FALSE);

#Beim Login überprüfen ob eine email-verifizierung besteht
#1 ist ausgeschaltet
#define('EMAIL_VERIFICATION', true);

define('HTML_TITLE', "Welcome to the CheatClub");
date_default_timezone_set('Europe/Berlin');
?>