<?php

/**
 * Description of class
 *
 * @author Florian
 */
namespace System\Security\Logging;
class Log {

    private static $logfile;
    private static $fileHandle;
    private static $chatHandle;

    public function __construct() {
        self::$logfile = PROJECT_DOCUMENT_ROOT.'/log/'.\date('d_m_Y', \time()).'.log';
    }
    public function __destruct()
    {        
        if(self::$fileHandle !== false){
            \fclose(self::$fileHandle);
        }
        if(self::$chatHandle !== false)
            \fclose(self::$chatHandle);
        
    }
    public function __invoke($message, $chat = false) {
        if($chat == true)
        {           
            $chatlog = PROJECT_DOCUMENT_ROOT.'/log/chat/'.\date('d_m_Y', \time()).'.log';
            if(!self::$chatHandle) self::$chatHandle = @fopen($chatlog, "a+");
            $string = \date("d-m-Y H:i:s", \time()) . "\t" .$message . "\r\n";
            \fwrite(self::$chatHandle, $string);
        }
        else
        {
            if(!self::$fileHandle)self::$fileHandle = @fopen(self::$logfile, "a+");
            $string = \date("d-m-Y H:i:s", \time()) . " - " . $_SERVER['SCRIPT_FILENAME'] . "\r\n\t" .$message . "\r\n";
            \fwrite(self::$fileHandle, $string);
        }
            
    }
    public static function getLog($count = 20)
    {
        if(!self::$fileHandle)self::$fileHandle = @fopen(self::$logfile, "a+");  
        $entries=\file(self::$logfile);
        
        $displayedmess = 0;
        $return = array();
        for($i = \count($entries); $i > 0; $i--)
        {
            if($displayedmess > $count)
                return true;
            if(!isset($entries[$i-1]))
                return true;
                
            \array_unshift($return, $entries[$i-1]);
         }
         return $return;
    }
    public static function deleteLogfile()
    {
        if(self::$fileHandle)fclose(self::$fileHandle);        
        \unlink(self::$logfile);
        self::$fileHandle = false;
    }
}
?>
