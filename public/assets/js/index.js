const conn = new WebSocket('ws://localhost:8090');
const button = $('#sender');
const text = $('#text');
const chat = $('#chat');
const roomId = $('#room');
const meId = $('#me');
const initer = $('#initer');
const msg = {roomId: '', type: '', participantId: ''};
conn.onopen = function (e) {

};
initer.on('click', (e) => {
    msg.type = 'init';
    msg.roomId = roomId.val();
    conn.send(JSON.stringify(msg));

});
button.on('click', (e) => {
    msg.text = text.val();
    const sendMessage = {
        type: 'message',
        text: text.val()
    };
    conn.send(JSON.stringify(sendMessage));
});
conn.onmessage = function (e) {
    let getMsg = JSON.parse(e.data);
    console.log(getMsg);
    switch (getMsg.type) {
        case 'init':
            msg.id = getMsg.id;
            getMsg.data.map(x => {
                if (x.participantId === msg.id) {
                    chat.append(`<li style="color:green;"><b>${new Date(x.date).toLocaleTimeString()}</b> <i>${x.name}</i> ${x.text}</li>`)
                } else {
                    chat.append(`<li style="color:red;"><b>${new Date(x.date).toLocaleTimeString()}</b> <i>${x.name}</i> ${x.text}</li>`)
                }
            });
            break;
        case 'message':
            if (getMsg.message.participantId === msg.id) {
                chat.append(`<li style="color:green;"><b>${new Date(getMsg.message.date).toLocaleTimeString()}</b> <i>${getMsg.message.name}</i> ${getMsg.message.text}</li>`)
            } else {
                chat.append(`<li style="color:red;"><b>${new Date(getMsg.message.date).toLocaleTimeString()}</b> <i>${getMsg.message.name}</i> ${getMsg.message.text}</li>`)
            }
            break;
    }

};
conn.onclose = event => {

    console.log(event);
};


function date(dateaa = '') {
    if (dateaa !== '') {
        let year = dateaa.substr(0, 4);
        let month = dateaa.substr(4, 2);
        let day = dateaa.substr(6, 2);
        let hour = dateaa.substr(8, 2);
        let minute = dateaa.substr(10, 2);
        let secodns = dateaa.substr(12, 2);
    }
    var date = new Date(2014, 11, 31, 12, 30, 0);

    var options = {
        era: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long',
        timezone: 'UTC',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
    };

    alert(date.toLocaleString("ru", options)); // среда, 31 декабря 2014 г. н.э. 12:30:00
}


Date.prototype.today = function () {
    return ((this.getDate() < 10) ? "0" : "") + this.getDate() + "/" + (((this.getMonth() + 1) < 10) ? "0" : "") + (this.getMonth() + 1) + "/" + this.getFullYear();
};

