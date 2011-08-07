<?php 
	
	$hello = "Wanna say something?";
	$namechooser = "What's your name?";
?>

<html>
<head>
<title>Socket, yo!</title>
<link rel="stylesheet" type="text/css" href="css/style.css" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://localhost:8080/socket.io/socket.io.js"></script>
<script>

	var socket = io.connect('http://localhost:8080');
	var chatEnabled = false;

	socket.on('chat', function (data) {
		var nickname = data.name;
		var msg = data.chat;
		
		$("#chatroom").append("<p><span class='alias-them'>" + nickname + ":</span> " + msg + "</p>");
	});

	socket.on('server', function (data) {
			$("#chatroom").append("<p><span class='alias-server'>Server:</span> " + data.msg + "</p>");
	});

	socket.on('count', function (data) {
		$("#status").text(data.count);
	});
    
	$(document).ready(function(){
 
		$("#chat").submit(function(event){

			// user is entering his nickname
			if (!chatEnabled){
				var nickname = $("#message").val();

				socket.emit('nickname', {nickname: nickname});

				$("#message").val('');
				$("#name-chooser").hide();
				$("#nickname").text(nickname + ": ");
				$("#hello").show();
				$("#chatroom").show();
								
				chatEnabled = true;
			}
			else {
				var msg = $("#message").val();
				socket.send(msg);
				$("#chatroom").append("<p><span class='alias-you'>You:</span> " + msg + "</p>");
				$("#chatroom").scrollTop($("#chatroom")[0].scrollHeight);
				$("#message").val('');
			}
			
		});
    
	});
  
</script>
</head>

<body>

<div id="name-chooser">
<?php 
	print $namechooser;
?>
</div>


<div id="hello" style='display: none'>
<?php 
	print $hello;
?>
</div>

<form id='chat' action='javascript:return false;'>
<span id='nickname'></span> <input type='text' id="message" autocomplete="off" ></input>
</form>


<div id='chatroom' style='display: none'>
<div id='statusbar'>
<span id='status'>0</span> users connected
</div>
</div>

<div id='footer'>
By <a href='http://www.arielvardi.com'>Ariel Vardi</a> - built using <a href='http://nodejs.org/'>node.js</a>, <a href='http://socket.io/'>socket.io</a>, and <a href='http://jquery.com/'>jquery</a> - Git repo: <a href='https://github.com/arielvardi/socket-yo'>https://github.com/arielvardi/socket-yo</a>
</div>

</body>

</html>

