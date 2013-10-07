<?php
session_start();
require_once '../common.php';

\System\HTML::printHead();
?>

<link rel="stylesheet" type="text/css" href="/inc/css/chat.css">
<script>
			var muted = false;
			var ws;
			function doit()
			{
				document.getElementById("chat").innerHTML += "Connecting...<br />";
				if(WebSocket)
					ws = new WebSocket("ws://<?php require_once $_SERVER['DOCUMENT_ROOT'].'/settings.php'; echo WEBSOCKET_SERVER_IP.":"; echo WEBSOCKET_PORT; ?>/");
				else
					alert("Keine Browser unterst�tzung");
				
				ws.onopen = function()
				{
					document.getElementById("chat").innerHTML += "Connectet...<br />";
					console.log("Conectet...");
					var name;
					while(typeof name != "string"){
						name = "<?php echo $_SESSION['user'];?>";
						if(name == null || name == "")
							name = "guest<?php echo rand(01,99)?>";
					}
					ws.send("name="+name);
                                        document.getElementById("msg").focus();
				};
				ws.onerror = function()
				{
					document.getElementById("chat").innerHTML += "FEEEEHLER...<br />";
					console.log("FEEEEHLER...");
				};
				ws.onmessage = function(msg)
				{
					document.getElementById("chat").innerHTML += msg.data+"<br />";
                    document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
					console.log("Mesage recived...");
					if(!muted)document.getElementById('snd').play();
				};
				ws.onclose = function()
				{
					document.getElementById("chat").innerHTML += "Closed...<br />";
					console.log("Closed...");
				};
			}
			function sendit()
			{
				if(document.getElementById("msg").value == "") 
					document.getElementById("msg").style.setProperty("background-color", "red", "important");
				else
				{                                 
					ws.send(document.getElementById("msg").value); 
					document.getElementById("msg").value = "";
				}                        
				document.getElementById("msg").focus();
				}
			function closeit()
			{
				ws.close();
			}
			document.onkeydown = function(e)
			{
			  	var key = window.event ? window.event.keyCode : e.which;
			  	if(key == 13)
				   	sendit();
			}
			function mute()
			{
				muted != muted;
			}
		</script>
<?php
#Place additional script or style or meta etc. in here
\System\HTML::printBody("");
?>
<header>
    <input type="button" onClick="sendit();" value="Senden" class="button" />
    <input type="text" id="msg" placeholder="Ihre Nachricht: " onKeyDown='this.style.setProperty("background-color", "white", "important");'>
	<input type="button" value="mute - Need a oo design @pueh" onClick="mute()" />
    <input style="float: right; margin-right: 100px; margin-top: 4px;" type="button" onClick="closeit();" value="Schliessen" class="button" />
</header>
<audio id="snd">
	<source src="..\media\bell.mp3" type="audio/mpeg">
</audio>
<div id="chat"></div>
<script type="text/javascript">
    doit();
</script>
<?php
\System\HTML::printFooter();
?>