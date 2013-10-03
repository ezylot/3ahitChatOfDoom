<?php
header("Content-type: text/css");
require '../../settings.php';
echo ('@charset "UTF-8";'.PHP_EOL);
echo ('@import url("norm.css");'.PHP_EOL);
echo ('@import url("tooltip.css");'.PHP_EOL);
echo ('@import url("awsome-button.css.php");'.PHP_EOL);

if(DISABLE_INERNET_EXPLORER)
{
    echo '/* Lets diable the fcking IE */'. PHP_EOL;
    echo '* html { display: none; }';
}
?>
