<?php    

require_once '../common.php';

if(!isset($_POST['username']) || empty($_POST['username']))
    die($_POST['username']."Username angeben");
if(!isset($_POST['password']) || empty($_POST['password']))
    die("Passwort angeben");
if(!isset($_POST['passwordagain']) || empty($_POST['passwordagain']))
    die("Passwort-Wiederhohlung eingeben");
if(!isset($_POST['email']) || empty($_POST['email']))
    die("Email angeben");
if($_POST['passwordagain'] != $_POST['password'])
    die($_POST['passwordagain'].$_POST['password']."Passwörter stimmen nicht überein");

if($GLOBALS['login']->addUser($_POST['username'], $_POST['password'], $_POST['email']))
    echo "Erfolgreich Registriert";
else
    echo "Fehler beim Registrieren";
?>
