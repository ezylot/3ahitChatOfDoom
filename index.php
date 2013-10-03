<?php
require_once 'common.php';

\System\HTML::printHead();

#Place additional script or style or meta etc. in here
\System\HTML::printBody("");
//Place your body text here

//Draws a Login Formular
//
$login->drawLoginForm("scripts/login.php");
#
//Draws a refister Form
//
$login->drawRegisterForm("scripts/register.php");

echo '<p id="try">This is just some text to show that it is in front of the tooltip.</p>'.
      '<p>Another Text.. I don\'t know what I should write</p>'.
      '<p>The third text</p>'.
      '<a href="" title="This is some info for our tooltip." class="tooltip">Tooltip</a>';

echo '<br /><input type="submit" class="awsome-button" value="Senden" />';
echo '<div id="time"></div>';

echo '<br /><br /><br /><a href="/HTML/websocket.php" class="tooltip" title="Hier gehts zum Chat">Chat</a>';
System\HTML::printFooter();
?>