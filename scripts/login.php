<?php
header('refresh:2;url=/index.php');
require_once '../common.php';

function check()
{
    if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']))
    {   
        return $GLOBALS['login']->checkLogin($_POST['username'], $_POST['password']);
    }
    else
    {
        return false;
    }            
}
if(isset($_SESSION['user']) && !empty($_SESSION['user']))
{
    echo "Bereits angemeldet!<br />";
}
else
{
    if(!check())
    {
        echo "Fehler beim einloggen!<br />";
    }
    else
    {
        echo "Eingeloggt<br />";
    }
}
?>
