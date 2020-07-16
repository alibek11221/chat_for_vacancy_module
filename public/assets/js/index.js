const conn = new WebSocket('ws://localhost:8090');
const button = $('#sender');
const text = $('#text');
const chat = $('#chat');
const me = {id: '', ms: ''};
let myId;
let message = '';
conn.onopen = function (e) {
    console.log(e);
};
button.on('click', (e) => {
    me.ms = text.val();
    text.val("");
    conn.send(JSON.stringify(me));
});
conn.onmessage = function (e) {
    let getMsg = JSON.parse(e.data);
    console.log(e.data);
    switch (getMsg.type) {
        case 'init':
            me.id = getMsg.text;
            break;
        case 'msg':
            if (getMsg.from === me.id) {
                chat.append(`<li style="color:green;">${getMsg.text}</li>`)
            } else {
                chat.append(`<li style="color:red;">${getMsg.text}</li>`)
            }
            break;
    }

};
conn.onclose = event => {
    location.href = location.href;
};
// conn.onmessage = function(e) {
// 	var message = JSON.parse(e.data);
// 	console.log(message);
// 	switch (message.type) {
// 		case 'init':
// 			setupScoreboard(message);
// 			break;
// 		case 'goal':
// 			goal(message);
// 			break;
// 	}
// }

// function setupScoreboard(message) {
//
// 	// Create a global reference to the list of games
// 	games = message.games;
// 	for (const game in games) {
//
// 	}
// 	var template = `<tr data-game-id="{{ game.id }}">
// 	<td class="team home"><h3>{{game.home.team}}</h3></td>
// 	<td class="score home">
// 		<div class="flip-counter" id="counter-{{game.id}}-home">{{game.home.score}}</div>
// 	</td>
// 	<td class="divider"><p>:</p></td>
// 	<td class="score away">
// 		<div class="flip-counter" id="counter-{{game.id}}-away">{{game.away.score}}</div>
// 	</td>
// 	<td class="team away"><h3>{{game.away.team}}</h3></td>
// </tr>`;
// 	games.map(x=>{
// 		$('#scoreboard table').append(Mustache.render(template, {game : x}));
// 	})
// }
//
// function goal(message) {
// 	games[message.game][message.team]['score']++;
// 	var counter = games[message.game]['counter_'+message.team];
// 	counter.incrementTo(games[message.game][message.team]['score']);
// }