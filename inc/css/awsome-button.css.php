<?php
header("Content-type: text/css");
require '../../settings.php';
echo ('@charset "UTF-8";'.PHP_EOL);
?>
/*
To make a awesome button, just give the button the ID 'awsome-button'
For example you can make a submit button:
<input type="submit" id="awsome-button" />
*/

input[class=awsome-button] <?php echo (ENABLE_SUBMIT_DESIGN)?", input[type=submit]":""; ?> {
	display: inline;
	font-size: 105%;
	width: auto;
	background:#62af56;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#76BF6B), to(#3B8230));
	background-image: -webkit-linear-gradient(top, #76BF6B, #3B8230);
	background-image: -moz-linear-gradient(top, #76BF6B, #3B8230);
	border-color: #2d6324;
	color: #fff;
	text-shadow: rgba(0, 0, 0, 0.5) 0px -1px 0px;
	padding: 8px 10px;
	border-radius:8px;
        margin: 1px 0px;
}
input[class=awsome-button]:hover <?php echo (ENABLE_SUBMIT_DESIGN)?", input[type=submit]:hover":""; ?> {
	background: #5BA150;
}


input[class=awsome-button]:active <?php echo (ENABLE_SUBMIT_DESIGN)?", input[type=submit]:active":""; ?>
{
        background: #eee;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dfdfdf), to(#eee));
	background-image: -webkit-linear-gradient(top, #dfdfdf, #eee);
	background-image: -moz-linear-gradient(top, #dfdfdf, #eee);
	background-image: -ms-linear-gradient(top, #dfdfdf, #eee);
	background-image: -o-linear-gradient(top, #dfdfdf, #eee);
	background-image: linear-gradient(top, #dfdfdf, #eee);
	text-shadow: #eee 0px 1px 0px;
	-moz-box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.3);
	-webkit-box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.3);
	box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.3);
	border-color: #aaa;
	text-decoration: none;
}