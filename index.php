<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="description" content="Slot machine game by Travis Luong">
	<meta name="keywords" content="slot machine, casino, html5, indie, browser-based, javascript, arcade, retro, oldschool, travis luong">
	<title>Slot Machine</title>
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/global.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head>
<body>
	<?php include('../header.php'); ?>
	<div class="screen-reader"><h2>Slot Machine</h2></div>
	<div id="stage"></div>
	<div id="instructions">

	</div>
	<script type="text/javascript">
// declare globals
var WIDTH = 800;
var HEIGHT = 600;
// sequence, winnings
var WINNING_SEQUENCES = [
	// white book
	[[0, 0, 0, 0, 0], 125],
	[[0, 0, 0, 0], 25],
	[[0, 0, 0], 5],
	[[0, 0], 2],
	// green book
	[[1, 1, 1, 1, 1], 125],
	[[1, 1, 1, 1], 50],
	[[1, 1, 1], 5],
	[[1, 1], 2],
	// ia
	[[2, 2, 2, 2, 2], 200],
	[[2, 2, 2, 2], 50],
	[[2, 2, 2], 10],
	[[2, 2], 5],
	// money
	[[3, 3, 3, 3, 3], 250],
	[[3, 3, 3, 3], 50],
	[[3, 3, 3], 10],
	[[3, 3], 5],
	// seattle
	[[4, 4, 4, 4, 4], 350],
	[[4, 4, 4, 4], 75],
	[[4, 4, 4], 10],
	[[4, 4], 5],
	// car
	[[5, 5, 5, 5, 5], 400],
	[[5, 5, 5, 5], 80],
	[[5, 5, 5], 20],
	[[5, 5], 10],
	// fruit
	[[6, 6, 6, 6, 6], 500],
	[[6, 6, 6, 6], 100],
	[[6, 6, 6], 25],
	[[6, 6], 10],
	// lucky
	[[7, 7, 7, 7, 7], 2500],
	[[7, 7, 7, 7], 250],
	[[7, 7, 7], 50],
	[[7, 7], 25]
];

// [reel_number, row]
var LINE_MAP = [
	[[0, 1], [1, 1], [2, 1], [3, 1], [4, 1]],
	[[0, 0], [1, 0], [2, 0], [3, 0], [4, 0]],
	[[0, 2], [1, 2], [2, 2], [3, 2], [4, 2]],
	[[0, 0], [1, 1], [2, 2], [3, 1], [4, 0]],
	[[0, 2], [1, 1], [2, 0], [3, 1], [4, 2]],
	[[0, 0], [1, 0], [2, 1], [3, 2], [4, 2]],
	[[0, 2], [1, 2], [2, 1], [3, 0], [4, 0]],
	[[0, 1], [1, 2], [2, 1], [3, 0], [4, 1]],
	[[0, 1], [1, 0], [2, 1], [3, 2], [4, 1]]
];

// console.log(WINNING_SEQUENCES);

// create the canvas
var canvas = $('<canvas width ="' + WIDTH + '" height="' + HEIGHT + '"></canvas>');
var ctx = canvas.get(0).getContext("2d");
$(canvas).appendTo('#stage');

// load images
var bg_img_ready = false;
var bg_img = new Image();
bg_img.onload = function () {
	bg_img_ready = true;
}
bg_img.src = "img/lucky-slot-machine.png";

var buttons_on_img_ready = false;
var buttons_on_img = new Image();
buttons_on_img.onload = function () {
	buttons_on_img_ready = true;
}
buttons_on_img.src = "img/buttons-on.png";

var tile1_img_ready = false;
var tile1_img = new Image();
tile1_img.onload = function () {
	tile1_img_ready = true;
}
tile1_img.src = "img/tl-icons-book.png";

var tile2_img_ready = false;
var tile2_img = new Image();
tile2_img.onload = function () {
	tile2_img_ready = true;
}
tile2_img.src = "img/tl-icons-book_06.png";

var tile3_img_ready = false;
var tile3_img = new Image();
tile3_img.onload = function () {
	tile3_img_ready = true;
}
tile3_img.src = "img/tl-icons-ia.png";

var tile4_img_ready = false;
var tile4_img = new Image();
tile4_img.onload = function () {
	tile4_img_ready = true;
}
tile4_img.src = "img/tl-icons-money.png";

var tile5_img_ready = false;
var tile5_img = new Image();
tile5_img.onload = function () {
	tile5_img_ready = true;
}
tile5_img.src = "img/tl-icons-spaceneedle.png";

var tile6_img_ready = false;
var tile6_img = new Image();
tile6_img.onload = function () {
	tile6_img_ready = true;
}
tile6_img.src = "img/tl-car.png";

var tile7_img_ready = false;
var tile7_img = new Image();
tile7_img.onload = function () {
	tile7_img_ready = true;
}
tile7_img.src = "img/tl-fruit.png";

var lucky_img_ready = false;
var lucky_img = new Image();
lucky_img.onload = function () {
	lucky_img_ready = true;
}
lucky_img.src = "img/lucky.png";

var ball1_img_ready = false;
var ball1_img = new Image();
ball1_img.onload = function () {
	ball1_img_ready = true;
}
ball1_img.src = "img/ball1.png";

var ball2_img_ready = false;
var ball2_img = new Image();
ball2_img.onload = function () {
	ball2_img_ready = true;
}
ball2_img.src = "img/ball2.png";

var ball3_img_ready = false;
var ball3_img = new Image();
ball3_img.onload = function () {
	ball3_img_ready = true;
}
ball3_img.src = "img/ball3.png";

var ball4_img_ready = false;
var ball4_img = new Image();
ball4_img.onload = function () {
	ball4_img_ready = true;
}
ball4_img.src = "img/ball4.png";

var ball5_img_ready = false;
var ball5_img = new Image();
ball5_img.onload = function () {
	ball5_img_ready = true;
}
ball5_img.src = "img/ball5.png";

var ball6_img_ready = false;
var ball6_img = new Image();
ball6_img.onload = function () {
	ball6_img_ready = true;
}
ball6_img.src = "img/ball6.png";

var ball7_img_ready = false;
var ball7_img = new Image();
ball7_img.onload = function () {
	ball7_img_ready = true;
}
ball7_img.src = "img/ball7.png";

var ball8_img_ready = false;
var ball8_img = new Image();
ball8_img.onload = function () {
	ball8_img_ready = true;
}
ball8_img.src = "img/ball8.png";

var ball9_img_ready = false;
var ball9_img = new Image();
ball9_img.onload = function () {
	ball9_img_ready = true;
}
ball9_img.src = "img/ball9.png";

var line1_img_ready = false;
var line1_img = new Image();
line1_img.onload = function () {
	line1_img_ready = true;
}
line1_img.src = "img/line1.png";

var line2_img_ready = false;
var line2_img = new Image();
line2_img.onload = function () {
	line2_img_ready = true;
}
line2_img.src = "img/line2.png";

var line3_img_ready = false;
var line3_img = new Image();
line3_img.onload = function () {
	line3_img_ready = true;
}
line3_img.src = "img/line3.png";

var line4_img_ready = false;
var line4_img = new Image();
line4_img.onload = function () {
	line4_img_ready = true;
}
line4_img.src = "img/line4.png";

var line5_img_ready = false;
var line5_img = new Image();
line5_img.onload = function () {
	line5_img_ready = true;
}
line5_img.src = "img/line5.png";

var line6_img_ready = false;
var line6_img = new Image();
line6_img.onload = function () {
	line6_img_ready = true;
}
line6_img.src = "img/line6.png";

var line7_img_ready = false;
var line7_img = new Image();
line7_img.onload = function () {
	line7_img_ready = true;
}
line7_img.src = "img/line7.png";

var line8_img_ready = false;
var line8_img = new Image();
line8_img.onload = function () {
	line8_img_ready = true;
}
line8_img.src = "img/line8.png";

var line9_img_ready = false;
var line9_img = new Image();
line9_img.onload = function () {
	line9_img_ready = true;
}
line9_img.src = "img/line9.png";


// compare line and winning sequence
// return false if no match and true if match
function results_sequence_match(results, winning_sequence) {
	for (var i = 0; i < winning_sequence.length; i++) {
		if (winning_sequence[i] != results[i]) {
			return false;
		}
	};
	return true;
}

// get results from reel based on line_map argument
// return the results of one line
function get_results_line_from_reel(line_map) {
	var results = [];
	for (var i = 0; i < line_map.length; i++) {
		var reel_number = line_map[i][0];
		var row = line_map[i][1];
		results.push(reels_top[reel_number].tiles[row]);
	};
	return results;
}

// return array of all results
function get_all_results(lines_to_get) {
	all_results = [];
	for (var i = 0; i < lines_to_get; i++) {
		all_results.push(get_results_line_from_reel(LINE_MAP[i]));
	};
	return all_results;
}

// calculate winnings from results
function calculate_winnings(all_results) {
	game_state.highlight_tiles = [];
	for (var i = 0; i < all_results.length; i++) {
		for (var j = 0; j < WINNING_SEQUENCES.length; j++) {
			if (results_sequence_match(all_results[i], WINNING_SEQUENCES[j][0])) {
				game_state.win += WINNING_SEQUENCES[j][1];
				game_state.highlight_tiles.push(i);
				game_state.current_line_winnings_map.push([i, WINNING_SEQUENCES[j][1]]);
				break;
			}
		};
	};
	console.log(game_state.highlight_tiles);
}


function rotate_highlight_tiles() {
	game_state.show_highlight_tiles = true;
	game_state.current_highlight_tiles_counter++;
	var current_index = game_state.current_highlight_tiles_counter % game_state.highlight_tiles.length;
	console.log(current_index);
	game_state.current_highlight_tiles = game_state.highlight_tiles[current_index];
}

// fisher yates shuffle algorithm
function fisherYates ( myArray ) {
	var i = myArray.length;
	if ( i == 0 ) return false;
	while ( --i ) {
		var j = Math.floor( Math.random() * ( i + 1 ) );
		var tempi = myArray[i];
		var tempj = myArray[j];
		myArray[i] = tempj;
		myArray[j] = tempi;
	}
	// console.log(myArray);
}

function GameState(win, paid, credits, bet, tiles, highlight_tiles, show_highlight_tiles) {
	this.win = win;
	this.paid = paid;
	this.credits = credits;
	this.bet = bet;
	this.tiles = tiles;
	this.highlight_tiles = highlight_tiles;
	this.show_highlight_tiles = show_highlight_tiles;
	this.current_highlight_tiles = 0;
	this.current_highlight_tiles_counter = 0;
	this.rotate_highlight_loop = null;
	this.spin_click_shield = false;
	this.show_lines = true;
	this.current_line_winnings_map = [];
	this.transfer_win_to_credits = function() {
		var i = this.win;
		var counter = 0;
		while (i > 0) {
			i -= 1;
			counter += 50;
			setTimeout(function(){
				game_state.paid += 1;
				game_state.credits += 1;
			}, counter);
		}
	}
}

var game_state = new GameState(0, 0, 50, 0, [], [], true);

game_state.tiles.push(new Tile('whitebook', tile1_img, '1'));
game_state.tiles.push(new Tile('greenbook', tile2_img, '1'));
game_state.tiles.push(new Tile('ia', tile3_img, '1'));
game_state.tiles.push(new Tile('money', tile4_img, '1'));
game_state.tiles.push(new Tile('seattle', tile5_img, '1'));
game_state.tiles.push(new Tile('car', tile6_img, '1'));
game_state.tiles.push(new Tile('fruit', tile7_img, '1'));
game_state.tiles.push(new Tile('lucky', lucky_img, '1'));

function Tile(name, img, value) {
	this.name = name;
	this.img = img;
	this.value = value;
}

function Reel(x, y, x_vel, y_vel, y_acc, tiles) {
	this.x = x;
	this.y = y;
	this.x_vel = x_vel;
	this.y_vel = y_vel;
	this.y_acc = y_acc;
	this.tiles = tiles;
	this.draw = function() {
		for (var i = 0; i < this.tiles.length; i++) {
			// drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
			y_offset = this.y + 100 * i + 19;
			ctx.drawImage(game_state.tiles[this.tiles[i]].img, 0, 0, 100, 100, this.x, y_offset, 100, 100);
		};
	};
	this.update = function(modifier) {
		// update velocity
		// this.y_vel += this.y_acc;

		// update position
		this.y += this.y_vel;

		// console.log(this.y);


	};
}

function ButtonObject(x, y, width, height, handler) {
	this.x = x;
	this.y = y;
	this.width = width;
	this.height = height;

    // mouse parameter holds the mouse coordinates
    this.handleClick = function(mouse) {

        // perform hit test between bounding box
        // and mouse coordinates

        if (this.x < mouse.x &&
        	this.x + this.width > mouse.x &&
        	this.y < mouse.y &&
        	this.y + this.height > mouse.y) {

            // hit test succeeded, handle the click event!

        handler();
        return true;
    	}

        // hit test did not succeed
        return false;
    }

    // draw function
    this.draw = function() {
    	ctx.fillStyle = "white";
    	ctx.fillRect(this.x, this.y, this.width, this.height);
    }
}

// subclass of ButtonObject
function BetButton(x, y, width, height, handler, bet_amount) {
	ButtonObject.apply(this, arguments);
	this.bet_amount = bet_amount;
	this.handleClick = function(mouse) {

        if (this.x < mouse.x &&
        	this.x + this.width > mouse.x &&
        	this.y < mouse.y &&
        	this.y + this.height > mouse.y) {

	        handler(bet_amount);
	    	game_state.show_lines = true;
	    	game_state.show_highlight_tiles = false;
	        return true;
	    }
	}
}

var change_bet_amount = function(bet_amount) {
	game_state.bet = bet_amount;
}

var reels_top = [];

var spin_handler = function(){
	if (game_state.spin_click_shield) {
		return;
	}
	game_state.spin_click_shield = true;
	clearInterval(game_state.rotate_highlight_loop);
	game_state.current_highlight_tiles_counter = 0;
	game_state.rotate_highlight_loop = 0;
	game_state.paid = 0;
	game_state.win = 0;
	game_state.credits -= game_state.bet;
	game_state.show_highlight_tiles = false;
	game_state.show_lines = false;
	game_state.current_line_winnings_map = [];
	for (var i = 0; i < reels_bottom.length; i++) {
		reels_top = generate_reels(-1000);
		animate_reels(i);
	};
	calculate_winnings(get_all_results(game_state.bet));
	setTimeout(function(){
		game_state.transfer_win_to_credits();
		game_state.rotate_highlight_loop = setInterval(rotate_highlight_tiles, 2000);
		game_state.spin_click_shield = false;
	}, 1500);
}

var animate_reels = function(index){
	setTimeout(function(){
		reels_top[index].y_vel = 15;
		reels_bottom[index].y_vel = 15;
	}, 100 * index);
}

var button_object_array = [
	// x, y, width, height, click handler
	new ButtonObject(550, 450, 86, 80, spin_handler),
	new BetButton(473, 450, 64, 57, change_bet_amount, 9),
	new BetButton(396, 450, 64, 57, change_bet_amount, 7),
	new BetButton(319, 450, 64, 57, change_bet_amount, 5),
	new BetButton(242, 450, 64, 57, change_bet_amount, 3),
	new BetButton(165, 450, 64, 57, change_bet_amount, 1),
	];

// returns an array of random numbers between 0 and length of tiles array
function generate_random_tile_list (num) {
	var random_tile_list = [];
	for (var i = 0; i < num; i++) {
		var random_num = Math.floor(Math.random() * game_state.tiles.length);
		random_tile_list.push(random_num);
	};
	return random_tile_list;
}

var generate_reels = function(starting_y){
	var reels = [];
	reels.push(new Reel(150, starting_y, 0, 0, 0, generate_random_tile_list(10)));
	reels.push(new Reel(250, starting_y, 0, 0, 0, generate_random_tile_list(10)));
	reels.push(new Reel(350, starting_y, 0, 0, 0, generate_random_tile_list(10)));
	reels.push(new Reel(450, starting_y, 0, 0, 0, generate_random_tile_list(10)));
	reels.push(new Reel(550, starting_y, 0, 0, 0, generate_random_tile_list(10)));
	return reels;
}

var reels_bottom = generate_reels(0);

// console.log(reels);

function draw_reel (reel) {
	for (var i = 0; i < reel.tiles.length; i++) {
		// drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
		y_offset = 100 * i;
		ctx.drawImage(game_state.tiles[reel.tiles[i]].img, 0, 0, 100, 100, reel.x, y_offset, 100, 100);
	};
}

// draw_reel(reels[0], 0);

// handle keyboard controls
var keysDown = {};

addEventListener("keydown", function (e) {
	keysDown[e.keyCode] = true;
	switch(e.keyCode){
		case 37: case 39: case 38:  case 40: // arrow keys
		case 32: e.preventDefault(); break; // space
		default: break; // do not block other keys
	}
	if (e.keyCode == 32) {

	}
}, false);

addEventListener("keyup", function (e) {
	delete keysDown[e.keyCode];
}, false);

// handle clicks
// http://www.ibm.com/developerworks/library/wa-games/
// calculate position of the canvas DOM element on the page
var canvasPosition = {
	x: canvas.offset().left,
	y: canvas.offset().top
};

canvas.on('click', function(e) {

	// use pageX and pageY to get the mouse position
	// relative to the browser window

	var mouse = {
		x: e.pageX - canvasPosition.x,
		y: e.pageY - canvasPosition.y
	}

	// iterate through all button objects
	// and call the onclick handler of each

	for (var i = 0; i < button_object_array.length; i++) {
		button_object_array[i].handleClick(mouse);
	};

	// now you have local coordinates,
	// which consider a (0,0) origin at the
	// top-left of canvas element
});


// reset the game
var reset = function () {

};

// update game objects
var update = function (modifier) {
	for (var i = 0; i < reels_bottom.length; i++) {
		reels_bottom[i].update(modifier);
	};
	for (var i = 0; i < reels_top.length; i++) {
		if (reels_top[i].y >= 0) {
			reels_top[i].y_vel = 0;
			reels_bottom = reels_top;
			reels_bottom[i].y = 0;
		}
		reels_top[i].update(modifier);
	};
};

// draw everything
var render = function () {
	// draw white background
	ctx.fillStyle = "white";
	ctx.fillRect(0, 0, WIDTH, HEIGHT);

	for (var i = 0; i < reels_bottom.length; i++) {
		// draw_reel(reels[i]);
		reels_bottom[i].draw();
	};
	for (var i = 0; i < reels_top.length; i++) {
		// draw_reel(reels[i]);
		reels_top[i].draw();
	};

	for (var i = 0; i < button_object_array.length; i++) {
		button_object_array[i].draw();
	};

	// draw bg img
	ctx.drawImage(bg_img, 0, 0, 800, 600, 0, 0, 800, 600);

	// draw game states
	ctx.fillStyle = "#7F9500"
	ctx.font = "14px 'Press Start 2P'";
	ctx.textAlign = "right";
	ctx.textBaseline = "top";
	ctx.fillText(game_state.win, 265, 358);
	ctx.fillText(game_state.paid, 382, 358);
	ctx.fillText(game_state.credits, 512, 358);
	ctx.fillText(game_state.bet, 602, 358);

	// draw game state highlight tiles
	if (game_state.show_highlight_tiles && game_state.highlight_tiles.length && !game_state.show_lines) {
		var winnings_x_coord = 155;
		var winnings_y_coord = 0;
		switch(game_state.current_highlight_tiles) {
			case 0:
				ctx.fillStyle = "rgba(0, 147, 68, 0.5)";
				winnings_y_coord = 125;
				break;
			case 1:
				ctx.fillStyle = "rgba(214, 223, 35, 0.5)";
				winnings_y_coord = 25;
				break;
			case 2:
				ctx.fillStyle = "rgba(42, 56, 143, 0.5)";
				winnings_y_coord = 225;
				break;
			case 3:
				ctx.fillStyle = "rgba(237, 28, 36, 0.5)";
				winnings_y_coord = 25;
				break;
			case 4:
				ctx.fillStyle = "rgba(211, 91, 146, 0.5)";
				winnings_y_coord = 225;
				break;
			case 5:
				ctx.fillStyle = "rgba(251, 175, 63, 0.5)";
				winnings_y_coord = 25;
				break;
			case 6:
				ctx.fillStyle = "rgba(101, 44, 144, 0.5)";
				winnings_y_coord = 225;
				break;
			case 7:
				ctx.fillStyle = "rgba(140, 198, 62, 0.5)";
				winnings_y_coord = 125;
				break;
			case 8:
				ctx.fillStyle = "rgba(0, 173, 239, 0.5)";
				winnings_y_coord = 125;
				break;
		}
		for (var j = 0; j < 5; j++) {
			var x_coord = LINE_MAP[game_state.current_highlight_tiles][j][0] * 100 + 150;
			var y_coord = LINE_MAP[game_state.current_highlight_tiles][j][1] * 100 + 20;
			ctx.fillRect(x_coord, y_coord, 100, 100);
		};
		for (var i = 0; i < game_state.current_line_winnings_map.length; i++) {
			if (game_state.current_line_winnings_map[i][0] == game_state.current_highlight_tiles) {
				ctx.textAlign = "left";
				ctx.fillStyle = "#FFFFFF";
				ctx.fillText(game_state.current_line_winnings_map[i][1], winnings_x_coord, winnings_y_coord);
			}
		};
	}

	// draw number balls
	if (game_state.bet >= 1) {
		ctx.drawImage(ball1_img, 0, 0, 561, 301, 117, 20, 561, 301);
		if (game_state.show_lines) {
			ctx.drawImage(line1_img, 0, 0, 561, 301, 117, 20, 561, 301);
		}
	}
	if (game_state.bet >= 3) {
		ctx.drawImage(ball2_img, 0, 0, 561, 301, 117, 20, 561, 301);
		ctx.drawImage(ball3_img, 0, 0, 561, 301, 117, 20, 561, 301);
		if (game_state.show_lines) {
			ctx.drawImage(line2_img, 0, 0, 561, 301, 117, 20, 561, 301);
			ctx.drawImage(line3_img, 0, 0, 561, 301, 117, 20, 561, 301);
		}
	}
	if (game_state.bet >= 5) {
		ctx.drawImage(ball4_img, 0, 0, 561, 301, 117, 20, 561, 301);
		ctx.drawImage(ball5_img, 0, 0, 561, 301, 117, 20, 561, 301);
		if (game_state.show_lines) {
			ctx.drawImage(line4_img, 0, 0, 561, 301, 117, 20, 561, 301);
			ctx.drawImage(line5_img, 0, 0, 561, 301, 117, 20, 561, 301);
		}
	}
	if (game_state.bet >= 7) {
		ctx.drawImage(ball6_img, 0, 0, 561, 301, 117, 20, 561, 301);
		ctx.drawImage(ball7_img, 0, 0, 561, 301, 117, 20, 561, 301);
		if (game_state.show_lines) {
			ctx.drawImage(line6_img, 0, 0, 561, 301, 117, 20, 561, 301);
			ctx.drawImage(line7_img, 0, 0, 561, 301, 117, 20, 561, 301);
		}
	}
	if (game_state.bet >= 9) {
		ctx.drawImage(ball8_img, 0, 0, 561, 301, 117, 20, 561, 301);
		ctx.drawImage(ball9_img, 0, 0, 561, 301, 117, 20, 561, 301);
		if (game_state.show_lines) {
			ctx.drawImage(line8_img, 0, 0, 561, 301, 117, 20, 561, 301);
			ctx.drawImage(line9_img, 0, 0, 561, 301, 117, 20, 561, 301);
		}
	}
};

// the main game loop
var main = function () {
	var now = Date.now();
	var delta = now - then;

	update(delta / 1000);
	render();

	then = now;
};

// let's play this game!
reset();
var then = Date.now();
var main_loop = setInterval(main, 16); // run script every 16 milliseconds, approx 60 FPS
</script>
<?php include('../footer.php'); ?>
</body>
</html>