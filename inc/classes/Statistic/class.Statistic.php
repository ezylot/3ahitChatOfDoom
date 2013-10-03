<?php

namespace System\Statistics;
class Statistic
{    
    public function __construct() {
        $DB = $GLOBALS['DB'];
        $DB->queryNoLog(
              "CREATE TABLE IF NOT EXISTS statistic".
              "(".
              "ip varchar(15) NOT NULL,".
              "browser varchar(32) NOT NULL,".
              "version varchar(30) NOT NULL,".
              "OS varchar(30) NOT NULL,".
              "javascript tinyint NOT NULL,".
              "timeMake DATETIME NOT NULL,".
              "PRIMARY KEY (ip)".
              ")"
               );
    }
    
    public function takeShot()
    { 
        $DB = $GLOBALS['DB'];      
        $ip = self::getRealIP();
        $result = $DB->query($select);
        $dbdate = strtotime($result[0]['timeMake']);
        $difference = (time()-$dbdate);
        if(($difference/3600 >6) || (!isset($_SESSION['statistic']) && $_SESSION['statistic'] != true))
        {            
            $browserAndOS = self::getBrowserAndOS();
            $browser = $browserAndOS['name'];
            $version = $browserAndOS['version'];
            $os = $browserAndOS['platform'];
            $query = "INSERT INTO statistic SET timeMake=NOW(),".
                        "ip='".addslashes($ip)."',".
                        "browser='".addslashes($browser)."',".
                        "version='".  addslashes($version)."',".
                        "os='".addslashes($os)."'".
                        ";";
            $DB->queryNoLog($query);
            $_SESSION['statistic'] = true;
        }
    }
    
    public static function getRealIP()
    {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])!= "unknown")
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (isset($_SERVER['HTTP_CLIENT_IP'])) 
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $realip=$_SERVER['REMOTE_ADDR']; 
        return $realip;
    }
    
    public static function getBrowserAndOS()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform
        );
    }
}


?>