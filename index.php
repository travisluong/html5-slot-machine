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
var tiles = [];

// create the canvas
var canvas = $('<canvas width ="' + WIDTH + '" height="' + HEIGHT + '"></canvas>');
var ctx = canvas.get(0).getContext("2d");
$(canvas).appendTo('#stage');

// load images
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

tiles.push(new Tile('whitebook', tile1_img, '1'));
tiles.push(new Tile('greenbook', tile2_img, '1'));
tiles.push(new Tile('ia', tile3_img, '1'));
tiles.push(new Tile('money', tile4_img, '1'));
tiles.push(new Tile('seattle', tile5_img, '1'));

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
	console.log(myArray);
}

function GameState(win, paid, credits, bet) {
	this.win = win;
	this.paid = paid;
	this.credits = credits;
	this.bet = bet;
}

function Tile(name, img, value) {
	this.name = name;
	this.img = img;
	this.value = value;
}


function ButtonObject(x, y, width, height) {
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
        	alert('hit');
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

var button_object_array = [
	new ButtonObject(400, 350, 200, 200)
];

var reels = [];

// returns an array of random numbers between 0 and length of tiles array
function generate_random_tile_list (num) {
	var random_tile_list = [];
	for (var i = 0; i < num; i++) {
		var random_num = Math.floor(Math.random() * tiles.length);
		random_tile_list.push(random_num);
	};
	return random_tile_list;
}

reels.push(generate_random_tile_list(10));
reels.push(generate_random_tile_list(10));
reels.push(generate_random_tile_list(10));
reels.push(generate_random_tile_list(10));
reels.push(generate_random_tile_list(10));

function draw_reel (reel, position) {
	for (var i = 0; i < reel.length; i++) {
		// console.log(tiles[reel[i]].name);
		// ctx.save();
		// ctx.translate(150, i * 100 + 150);

		// drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
		x_offset = 100 * position;
		y_offset = 100 * i;
		ctx.drawImage(tiles[reel[i]].img, 0, 0, 100, 100, x_offset + 150, y_offset, 100, 100);
	};
}

draw_reel(reels[0], 0);

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

};

// draw everything
var render = function () {
	// draw background
	ctx.fillStyle = "white";
	ctx.fillRect(0, 0, WIDTH, HEIGHT);
	for (var i = 0; i < reels.length; i++) {
		draw_reel(reels[i], i)
	};
	draw_reel(reels[0], 0);
	ctx.fillStyle = "grey";
	ctx.fillRect(0, HEIGHT / 2, WIDTH, HEIGHT / 2)

	for (var i = 0; i < button_object_array.length; i++) {
		button_object_array[i].draw();
	};
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