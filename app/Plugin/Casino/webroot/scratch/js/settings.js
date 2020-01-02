var CANVAS_WIDTH = 1500;
var CANVAS_HEIGHT = 640;

var EDGEBOARD_X = 345;
var EDGEBOARD_Y = 0;

var DISABLE_SOUND_MOBILE = false;

var PRIMARY_FONT = "Arial";
var PRIMARY_FONT_COLOUR = "#FFFFFF";

var FPS           = 30;
var FPS_TIME      = 1000/FPS;

var STATE_LOADING = 0;
var STATE_MENU    = 1;
var STATE_HELP    = 1;
var STATE_GAME    = 3;

var ON_MOUSE_DOWN = 0;
var ON_MOUSE_UP   = 1;
var ON_MOUSE_OVER = 2;
var ON_MOUSE_OUT  = 3;
var ON_DRAG_START = 4;
var ON_DRAG_END   = 5;
var ON_PRESS_MOVE = 6;

var SCRATCHCANVAS_DIM=342;
var SCRATCH_X=577;
var SCRATCH_Y=283;
var SIZE=10;
var PRECISION=10;

var PRIZE = new Array();
var PRIZE_PROB = new Array();
var PROBABILITY_SCALE = 1; //This set the scale of probability, for example a value of 100, will have a probability % scale based.
var MULTIPLE_WIN_PERCENTAGE;
var SCRATCH_RATIO;

var BET;
var CREDIT;
var CASH_CREDIT;

var ENABLE_FULLSCREEN;
var ENABLE_CHECK_ORIENTATION;
var ENABLE_CREDITS;

var AD_SHOW_COUNTER;

