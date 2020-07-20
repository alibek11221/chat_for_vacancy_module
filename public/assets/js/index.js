const conn = new WebSocket('ws://localhost:8090');
const button = $('#sender');
const text = $('#text');
const chat = $('#chat');
const roomId = $('#room');
const meId = $('#me');
const initer = $('#initer');
let token;
const msg = {roomId: '', type: '', participantId: ''};
const messages = window.localStorage.getItem('messages') === null ? [] : JSON.parse(localStorage.getItem('messages'));
let messageCache = messages.length ? messages : [];
conn.onopen = function (e) {

};
initer.on('click', (e) => {
    msg.type = 'init';
    msg.roomId = roomId.val();
    msg.participantId = meId.val();
    conn.send(JSON.stringify(msg));

});
button.on('click', (e) => {
    msg.type = 'message';
    msg.text = text.val();
    msg.data = new Date();
    conn.send(JSON.stringify(msg));
});
conn.onmessage = function (e) {
    let getMsg = JSON.parse(e.data);
    switch (getMsg.type) {
        case 'init':
            token = getMsg.token;
            if (messages.token !== token) {
                msg.type = 'getmessages';
                conn.send(JSON.stringify(msg));
            } else {
                messages.map(x => {
                    if (x.participant_id === msg.participantId) {
                        chat.append(`<li style="color:green;"><b>${rendertime(new Date(Date.parse(x.message_date)))}</b> ${x.text}</li>`)
                    } else {
                        chat.append(`<li style="color:red;"><b>${rendertime(new Date(Date.parse(x.message_date)))}</b> ${x.text}</li>`)
                    }
                });
            }
            break;
        case 'message':
            if (getMsg.participantId === msg.participantId) {
                chat.append(`<li style="color:green;"><b>${rendertime(new Date(Date.parse(getMsg.data)))}</b>${getMsg.text}</li>`)
            } else {
                chat.append(`<li style="color:red;"><b>${rendertime(new Date(Date.parse(getMsg.data)))}</b>${getMsg.text}</li>`)
            }
            break;
        case 'getmessages':
            getMsg.messages.map(x => {
                if (x.participant_id === msg.participantId) {
                    chat.append(`<li style="color:green;"><b>${rendertime(new Date(Date.parse(x.message_date)))}</b> ${x.text}</li>`)
                } else {
                    chat.append(`<li style="color:red;"><b>${rendertime(new Date(Date.parse(x.message_date)))}</b> ${x.text}</li>`)
                }
            });
            messageCache = {token: getMsg.token, messages: getMsg.messages};
            localStorage.setItem('messages', JSON.stringify(messageCache));
            break;
    }

};
conn.onclose = event => {
    location.href = location.href;
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

// For the time now
rendertime = function (date) {
    return ((date.getHours() < 10) ? "0" : "") + date.getHours() + ":" + ((date.getMinutes() < 10) ? "0" : "") + date.getMinutes();
};