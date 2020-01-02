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
    
    this.preloaderReady = function(){
        this._loadImages();
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            this._initSounds();
        }
        
        _bUpdate = true;
    };

    this.soundLoaded = function(){
         _iCurResource++;

         if(_iCurResource === RESOURCE_TO_LOAD){
            this._onRemovePreloader();
         }
    };
    
    this._initSounds = function(){
         if (!createjs.Sound.initializeDefaultPlugins()) {
             return;
         }
        
        if(navigator.userAgent.indexOf("Opera")>0 || navigator.userAgent.indexOf("OPR")>0){
			createjs.Sound.alternateExtensions = ["mp3"];
			createjs.Sound.addEventListener("fileload", createjs.proxy(this.soundLoaded, this));
		
			createjs.Sound.registerSound("./sounds/press_but.ogg", "press_but");
			createjs.Sound.registerSound("./sounds/win.ogg", "win");
			createjs.Sound.registerSound("./sounds/reels.ogg", "reels");
			createjs.Sound.registerSound("./sounds/reel_stop.ogg", "reel_stop",6);
			createjs.Sound.registerSound("./sounds/start_reel.ogg", "start_reel",6);
			createjs.Sound.registerSound("./sounds/bonus_item_choosen.ogg", "bonus_item_choosen");
			createjs.Sound.registerSound("./sounds/press_hold.ogg", "press_hold");
			createjs.Sound.registerSound("./sounds/soundtrack.ogg", "soundtrack");
			createjs.Sound.registerSound("./sounds/soundtrack_bonus.ogg", "soundtrack_bonus");
			createjs.Sound.registerSound("./sounds/reveal_prize.ogg", "reveal_prize");
		}else{
			createjs.Sound.alternateExtensions = ["ogg"];
			createjs.Sound.addEventListener("fileload", createjs.proxy(this.soundLoaded, this));
		
			createjs.Sound.registerSound("./sounds/press_but.mp3", "press_but");
			createjs.Sound.registerSound("./sounds/win.mp3", "win");
			createjs.Sound.registerSound("./sounds/reels.mp3", "reels");
			createjs.Sound.registerSound("./sounds/reel_stop.mp3", "reel_stop",6);
			createjs.Sound.registerSound("./sounds/start_reel.mp3", "start_reel",6);
			createjs.Sound.registerSound("./sounds/bonus_item_choosen.mp3", "bonus_item_choosen");
			createjs.Sound.registerSound("./sounds/press_hold.mp3", "press_hold");
			createjs.Sound.registerSound("./sounds/soundtrack.mp3", "soundtrack");
			createjs.Sound.registerSound("./sounds/soundtrack_bonus.mp3", "soundtrack_bonus");
			createjs.Sound.registerSound("./sounds/reveal_prize.mp3", "reveal_prize");
		}
        
        RESOURCE_TO_LOAD += 10;
    };
    
    this._loadImages = function(){
        s_oSpriteLibrary.init( this._onImagesLoaded,this._onAllImagesLoaded, this );

        s_oSpriteLibrary.addSprite("but_exit","./sprites/but_exit.png");
        s_oSpriteLibrary.addSprite("bg_menu","./sprites/bg_menu.jpg");
        s_oSpriteLibrary.addSprite("bg_game","./sprites/bg_game.jpg");
        s_oSpriteLibrary.addSprite("paytable","./sprites/paytable.jpg");
        s_oSpriteLibrary.addSprite("but_play_bg","./sprites/but_play_bg.png");
        s_oSpriteLibrary.addSprite("mask_slot","./sprites/mask_slot.png");
        s_oSpriteLibrary.addSprite("spin_but","./sprites/but_spin_bg.png");
        s_oSpriteLibrary.addSprite("coin_but","./sprites/but_coin_bg.png");
        s_oSpriteLibrary.addSprite("info_but","./sprites/but_info_bg.png");
        s_oSpriteLibrary.addSprite("bet_but","./sprites/bet_but.png");
        s_oSpriteLibrary.addSprite("win_frame_anim","./sprites/win_frame_anim.png");
        s_oSpriteLibrary.addSprite("but_lines_bg","./sprites/but_lines_bg.png");
        s_oSpriteLibrary.addSprite("but_maxbet_bg","./sprites/but_maxbet_bg.png");
        s_oSpriteLibrary.addSprite("audio_icon","./sprites/audio_icon.png");
        s_oSpriteLibrary.addSprite("hit_area_col","./sprites/hit_area_col.png");
        s_oSpriteLibrary.addSprite("hold_col","./sprites/hold_col.png");
        s_oSpriteLibrary.addSprite("bonus_bg","./sprites/bonus_bg.jpg");
        s_oSpriteLibrary.addSprite("star_bonus","./sprites/star_bonus.png");
	s_oSpriteLibrary.addSprite("bonus_item","./sprites/bonus_item.png");
        s_oSpriteLibrary.addSprite("but_fullscreen","./sprites/but_fullscreen.png");
        s_oSpriteLibrary.addSprite("but_credits","./sprites/but_credits.png");
        s_oSpriteLibrary.addSprite("msg_box","./sprites/msg_box.png");
        s_oSpriteLibrary.addSprite("logo_ctl","./sprites/logo_ctl.png");
        
        for(var i=1;i<NUM_SYMBOLS+1;i++){
            s_oSpriteLibrary.addSprite("symbol_"+i,"./sprites/symbol_"+i+".png");
            s_oSpriteLibrary.addSprite("symbol_"+i+"_anim","./sprites/symbol_"+i+"_anim.png");
        }
        
        for(var j=1;j<NUM_PAYLINES+1;j++){
            s_oSpriteLibrary.addSprite("payline_"+j,"./sprites/payline_"+j+".png");
        }
		
        //ADD BONUS ITEM
        for(var j=0;j<25;j++){
            s_oSpriteLibrary.addSprite("bonus_item_"+j,"./sprites/bonus_item/bonus_item_"+j+".png");
        }
        
        RESOURCE_TO_LOAD += s_oSpriteLibrary.getNumSprites();

        s_oSpriteLibrary.loadSprites();
    };
    
    this._onImagesLoaded = function(){
        _iCurResource++;

        var iPerc = Math.floor(_iCurResource/RESOURCE_TO_LOAD *100);

        _oPreloader.refreshLoader(iPerc);
        
        if(_iCurResource === RESOURCE_TO_LOAD){
            this._onRemovePreloader();
        }
    };
    
    this._onAllImagesLoaded = function(){
        
    };
    
    this.onAllPreloaderImagesLoaded = function(){
        this._loadImages();
    };
    
    this._onRemovePreloader = function(){
        _oPreloader.unload();

        if (!isIOS()) {
            if (DISABLE_SOUND_MOBILE === false || s_bMobile === false) {
                s_oSoundTrack = createjs.Sound.play("soundtrack", {loop: -1});
            }
        }
        

        this.gotoMenu();
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
    PAYTABLE_VALUES = new Array();
    for(var i=0;i<8;i++){
        PAYTABLE_VALUES[i] = oData["paytable_symbol_"+(i+1)];
    }
    
    ENABLE_FULLSCREEN = _oData.fullscreen;
    ENABLE_CHECK_ORIENTATION = _oData.check_orientation;
    SHOW_CREDITS = _oData.show_credits;

    this.initContainer();
}

var s_bMobile;
var s_bAudioActive = true;
var s_bFullscreen = false;
var s_iCntTime = 0;
var s_iTimeElaps = 0;
var s_iPrevTime = 0;
var s_iCntFps = 0;
var s_iCurFps = 0;

var s_oDrawLayer;
var s_oStage;
var s_oMain;
var s_oSpriteLibrary;
var s_oSoundTrack = null;