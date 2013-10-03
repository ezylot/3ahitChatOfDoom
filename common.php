<?php
$start=microtime(true);
include(__DIR__.'/paths.php');

require_once PROJECT_DOCUMENT_ROOT.'/settings.php';
if(SHOW_DETAILED_TIMES)
{  
    echo "Setting loaded after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}
require_once PROJECT_DOCUMENT_ROOT.'/inc/inludeAllClasses.php';
if(SHOW_DETAILED_TIMES)
{  
    echo "All classes included after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}
#globales sessionHandler Objekt
#new \System\SessionHandler();
session_start();
if(SHOW_DETAILED_TIMES)
{  
    echo "Session started after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}

if(!isset($GLOBALS['log']))
    $log = new \System\Security\Logging\Log();
if(SHOW_DETAILED_TIMES)
{  
    echo "Log-Object created after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}

#globales DAtenbankobjekt
if(!isset($GLOBALS['DB']))
    $DB = new \System\Database\MySql (DB_SERVER, DB_USER, DB_Password, DB_NAME);
if(SHOW_DETAILED_TIMES)
{  
    echo "Database Object created after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}

#Login projekt
if(!isset($GLOBALS['login']) || $GLOBALS['login'] == NULL)
    $login = new \System\Security\Login();
if(SHOW_DETAILED_TIMES)
{  
    echo "Login Object created after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}

#Wenn die Console aktiv ist kann als zusätzlichen GET Parameter Befehle übergeben werden
#   deleteLog
#   destroySession
#   getLog=anzahlz  
#   info
#   time

#Aufrug:    www.example.com?console&deleteLog
if(isset($_GET['console']) && ENABLE_CONSOLE)
{
    define ("CONSOLE", true);
    $console = new \System\Administration\Console();
}
else
    define ("CONSOLE", false);
if(SHOW_DETAILED_TIMES)
{  
    echo "Checked for console after: ".  round((microtime(true)-$start)*1000,2)." ms<br />" . PHP_EOL;
}
function runStatistic()
{
    if(!isset($GLOBALS['statistic']) || $GLOBALS['statistic'] == NULL)
        $statistic = new \System\Statistics\Statistic();
    if(SHOW_DETAILED_TIMES)
    {  
    echo "Statistic object created after: ".  round((microtime(true)-$GLOBALS['start'])*1000,2)." ms<br />" . PHP_EOL;
    }
    $statistic->takeShot();
    if(SHOW_DETAILED_TIMES)
    {  
        echo "Statisic Snapshoot taken after: ".  round((microtime(true)-$GLOBALS['start'])*1000,2)." ms<br />" . PHP_EOL;
    }
 }
 (ACTIVATE_STATISTIC)?runStatistic():"";
?>
        