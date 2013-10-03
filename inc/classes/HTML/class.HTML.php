<?php

/**
 * Description of class
 *
 * @author Florian
 */

namespace System;
class HTML {
    
    public static function printHead()
    {
        header('Content-Type: text/html; charset=UTF-8');
        
        echo '<!DOCTYPE html>'.PHP_EOL;
        echo '<html lang="de">'.PHP_EOL;
        echo '<head>'.PHP_EOL;
        echo '<title>'.HTML_TITLE.'</title>'.PHP_EOL;
        echo '<meta charset="utf-8">'.PHP_EOL;        
        #makes IE Versions 5.5 to 8 look at last a little bit cool... FU IE!!
        if(DISABLE_INERNET_EXPLORER)
        {
            echo '  <script type="text/javascript">
                    if (/*@cc_on!@*/false && document.documentMode === 10) {
                        document.write("<link rel=\"stylesheet\" type=\"text/css\" href=\"'.PROJECT_HTTP_ROOT.'/inc/css/Disabled_IE.css\" />");
                    }
                    </script>';
            echo '<!--[if IE]>'.PHP_EOL;
            echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/Disabled_IE.css" type="text/css" rel="stylesheet"/>'.PHP_EOL;
                echo '<![endif]-->'.PHP_EOL;
        }
        else
        {
            echo '<!--[if lt IE 9]>'.PHP_EOL;
            echo '<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>'.PHP_EOL;
            echo '<![endif]-->'.PHP_EOL.PHP_EOL;
        }
        #Includes JQuery script version 1.9.1
        echo '<script src="/inc/js/jquery-1.9.1.min.js"></script>'.PHP_EOL.PHP_EOL;
        
        echo '<link rel="stylesheet" type="text/css" href="/inc/css/default.css.php" />'.PHP_EOL;
		echo '<link rel="stylesheet" type="text/css" href="/inc/css/style.css" />'.PHP_EOL;
        echo '<script src="/inc/js/default.js" type="text/javascript"></script>'.PHP_EOL.PHP_EOL;
    }
    
    public static function printBody($bodyAttributes = NULL)
    {
        echo '</head>'.PHP_EOL;
        echo '<body ';
        if($bodyAttributes != NULL)
        {
            echo $bodyAttributes;
        }
        echo '>'.PHP_EOL;
    }
    
    public static function printFooter()
    {
        $stop = microtime(true);
        if(SHOW_BUILD_TIME)
        {
            echo '<p style="font-size:75%;">Website was built in: <b>'. round(($stop-$GLOBALS['start'])*1000,2) . "</b> milliseconds</p>";
        }
        echo '</body>'.PHP_EOL;
        echo '</html>';
    }
}
?>
