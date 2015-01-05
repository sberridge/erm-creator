var websocketServer = require('ws').Server;

var ipaddress = process.env.OPENSHIFT_NODEJS_IP || "127.0.0.1";
var port = process.env.OPENSHIFT_NODEJS_PORT || 8000;
var connectionId = 1;
var wsServer = new websocketServer({host: ipaddress,port: port});
function randColour() {
    return "rgb("+(Math.floor(Math.random()*200))+","+(Math.floor(Math.random()*200))+","+(Math.floor(Math.random()*200))+")";
}
var User = function(connection) {
    this.connection = connection;
    this.project = false;
    this.name = "";
    this.colour = randColour();
};
var users = {};
function projectUsers(project,self) {
    self = self ? self : false;
    var returnArr = {};
    for(key in users) {
        if(self === false && users[key].project == project || self !== false && parseInt(key) !== self && users[key].project == project) {
                            
            returnArr[key] = users[key];
        }
    }
    return returnArr;
}
wsServer.on('connection',function(ws) {
    var thisUser = connectionId;
    users[connectionId] = new User(ws);
    connectionId++;
    
    ws.on('message',function(message) {
        
        var message = JSON.parse(message);
        if(message) {
            if(message.openProject) {
                if(users[thisUser].project !== false) {
                    for(key in projectUsers(users[thisUser].project,thisUser)) {            
                        users[key].connection.send(JSON.stringify({userDisconnected:true,user:thisUser}));
                    }
                }
                users[thisUser].project = message.project;
                users[thisUser].name = message.user.name;
                var connectedUsers = [];
                for(key in projectUsers(users[thisUser].project,thisUser)) {
                    
                    var user = {"id":key,"name":users[key].name,"colour":users[key].colour};
                    users[key].connection.send(JSON.stringify({newUser:true,user:{id:thisUser,name:users[thisUser].name,colour:users[thisUser].colour}}));
                    connectedUsers.push(user);
                }
               
                users[thisUser].connection.send(JSON.stringify({newConnection:true,users:connectedUsers}));
            } else if(message.newTable || message.updateTable || message.deleteTable || message.newConnector || message.deleteConnector) {
                
                for(key in projectUsers(users[thisUser].project,thisUser)) {                       
                    users[key].connection.send(JSON.stringify(message));                    
                }
            }
        }

    });
    ws.on('close',function(connection) {
        var project = users[thisUser].project;
        delete users[thisUser];
        for(key in projectUsers(project,thisUser)) {
            
            users[key].connection.send(JSON.stringify({userDisconnected:true,user:thisUser}));
        }
    });
});