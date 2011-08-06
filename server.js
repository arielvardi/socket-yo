var io = require('socket.io').listen(8080);
var connected = 0;

io.sockets.on('connection', function (socket) {
  
  var nickname = "Guest";
  connected++;
  
  socket.emit('server',{msg:'Welcome to socket.io chat. ' + connected + ' users connected.'});
   
  socket.on('message', function (data) {
	    console.log("message: " + data);
	    socket.broadcast.emit('chat', {name: nickname, chat: data});
  });
  
  socket.on('nickname', function (data) {
	    console.log("nickname: " + data.nickname);
	    nickname = data.nickname;
	    

	    updateConnectCount(socket, connected);
	    socket.broadcast.emit('server',{msg: nickname + " joined the chat."});	  	
	    
  });
  
  socket.on('disconnect', function () {
	  connected--;
	  updateConnectCount(socket, connected);
	  socket.broadcast.emit('server',{msg: nickname + " left the chat."});

  });
  
});

function updateConnectCount(socket, count){
	
  socket.broadcast.emit('count',{count: connected});
  socket.emit('count',{count: connected});

}