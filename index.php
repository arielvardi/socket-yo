<?php 
	
	$hello = "Wanna say something?";
	$namechooser = "What's your name?";
?>

<html>
<head>
<title>Socket, yo!</title>
<link rel="stylesheet" type="text/css" href="css/style.css" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://192.168.0.105:8080/socket.io/socket.io.js"></script>
<script>

	var socket = io.connect('http://192.168.0.105:8080');
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

</body>

</html>

