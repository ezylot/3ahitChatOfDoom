<?php
/**
 * Description of class
 *
 * @author Florian
 */
namespace System\Administration;
class Console {
    
    public function __construct() {
        $DB = $GLOBALS['DB'];
        $log = $GLOBALS['log'];
        if(CONSOLE)
        {
            if(isset($_GET['destroySession']))
            {
                session_destroy();
                echo '<b>Session destroyed</b><br />';
                $log("Session wurde über die Konsole zerstört");
            }
            if(isset($_GET['getLog']))
            {
                if(!empty($_GET['getLog']))
                    $logs = $log->getLog($_GET['getLog']);
                else
                    $logs = $log->getLog();
                echo "-----------------------<b>LOGS START</b>-----------------------<br />";
                for($i = 0; $i < count($logs); $i++)
                {
                    echo(htmlentities($logs[$i])."<br />");
                }
                echo "-----------------------<b>LOGS ENDE</b>------------------------<br />";
                $log("Logs wurden  über die Konsole gezeigt");
            }            
            if(isset($_GET['deleteLog']))
            {
                $log->deleteLogfile();
                echo '<b>Log deleted</b><br />';
                $log = new \System\Security\Logging\Log();
                if (! isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $client_ip = $_SERVER['REMOTE_ADDR'];
                }
                else {
                    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                $log("Log wurden von ".$client_ip." über die Konsole gelöscht");
            }
            if(isset($_GET['info']))
            {
                echo ('---------- <b>INFO</b> ----------'.'<br \>');
                echo 'J-Query: 1.9.1-min'.'<br \>';
                echo 'IE-Hack: 2.1(beta4)/IE9.js'.'<br \>';
                echo 'MySQL-error (0 is OK) = '. mysqli_connect_errno() .'<br />';
                echo 'Error-Reporting is '. error_reporting().'<br \>';
                echo 'MySQL-Last-Query: ' . $DB->lastQuery .'<br \>';
                echo 'MySQL-Last-Query-Satus: ' . $DB->lastSQLState .'<br \>';
                echo 'HTML-Title: '.HTML_TITLE.'<br \>';
                echo 'Timezone: '. date_default_timezone_get().'<br \><br />';
            }
            
        }
    }
}
?>
