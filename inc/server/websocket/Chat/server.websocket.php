<?php  /*  >php -q server.php  */ 

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$masterpw = "";
$mastersocket = "";
$master  = WebSocket(WEBSOCKET_SERVER_IP ,WEBSOCKET_PORT);
$sockets = array($master);
$users   = array();
$debug   = false;
$maxLen = MAM_MESSAGE_LENGTH;
$roomLimit = MAX_CLIENTS;

$help = "Welcome to the Chat.\n You can access the following commands:<br />\t/help<br />\t/list<br />\t/sudo &lt;adminpassword&gt;";
$helpadmin = "Welcome to the Chat.\n You can access the following commands:<br />\t/help<br />\t/sudo &lt;adminpassword&gt;<br />\t/list<br />\t/kick &lt;id&gt;<br />\t/name &lt;yournewname&gt;".
			 "<br />\t/rename &lt;id&gt; &lt;idsnewname&gt;<br />\t/setMaxLen &lt;length&gt; \/\/Set the maximal amount of characters per message<br />\t/setRoomLimit &lt;newSize&gt; ".
			 "\/\/Set the limit of connected clients";

while(true){

  $changed = $sockets;
  @socket_select($changed,$write=NULL,$except=NULL,NULL);
  foreach($changed as $socket){
    if($socket==$master){
      $client=socket_accept($master);
      if($client<0){ console("socket_accept() failed"); continue; }
      else{ connect($client); }
    }
    else{
      $bytes = @socket_recv($socket,$buffer,2048,0);
      if($bytes==0){ disconnect($socket); }
      else{
        $user = getuserbysocket($socket);
        if(!$user->handshake){dohandshake($user,$buffer); }
        else{ process($user,$buffer); }
      }
    }
  }
}

//---------------------------------------------------------------
function process($user,$msg){
  global $masterpw;
  global $mastersocket;
  global $maxLen;
  global $roomLimit;
  $value = unpack('H*', $msg[0]);
  $opcode =  base_convert($value[1], 16, 10);
  if($opcode == 136 ||$opcode == 8) return disconnect($user->socket);
  $action = decode($msg);
  if(strlen($action) > $maxLen) return send($user->socket, "<span class='error'>Your entered text was too long</span>");
  
  if(preg_match("/^\/rename #?(\d{2,4}) (.*)/",$action,$match)){ $idtorename=$match[1]; $newname=$match[2]; }
  if(isset($idtorename) && isset($newname))
	{
        if($user->id != $mastersocket) return send($user->socket,"<span class='error'>You tried to accsess to an command you aren't allowed to use!</span>");
		else
		{
            $usertorename = getuserbyresource($idtorename);
            if ($usertorename == false)
            {
                return send($user->socket,"Recource ID not found");
            }
            else
            {
				send($usertorename->socket,"<span class='info'>You were renamed by the admin</span>");
                $log = $GLOBALS['log'];
                $log("User " . $usertorename->name ." was renamed by the amdin to ".$newname, true);
				sendall($user->socket, "<span class='info'>User " . $usertorename->name ." was renamed by the amdin to ".$newname . "</span>");				
				
				return $usertorename->name = $newname;
            }
        }
  }
  
  if(preg_match("/^\/?name[=\s](.*)/",$action,$match)){ $name=$match[1]; }
  if(isset($name) && ($user->name == "" || $user->name == null || empty($user->name) || $user->id== $mastersocket))
  {
	if(empty($name))
		return disconnect($user->socket);
	else
	{
        $user->name = $name;
        return renameuser($user);
	}
  }
    if(preg_match("/\/sudo (.*)/",$action,$match)){ $pw=$match[1]; }
	if(isset($pw))
	{	
  		if($pw == $masterpw)
       {
            $mastersocket = $user->id;
            $user->name="[ADMIN]";
            return renameuser($user);                    
		} else if($pw == "")
		{
			return send($user->socket, "<span class='error'>False Sytax. Use /sudo <masterpassword></span>");
		}
		else
		{
			return send($user->socket, "<span class='error'>You entered the wrong password</span>");
		}
	}
	
	if(preg_match("/^\/setRoomLimit[=\s](\d{1,3})/",$action,$match)){ $newsize=$match[1]; }
	if(isset($newsize))
	{
        if($user->id != $mastersocket) return send($user->socket,"<span class='error'>You tried to accsess to an command you aren't allowed to use!</span>");
		if(empty($newsize))
			return send($user->socket,"<span class='error'>No RoomSize specified</span>");
		else
		{
                    $roomLimit = $newsize;
                    say("Room limit set to ".$roomLimit);
					return;
		}
	}

	if(preg_match("/^\/setMaxLen[=\s](\d{1,4})/",$action,$match)){ $len=$match[1]; }
	if(isset($len))
	{
        if($user->id != $mastersocket) return send($user->socket,"<span class='error'>You tried to accsess to an command you aren't allowed to use!</span>");
		if(empty($len))
			return send($user->socket,"<span class='error'>No lenght specified</span>");
		else
		{
                    return $maxLen = $len;
		}
	}
  
  
  if(preg_match("/^\/kick[=\s](#(\d{2,4})|(\d{2,4})|(.*))/",$action,$match)){ $kick=$match[1]; }
  if(isset($kick))
  {
        if($user->id != $mastersocket) return send($user->socket,"<span class='error'>You tried to accsess to an command you aren't allowed to use!</span>");
	if(empty($kick))
		return send($user->socket,"<span class='error'>Recource ID empty</span>");
	else
	{
            if(intval($match[1]) != 0)
                $kick = $match[1];
            elseif (intval($match[2]) != 0) {
                $kick = $match[2];
            }
            else
            {
                return send($user->socket,"<span class='error'>Recource ID not numerical</span>");
            }
            $usertokick = getuserbyresource($kick);
            if ($usertokick == false)
            {
                return send($user->socket,"<span class='error'>Recource ID not found</span>");
            }
            else
            {
				send($usertokick->socket,"<span class='error'>You got kicked by the admin</span>");
                                $log = $GLOBALS['log'];
                                $log("User " . $usertokick->name ."(". $kick. ") got kicked ", true);                                
				disconnect($usertokick->socket);
				return sendall($user->socket, "<span class='info'>User " . $usertokick->name ."(". $kick. ") got kicked</span>");
            }
        }
  }
  //Here comes the /list command, which lists all the users connectet
  //It sends for each a id=>name pair
  if(preg_match("/^\/list/",$action) == 1){
        global $users;
        $n=count($users);
        $list = "<div class='userlist'>";
        
        for($i=0;$i<$n;$i++){
            preg_match("/(\d{2,4})/",(string)$users[$i]->socket, $match);
            $list .= $match[1] . "=" . $users[$i]->name . "<br>";
        }  
        return send($user->socket, $list . "</div>");
  }
  
    if(preg_match("/^\/help/",$action) == 1) {	
	global $help;
        global $helpadmin;
	if($user->id == $mastersocket) 
            return send($user->socket, $helpadmin);
        return send($user->socket, $help);
  }
    if(preg_match("/^\/rename #?(\d){2,3} (.*)/",$action,$match)){ $idtorename=$match[0]; $newname=$match[1]; }
	if(isset($idtorename) && isset($newname))
	{
        if($user->id != $mastersocket) return send($user->socket,"<span class='error'>You tried to accsess to an command you aren't allowed to use!</span>");
		else
		{
            $usertorename = getuserbyresource($idtorename);
            if ($usertorename == false)
            {
                return send($user->socket,"<span class='error'>Recource ID not found</span>");
            }
            else
            {
				send($usertorename->socket,"<span class='error'>You were renamed by the admin</span>");
                $log = $GLOBALS['log'];
                $log("User " . $usertokick->name ."(". $kick. ") was renamed by the amdin to ".$newname, true);                                
				$usertokick->name = $newname;
				return sendall($user->socket, "<span class='info'>User " . $usertokick->name ."(". $kick. ") was renamed by the amdin to ".$newname . "</span>");
            }
        }
  }
  
  say($user->name.": ".$action);
  switch($action){
        case ""  : send($user->socket,"<span class='error'><b>No message recived</b></span>");  break;
    default      : sendall($user->socket,$action);   			  break;
  }
}

function send($client,$msg){
  $msg = wrap("<span class='date'>".date("H:i:s", time())."</span> ".$msg);
  socket_write($client,$msg,strlen(($msg)));
}

function sendall($source,$msg){
  global $sockets,$users;
  $n=count($users);
  if($source != NULL){
  $nachr = "<span class='user'>".getuserbysocket($source)->name . "</span>: ".$msg;
  $log = $GLOBALS['log'];
  $log(getuserbysocket($source)->name . " : " . $msg , true);
  } else { $nachr = $msg; }
  for($i=0;$i<$n;$i++){
    send($users[$i]->socket, $nachr);
  }  
}
function renameuser($user){
  global $sockets,$users;
  $n=count($users);
  $nachr = "<span class='info'>".$user->socket . " renamed himself to <span class='user'>". $user->name."</class></class>";
  say($user->socket . " RENAMED TO ".$user->name);
  //$log($user->socket . " RENAMED TO ".$user->name, true);
  for($i=0;$i<$n;$i++){
    send($users[$i]->socket, $nachr);
  }  
}
function WebSocket($address,$port){
    global $masterpw;
    if(WEBSOCKET_MASTER_PASSWORD == "")
    {
        $masterpw=sha1(md5(rand(0, 10000)));
    }
    else
    {
        $masterpw = WEBSOCKET_MASTER_PASSWORD;
    }   
  $master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)     or die("socket_create() failed");
  socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1)  or die("socket_option() failed");
  socket_bind($master, $address, $port)                    or die("socket_bind() failed");
  socket_listen($master,20)                                or die("socket_listen() failed");
  say(PHP_EOL . "Server Started : ".date('Y-m-d H:i:s'));
  say("Master socket  : ".$master);
  say("Listening on   : ".$address." port ".$port);
  return $master;
}

function connect($socket){
  global $sockets,$users;
  $user = new User();
  $user->id = uniqid();
  $user->socket = $socket;
  $user->ip = getRealIP();
  array_push($users,$user);
  array_push($sockets,$socket);
  //$log($socket." CONNECTED!", true);  
}

function disconnect($socket, $nomessage = false){
  global $sockets,$users;
  $found=null;
  $n=count($users);
  for($i=0;$i<$n;$i++){
    if($users[$i]->socket==$socket){ $found=$i; break; }
  }
  if(!$nomessage) { 
	say(getuserbysocket($socket)->name." disconnected!");
	sendall(NULL, getuserbysocket($socket)->name." disconnected!");
  }
  if(!is_null($found)){ array_splice($users,$found,1); }
  $index = array_search($socket,$sockets);
  socket_close($socket);
  if($index>=0){ array_splice($sockets,$index,1); }
}

function dohandshake($user,$buffer){
  global $users;
  global $roomLimit;
  console("\nRequesting handshake...");
  list($resource,$host,$origin,$strkey,$data) = getheaders($buffer);
  console("Handshaking...");

    $accept_key = $strkey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
    $accept_key = sha1($accept_key, true);
    $accept_key = base64_encode($accept_key);

  $upgrade  = "HTTP/1.1 101 Switching Protocols\r\n" .
              "Upgrade: WebSocket\r\n" .
              "Connection: Upgrade\r\n" .
			  "Sec-WebSocket-Accept: ". $accept_key . "\r\n" .
              "Sec-WebSocket-Origin: " . $origin . "\r\n" .
              "Sec-WebSocket-Location: ws://" . $host . $resource . "\r\n\r\n";
  socket_write($user->socket,$upgrade,strlen($upgrade));
  $user->handshake=true;
  console($upgrade);
  console("Done handshaking...");
  	if(count($users) > $roomLimit || count($users) > 128)
	{
        say("Server Room Full");
		$tofull = "You could not connect, because the room is full(".(count($users)-1)."/".($roomLimit).")";
		var_dump($roomLimit,count($users) );
		send($user->socket,$tofull); 
		return disconnect($user->socket, true);
	}
  return true;
}

function getheaders($req){
  $r=$h=$o=null;
  if(preg_match("/GET (.*) HTTP/"   ,$req,$match)){ $r=$match[1]; }
  if(preg_match("/Host: (.*)\r\n/"  ,$req,$match)){ $h=$match[1]; }
  if(preg_match("/Origin: (.*)\r\n/",$req,$match)){ $o=$match[1]; }
  if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/",$req,$match)){ $key=$match[1]; }
  if(preg_match("/\r\n(.*?)\$/",$req,$match)){ $data=$match[1]; }
  return array($r,$h,$o,$key,$data);
}

function getuserbyresource($id)
{
    global $users;
    $match = "";
    foreach($users as $user){
        if(preg_match("/(\d{2,4})/",(string)$user->socket, $match)){ $r=$match[1]; }
        if(intval($r) == intval($id)) { return $user; } 
    }
    return false;
}

function getuserbysocket($socket){
  global $users;
  $found=null;
  foreach($users as $user){
    if($user->socket==$socket){ $found=$user; break; }
  }
  return $found;
}
function decode($msg=""){

  	$value = unpack('H*', $msg[1]);
	$datalength =  base_convert($value[1], 16, 10) & 127;
	
	$maskstart = 2;
	if($datalength == 126)
		$maskstart = 4;
	if($datalength == 127)
		$maskstart = 10;

	$mask = array(	$msg[$maskstart + 0],
					$msg[$maskstart + 1],
					$msg[$maskstart + 2],
					$msg[$maskstart + 3]);
	for($a = 0; $a < 4; $a++)
	{		
		$value = unpack('H*', $mask[$a]);
		$mask[$a] = base_convert($value[1], 16, 10);
	}
	$i = $maskstart + 4;
	$index = 0;
	$output = "";
	$curr ="";
	if($i == strlen($msg))
	{
		console("No message recived");
		return "";
	}
	while($i < strlen($msg))
	{
		$curr = $msg[$i++];
		$value = unpack('H*', $curr);
		$curr =  base_convert($value[1], 16, 10);	
		$rdy = chr((int)$curr ^ (int)$mask[$index++ % 4]);		
		$output = $output . htmlentities($rdy);
	}
	return $output;
}

function wrap($msg=""){ 
	$formated = array();
	$formated[0] = chr(129);
	if(strlen($msg) <= 125){
		$formated[1] = chr(strlen($msg));
	} else if(strlen($msg) >= 126 && strlen($msg) <= 65535) {
		$formated[1] = chr(126);
		$formated[2] = chr((strlen($msg) >> 8) & 255);
		$formated[3] = chr((strlen($msg)     ) & 255);
	} else {
		$formated[1] = chr(127);
		$formated[2] = chr((strlen($msg) >> 56) & 255);
		$formated[3] = chr((strlen($msg) >> 48) & 255);
		$formated[4] = chr((strlen($msg) >> 40) & 255);
		$formated[5] = chr((strlen($msg) >> 32) & 255);
		$formated[6] = chr((strlen($msg) >> 24) & 255);
		$formated[7] = chr((strlen($msg) >> 16) & 255);
		$formated[8] = chr((strlen($msg) >>  8) & 255);
		$formated[9] = chr((strlen($msg)      ) & 255);
	}
	
	for ($it = 0; $it < strlen($msg); $it++){
		array_push($formated, $msg[$it]);
	}
	
	$ret = $formated[0];
	if(strlen($msg) <= 125){
		$ret .= $formated[1];
		$ret .= implode(array_slice($formated, 2));
	} else if(strlen($msg) >= 126 && strlen($msg) <= 65535) {
		$ret .= $formated[1];
		$ret .= $formated[2];
		$ret .= $formated[3];
		$ret .= implode(array_slice($formated, 4));
	} else {
		$ret .= $formated[1];
		$ret .= $formated[2];
		$ret .= $formated[3];
		$ret .= $formated[4];
		$ret .= $formated[5];
		$ret .= $formated[6];
		$ret .= $formated[7];
		$ret .= $formated[8];
		$ret .= $formated[9];
		$ret .= implode(array_slice($formated, 10));
	}
	return $ret;
}

function     say($msg=""){ echo $msg."\n"; }
function console($msg=""){ global $debug; if($debug){ echo $msg."\n"; } }

class User{
  var $id;
  var $socket;
  var $handshake;
  var $name;
  
  //ipadresse für z.B. Rechte verteilung aufgrund der ips
  var $ip;
}

function getRealIP()
{
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])!= "unknown")
        $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif (isset($_SERVER['HTTP_CLIENT_IP'])) 
        $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else
        $realip=$_SERVER['REMOTE_ADDR']; 
    return $realip;
}
?>