<?php
/**
 * Description of class
 *
 * @author Florian
 */
namespace System\Security;
class Login {
    
    public function __construct()
    {        
        $DB = $GLOBALS['DB'];
        $DB->queryNoLog(
               "CREATE TABLE IF NOT EXISTS login".
              "(".
             "user varchar(20) NOT NULL,".
             "password varchar(32) NOT NULL,".
             "email varchar(50) NOT NULL,".
             "active tinyint NOT NULL,".
             "PRIMARY KEY (user)".
             ")"
             );
    }
    
    public function drawLoginForm($actionScript = '/scripts/login.php')
    {        
        if(isset($_SESSION['user']) && !empty($_SESSION['user']))
        {            
            echo "<br />Willkommen ".$_SESSION['user']."<br />";
        }
        else
        {
            echo '<form action="'.$actionScript.'" method="post">'.'<br />';
            echo '<input type="text" name="username" required placeholder="Username" autocomplete="off" />'.'<br />';
            echo '<input type="password" name="password" required placeholder="Password" autocomplete="off" />'.'<br />';
            echo '<input type="submit" value="Login" />'.'<br />';
            echo '</form>';
        }
    }
    
    public function drawRegisterForm($actionScript = "/scripts/register.php")
    {
                if(isset($_SESSION['user']) && !empty($_SESSION['user']))
        {            
            echo "<br />Willkommen ".$_SESSION['user']."<br />";
        }
        else
        {
            echo '<form action="'.$actionScript.'" method="post">'.'<br />';
            echo '<input id="regUser" type="text" name="username" required placeholder="Username" autocomplete="off" />'.'<br />';
            echo '<input id="regPwd" type="password" name="password" required placeholder="Password" autocomplete="off" />'.'<br />';
            echo '<input id="regPwdAgain" type="password" name="passwordagain" required placeholder="Password again" autocomplete="off" />'.'<br />';
            echo '<input id="regEmail" type="email" name="email" required placeholder="Email-Adresse" autocomplete="off" />'.'<br />';
            echo '<input type="submit" value="Registrieren" />'.'<br />';
            echo '</form>';
        }
    }

    public function checkLogin($username, $password)
    {
        $DB = $GLOBALS['DB'];  
        $query = "SELECT * FROM login WHERE user='".mysql_real_escape_string($username)."' AND password='".sha1((mysql_real_escape_string($password))."sha1AndSaltOfDoom44!")."'";
        $result = $DB->queryNoLog($query,true);
		if(empty($result))
        {
                return FALSE;
        }
         else 
        {
            $work = array();
            while($line = $result->fetch_array(MYSQLI_ASSOC))
            {
                array_push($work,$line);
            }
            $log = $GLOBALS['log'];
            $log($work[0]['user'] . " hatt sich eingelogt!");
            $_SESSION = array();
            session_destroy();
            session_start();
            session_regenerate_id();
            $_SESSION['user'] = $work[0]['user'];
            $_SESSION['active'] = (int)$work[0]['active'];
            $_SESSION['email'] = $work[0]['email'];
            return $work[0];
        }
    }
    
    public function addUser($username, $password, $email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false)
        {
             die("E-Mail Adresse war nicht korrekt");  
        }
        if(strpos($username, '"')!==false ||
           strpos($username, "'")!==false ||
           strpos($email, '"')!==false ||
           strpos($email, "'")!==false)
        {
            echo 'SQL-Injection try discovered!<br />';
            session_destroy();
            die("BAD LUCK, HACKER!");
        }
        $DB = $GLOBALS['DB'];  
        $query = "INSERT INTO login SET user='".mysql_real_escape_string($username)."', password='".sha1((mysql_real_escape_string($password))."sha1AndSaltOfDoom44!")."', email='".mysql_real_escape_string($email)."'";
        $result = $DB->queryNoLog($query, true);
		if($result == false)
                return FALSE;
        else 
        {
            $log = $GLOBALS['log'];
            $log($username . " hatt sich registriert!");
            session_regenerate_id();
            $_SESSION['user'] = $username;
            $_SESSION['active'] = (int)0;
            $_SESSION['email'] = $email;
            return true;
        }
    }
}
?>
