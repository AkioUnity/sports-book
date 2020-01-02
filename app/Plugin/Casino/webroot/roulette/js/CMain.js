function CMain(oData){
    var _bUpdate;
    var _iCurResource = 0;
    var RESOURCE_TO_LOAD = 0;
    var _iState = STATE_LOADING;
    
    var _oData;
    var _oPreloader;
    var _oMenu;
    var _oHelp;
    var _oGame;

    this.initContainer = function(){
        var canvas = document.getElementById("canvas");
        s_oStage = new createjs.Stage(canvas);       
        createjs.Touch.enable(s_oStage);
        
        s_bMobile = jQuery.browser.mobile;
        if(s_bMobile === false){
            s_oStage.enableMouseOver(20);  
        }
        
        
        s_iPrevTime = new Date().getTime();

        createjs.Ticker.setFPS(30);
	createjs.Ticker.addEventListener("tick", this._update);
		
        if(navigator.userAgent.match(/Windows Phone/i)){
                DISABLE_SOUND_MOBILE = true;
        }
		
        s_oSpriteLibrary  = new CSpriteLibrary();

        //ADD PRELOADER
        _oPreloader = new CPreloader();
    };

    this.soundLoaded = function(){
         _iCurResource++;

         if(_iCurResource === RESOURCE_TO_LOAD){
             _oPreloader.unload();
            
            this.gotoMenu();
         }
    };
    
    this._initSounds = function(){
         if (!createjs.Sound.initializeDefaultPlugins()) {
             return;
         }
	
        if(navigator.userAgent.indexOf("Opera")>0 || navigator.userAgent.indexOf("OPR")>0){
                createjs.Sound.alternateExtensions = ["mp3"];
                createjs.Sound.addEventListener("fileload", createjs.proxy(this.soundLoaded, this));

                createjs.Sound.registerSound("./sounds/chip.ogg", "chip");
                createjs.Sound.registerSound("./sounds/click.ogg", "click");
                createjs.Sound.registerSound("./sounds/fiche_collect.ogg", "fiche_collect");
                createjs.Sound.registerSound("./sounds/fiche_select.ogg", "fiche_select");
                createjs.Sound.registerSound("./sounds/wheel_sound.ogg", "wheel_sound");
        }else{
                createjs.Sound.alternateExtensions = ["ogg"];
                createjs.Sound.addEventListener("fileload", createjs.proxy(this.soundLoaded, this));

                createjs.Sound.registerSound("./sounds/chip.mp3", "chip");
                createjs.Sound.registerSound("./sounds/click.mp3", "click");
                createjs.Sound.registerSound("./sounds/fiche_collect.mp3", "fiche_collect");
                createjs.Sound.registerSound("./sounds/fiche_select.mp3", "fiche_select");
                createjs.Sound.registerSound("./sounds/wheel_sound.mp3", "wheel_sound");
        }
        
        RESOURCE_TO_LOAD += 5;
    };
    
    this._loadImages = function(){
        s_oSpriteLibrary.init( this._onImagesLoaded,this._onAllImagesLoaded, this );

		s_oSpriteLibrary.addSprite("bg_menu","./sprites/bg_menu.jpg");
        s_oSpriteLibrary.addSprite("but_bg","./sprites/but_play_bg.png");
        s_oSpriteLibrary.addSprite("but_exit","./sprites/but_exit.png");
        s_oSpriteLibrary.addSprite("bg_game","./sprites/bg_game.jpg");
        s_oSpriteLibrary.addSprite("audio_icon","./sprites/audio_icon.png");
        s_oSpriteLibrary.addSprite("block","./sprites/block.png");
        s_oSpriteLibrary.addSprite("msg_box","./sprites/msg_box.png");
        s_oSpriteLibrary.addSprite("display_bg","./sprites/display_bg.png");
        s_oSpriteLibrary.addSprite("hit_area_bet0","./sprites/hit_area_bet0.png");
        s_oSpriteLibrary.addSprite("hit_area_simple_bet","./sprites/hit_area_simple_bet.png");
        s_oSpriteLibrary.addSprite("hit_area_couple_bet","./sprites/hit_area_couple_bet.png");
        s_oSpriteLibrary.addSprite("hit_area_small_circle","./sprites/hit_area_small_circle.png");
        s_oSpriteLibrary.addSprite("hit_area_triple_bet","./sprites/hit_area_triple_bet.png");
        s_oSpriteLibrary.addSprite("hit_area_col_bet","./sprites/hit_area_col_bet.png");
        s_oSpriteLibrary.addSprite("hit_area_twelve_bet","./sprites/hit_area_twelve_bet.png");
        s_oSpriteLibrary.addSprite("hit_area_other_bet","./sprites/hit_area_other_bet.png");
        s_oSpriteLibrary.addSprite("enlight_bet0","./sprites/enlight_bet0.png");
        s_oSpriteLibrary.addSprite("enlight_black","./sprites/enlight_black.png");
        s_oSpriteLibrary.addSprite("enlight_first18","./sprites/enlight_first18.png");
        s_oSpriteLibrary.addSprite("enlight_first_twelve","./sprites/enlight_first_twelve.png");
        s_oSpriteLibrary.addSprite("enlight_second_twelve","./sprites/enlight_second_twelve.png");
        s_oSpriteLibrary.addSprite("enlight_third_twelve","./sprites/enlight_third_twelve.png");
        s_oSpriteLibrary.addSprite("enlight_second18","./sprites/enlight_second18.png");
        s_oSpriteLibrary.addSprite("enlight_number1","./sprites/enlight_number1.png");
        s_oSpriteLibrary.addSprite("enlight_number3","./sprites/enlight_number3.png");
        s_oSpriteLibrary.addSprite("enlight_number4","./sprites/enlight_number4.png");
        s_oSpriteLibrary.addSprite("enlight_number12","./sprites/enlight_number12.png");
        s_oSpriteLibrary.addSprite("enlight_number16","./sprites/enlight_number16.png");
        s_oSpriteLibrary.addSprite("enlight_number25","./sprites/enlight_number25.png");
        s_oSpriteLibrary.addSprite("enlight_number30","./sprites/enlight_number30.png");
        s_oSpriteLibrary.addSprite("enlight_odd","./sprites/enlight_odd.png");
        s_oSpriteLibrary.addSprite("enlight_red","./sprites/enlight_red.png");
        s_oSpriteLibrary.addSprite("enlight_col","./sprites/enlight_col.png");
        s_oSpriteLibrary.addSprite("select_fiche","./sprites/select_fiche.png");
        s_oSpriteLibrary.addSprite("roulette_anim_bg","./sprites/roulette_anim_bg.png");
        s_oSpriteLibrary.addSprite("ball_spin","./sprites/ball_spin.png");
        s_oSpriteLibrary.addSprite("spin_but","./sprites/spin_but.png");
        s_oSpriteLibrary.addSprite("placeholder","./sprites/placeholder.png");
        s_oSpriteLibrary.addSprite("but_game_bg","./sprites/but_game_bg.png");
        s_oSpriteLibrary.addSprite("circle_red","./sprites/circle_red.png");
        s_oSpriteLibrary.addSprite("circle_green","./sprites/circle_green.png");
        s_oSpriteLibrary.addSprite("circle_black","./sprites/circle_black.png");
        s_oSpriteLibrary.addSprite("final_bet_bg","./sprites/final_bet_bg.png");
        s_oSpriteLibrary.addSprite("neighbor_bg","./sprites/neighbor_bg.jpg");
        s_oSpriteLibrary.addSprite("neighbor_enlight","./sprites/neighbor_enlight.png");
        s_oSpriteLibrary.addSprite("hitarea_neighbor","./sprites/hitarea_neighbor.png");
        s_oSpriteLibrary.addSprite("game_over_bg","./sprites/game_over_bg.jpg");
        s_oSpriteLibrary.addSprite("but_game_small","./sprites/but_game_small.png");
        s_oSpriteLibrary.addSprite("but_fullscreen","./sprites/but_fullscreen.png");
        
        for(var i=0;i<NUM_FICHES;i++){
            s_oSpriteLibrary.addSprite("fiche_"+i,"./sprites/fiche_"+i+".png");
        }
        
        for(var j=0;j<NUM_MASK_BALL_SPIN_FRAMES;j++){
            s_oSpriteLibrary.addSprite("mask_ball_spin_"+j,"./sprites/mask_ball_spin/mask_ball_spin_"+j+".png");
        }
        
        for(var t=0;t<NUM_MASK_BALL_SPIN_FRAMES;t++){
            s_oSpriteLibrary.addSprite("wheel_anim_"+t,"./sprites/wheel_anim/wheel_anim_"+t+".jpg");
        }
        
        for(var k=0;k<NUM_WHEEL_TOP_FRAMES;k++){
            s_oSpriteLibrary.addSprite("wheel_top_"+k,"./sprites/wheel_top/wheel_top_"+k+".jpg");
        }
        
        for(var q=0;q<NUM_BALL_SPIN_FRAMES;q++){
            s_oSpriteLibrary.addSprite("ball_spin1_"+q,"./sprites/ball_spin1/ball_spin1_"+q+".png");
            s_oSpriteLibrary.addSprite("ball_spin2_"+q,"./sprites/ball_spin2/ball_spin2_"+q+".png");
            s_oSpriteLibrary.addSprite("ball_spin3_"+q,"./sprites/ball_spin3/ball_spin3_"+q+".png");
        }
        
        RESOURCE_TO_LOAD += s_oSpriteLibrary.getNumSprites();

        s_oSpriteLibrary.loadSprites();
    };
    
    this._onImagesLoaded = function(){
        _iCurResource++;

        var iPerc = Math.floor(_iCurResource/RESOURCE_TO_LOAD *100);

        _oPreloader.refreshLoader(iPerc);
        
        if(_iCurResource === RESOURCE_TO_LOAD){
            _oPreloader.unload();
            
            this.gotoMenu();
        }
    };
    
    this._onAllImagesLoaded = function(){
        
    };
    
    this.onAllPreloaderImagesLoaded = function(){
        this._loadImages();
    };
    
    this.onImageLoadError = function(szText){
        
    };
	
    this.preloaderReady = function(){
        this._loadImages();
		
	if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            this._initSounds();
        }
        
        _bUpdate = true;
    };
    
    this.gotoMenu = function(){
        _oMenu = new CMenu();
        _iState = STATE_MENU;
    };
    
    this.gotoGame = function(){
        _oGame = new CGame(_oData);   
							
        _iState = STATE_GAME;
    };
    
    this.gotoHelp = function(){
        _oHelp = new CHelp();
        _iState = STATE_HELP;
    };
    
    this.stopUpdate = function(){
        _bUpdate = false;
        createjs.Ticker.paused = true;
        $("#block_game").css("display","block");
	createjs.Sound.setMute(true);
    };

    this.startUpdate = function(){
        s_iPrevTime = new Date().getTime();
        _bUpdate = true;
        createjs.Ticker.paused = false;
        $("#block_game").css("display","none");

        if(s_bAudioActive){
                createjs.Sound.setMute(false);
        }
    };
    
    this._update = function(event){
        if(_bUpdate === false){
                return;
        }
        var iCurTime = new Date().getTime();
        s_iTimeElaps = iCurTime - s_iPrevTime;
        s_iCntTime += s_iTimeElaps;
        s_iCntFps++;
        s_iPrevTime = iCurTime;
        
        if ( s_iCntTime >= 1000 ){
            s_iCurFps = s_iCntFps;
            s_iCntTime-=1000;
            s_iCntFps = 0;
        }
                
        if(_iState === STATE_GAME){
            _oGame.update();
        }
        
        s_oStage.update(event);

    };
    
    s_oMain = this;
    _oData = oData;
    ENABLE_CHECK_ORIENTATION = oData.check_orientation;
    
    this.initContainer();
}

var s_bMobile;
var s_bAudioActive = true;
var s_iCntTime = 0;
var s_iTimeElaps = 0;
var s_iPrevTime = 0;
var s_iCntFps = 0;
var s_iCurFps = 0;

var s_oDrawLayer;
var s_oStage;
var s_oMain = null;
var s_oSpriteLibrary;
var s_bFullscreen = false;