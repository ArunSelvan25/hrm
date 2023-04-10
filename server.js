const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
    cors: {origin:'*'}
});

io.on('connection', (socket) => {
    // socket.on('houseOwnerCreated', () => {
    //     io.sockets.emit('houseOwnerCreatedNotification');
    // });
});

app.get('/house-owner-created',async function(req,res,next){
    io.sockets.emit('houseOwnerCreatedNotification');
    console.log('emitted registered event');
    res.send('Event Triggered');
});

server.listen(3000, () => {
    console.log('Listening successful');
});
