function CInterface(){
    
    var _aButFiche;
    
    var _oAudioToggle;
    var _oButExit;
    var _oButClearBet;
    var _oButGiveup;
    var _oButAllin;
    var _oMoneyTextBack;
    var _oMoneyText;
    var _oMakeTextBack;
    var _oMakeText;
    var _oButArrowHigh;
    var _oButArrowLow;
    var _oBlockHighLow;
    var _oBlockFiche;
    var _oInfoContainer;
    var _oTurnText;
    var _oHighsText;
    var _oLowsText;
    var _oGuessText;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    var _pStartPosExit;
    var _pStartPosAudio;
    var _pStartPosFullscreen;
    
    this._init = function(){                
        var oExitX;
        
        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _pStartPosExit = {x: CANVAS_WIDTH - (oSprite.height/2)- 10, y: (oSprite.height/2) + 10};
        _oButExit = new CGfxButton(_pStartPosExit.x,_pStartPosExit.y,oSprite,true);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        oExitX = CANVAS_WIDTH - (oSprite.width/2) - 80;
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            var oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _pStartPosAudio = {x: oExitX, y: (oSprite.height/2) + 10};
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,oSprite,s_bAudioActive);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);          
        }
        
        var doc = window.document;
        var docEl = doc.documentElement;
        _fRequestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        _fCancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
        
        if(ENABLE_FULLSCREEN === false){
            _fRequestFullScreen = false;
        }
        
        if (_fRequestFullScreen && inIframe() === false){
            oSprite = s_oSpriteLibrary.getSprite('but_fullscreen');
            _pStartPosFullscreen = {x:oSprite.width/4 + 10,y:oSprite.height/2 + 10};

            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen,true);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
        
        var oSprite = s_oSpriteLibrary.getSprite('but_bet');
        _oButAllin = new CTextButton(1237,CANVAS_HEIGHT -46,oSprite,TEXT_ALLIN,PRIMARY_FONT,"#ffffff",40, false, s_oStage);
        _oButAllin.addEventListener(ON_MOUSE_UP, this._onButAllinRelease, this);
        _oButAllin.enable();

        var oSprite = s_oSpriteLibrary.getSprite('but_clear_bet');
        _oButClearBet = new CTextButton(337,CANVAS_HEIGHT -63,oSprite,TEXT_CLEARBET,PRIMARY_FONT,"#ffffff",20, false, s_oStage);
        _oButClearBet.setTextPosition(0, 0);
        _oButClearBet.addEventListener(ON_MOUSE_UP, this._onButClearBetRelease, this);
        _oButClearBet.enable();
        
        var oSprite = s_oSpriteLibrary.getSprite('but_clear_bet');
        _oButGiveup = new CTextButton(337,CANVAS_HEIGHT -19,oSprite,TEXT_GIVEUP,PRIMARY_FONT,"#ffffff",20, false, s_oStage);
        _oButGiveup.setTextPosition(0, 0);
        _oButGiveup.addEventListener(ON_MOUSE_UP, this._onButGiveupRelease, this);
        _oButGiveup.enable();
        
        var oSprite = s_oSpriteLibrary.getSprite('arrow_high');
        _oButArrowHigh = new CGfxButton(CANVAS_WIDTH/2 - (oSprite.height/2)- 100, CANVAS_HEIGHT/2 - (oSprite.height/2) - 5,oSprite,false);        
        _oButArrowHigh.addEventListener(ON_MOUSE_UP, this._onArrowHigh, this);

        var oSprite = s_oSpriteLibrary.getSprite('arrow_low');
        _oButArrowLow = new CGfxButton(CANVAS_WIDTH/2 - (oSprite.height/2) + 235, CANVAS_HEIGHT/2 - (oSprite.height/2) + 85,oSprite,false);
        _oButArrowLow.addEventListener(ON_MOUSE_UP, this._onArrowLow, this);

        _oBlockHighLow = new createjs.Shape();
        _oBlockHighLow.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(0, 0, 135, 135);
        _oBlockHighLow.visible = false;
        _oBlockHighLow.on("mousedown",function(){});
        s_oStage.addChild(_oBlockHighLow);

        this.enableArrow(false);

        var _aButFiche = new Array();
        for(var i=0; i<5; i++){
            var iTag = "fiche_" + i;
            var oSprite = s_oSpriteLibrary.getSprite(iTag);
            _aButFiche[i] = new CGfxButton(490+i*50, 639,oSprite, true);
            _aButFiche[i].addEventListenerWithParams(ON_MOUSE_UP, this._onFicheClicked, this, i);
        }       

        _oBlockFiche = new createjs.Shape();
        _oBlockFiche.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(420, 614, CANVAS_WIDTH, 150);
        _oBlockFiche.visible = false;
        _oBlockFiche.on("mousedown",function(){});
        s_oStage.addChild(_oBlockFiche);

        _oMakeTextBack = new createjs.Text(TEXT_MAKE,"28px "+PRIMARY_FONT, "#000000");
        _oMakeTextBack.x = 594;
        _oMakeTextBack.y = 601;
        _oMakeTextBack.textAlign = "center";
        _oMakeTextBack.textBaseline = "alphabetic";
        _oMakeTextBack.lineWidth = 300;
        s_oStage.addChild(_oMakeTextBack);
        
        _oMakeText = new createjs.Text(TEXT_MAKE,"28px "+PRIMARY_FONT, "#ffffff");
        _oMakeText.x = 592;
        _oMakeText.y = 599;
        _oMakeText.textAlign = "center";
        _oMakeText.textBaseline = "alphabetic";
        _oMakeText.lineWidth = 300;
        s_oStage.addChild(_oMakeText);

        var oSprite = s_oSpriteLibrary.getSprite('money_panel');
        var oMoneyPanel = createBitmap(oSprite);
        oMoneyPanel.x = 282;
        oMoneyPanel.y = 659;
        s_oStage.addChild(oMoneyPanel);
        
        _oMoneyTextBack = new createjs.Text(TEXT_CURRENCY + " " + START_MONEY.toFixed(2),"32px "+PRIMARY_FONT, "#000000");
        _oMoneyTextBack.x = 585;
        _oMoneyTextBack.y = 694;
        _oMoneyTextBack.textAlign = "center";
        _oMoneyTextBack.textBaseline = "alphabetic";
        _oMoneyTextBack.lineWidth = 200;
        s_oStage.addChild(_oMoneyTextBack);
        
        _oMoneyText = new createjs.Text(TEXT_CURRENCY + " " + START_MONEY.toFixed(2),"32px "+PRIMARY_FONT, "#ffffff");
        _oMoneyText.x = 582;
        _oMoneyText.y = 691;
        _oMoneyText.textAlign = "center";
        _oMoneyText.textBaseline = "alphabetic";
        _oMoneyText.lineWidth = 200;
        s_oStage.addChild(_oMoneyText);
        
        _oInfoContainer = new createjs.Container();
        _oInfoContainer.x = 242;
        _oInfoContainer.y = 74;
        s_oStage.addChild(_oInfoContainer);  
        
        var oSprite = s_oSpriteLibrary.getSprite('panel_high_sx');
        var oInfoPanel = createBitmap(oSprite);        
        _oInfoContainer.addChild(oInfoPanel);        
        
        _oTurnText = new createjs.Text(TEXT_TURN + "1","24px "+PRIMARY_FONT, "#ffffff");
        _oTurnText.x = 140;
        _oTurnText.y = 25;
        _oTurnText.textAlign = "center";
        _oTurnText.textBaseline = "alphabetic";
        _oTurnText.lineWidth = 400;
        _oInfoContainer.addChild(_oTurnText);
        
        _oHighsText = new createjs.Text(TEXT_HIGHS + "0/0","18px "+PRIMARY_FONT, "#ffffff");
        _oHighsText.x = 10;
        _oHighsText.y = 50;
        _oHighsText.textAlign = "left";
        _oHighsText.textBaseline = "alphabetic";
        _oHighsText.lineWidth = 400;
        _oInfoContainer.addChild(_oHighsText);
        
        _oLowsText = new createjs.Text(TEXT_LOWS +"0/0","18px "+PRIMARY_FONT, "#ffffff");
        _oLowsText.x = 10;
        _oLowsText.y = 75;
        _oLowsText.textAlign = "left";
        _oLowsText.textBaseline = "alphabetic";
        _oLowsText.lineWidth = 400;
        _oInfoContainer.addChild(_oLowsText);
        
        _oGuessText = new createjs.Text(TEXT_GUESS + "0","18px "+PRIMARY_FONT, "#ffffff");
        _oGuessText.x = 10;
        _oGuessText.y = 100;
        _oGuessText.textAlign = "left";
        _oGuessText.textBaseline = "alphabetic";
        _oGuessText.lineWidth = 400;
        _oInfoContainer.addChild(_oGuessText);
        
        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
        
    };
    
    this.refreshButtonPos = function(iNewX,iNewY){

        _oButExit.setPosition(_pStartPosExit.x - iNewX,_pStartPosExit.y +iNewY);        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,_pStartPosAudio.y+iNewY);
        }       
        
        if (_fRequestFullScreen && inIframe() === false){
            _oButFullscreen.setPosition(_pStartPosFullscreen.x + iNewX,_pStartPosFullscreen.y + iNewY);
        }
    };
    
    this.unload = function(){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        
        if (_fRequestFullScreen && inIframe() === false){
            _oButFullscreen.unload();
        }
        
        _oButExit.unload();
        _oButAllin.unload();
        _oButClearBet.unload();
        _oButGiveup.unload();
        _oButArrowHigh.unload();
        _oButArrowLow.unload();
        
        for(var i=0; i<FICHE_VALUE.length; i++){
            _aButFiche[i].unload();
        }
        
        _oBlockFiche.off("mousedown",function(){});
        _oBlockHighLow.off("mousedown",function(){});
        
        s_oStage.removeChild(_oInfoContainer);
        
        s_oInterface = null;
    };
    
    this.resetFullscreenBut = function(){
        _oButFullscreen.setActive(s_bFullscreen);
    };
    
    this.refreshMoney = function(iValue){
        _oMoneyTextBack.text = TEXT_CURRENCY + " " + iValue.toFixed(2);
        _oMoneyText.text = TEXT_CURRENCY + " " + iValue.toFixed(2);
    };

    this.refreshTurn = function(iValue){
        _oTurnText.text = TEXT_TURN + iValue;    
    };  
    
    this.refreshHighs = function(iGuess, iMax, iRatio){
        _oHighsText.text = TEXT_HIGHS + iGuess+'/'+iMax + " (" +iRatio+"%)";
    };
    
    this.refreshLows = function(iGuess, iMax, iRatio){
        _oLowsText.text = TEXT_LOWS + iGuess+'/'+iMax + " (" +iRatio+"%)";
    };
    
    this.refreshGuess = function(iValue, iRatio){
        _oGuessText.text = TEXT_GUESS + iValue + " (" +iRatio+"%)";
    };
    
    this.disableAllIn = function(){
        _oButAllin.disable();
    };
    
    this.initState = function(){
        _oButAllin.enable();
        _oButClearBet.enable();
        _oButGiveup.enable();
        _oBlockHighLow.visible=false;
        _oMakeTextBack.visible = true;
        _oMakeText.visible = true;
        this.enableArrow(false);
        this._enableFiche(true);
    };
    
    this._onButAllinRelease = function () {
        _oButAllin.disable();
        _oButGiveup.disable();
        _oMakeTextBack.visible = false;
        _oMakeText.visible = false;
        this.enableArrow(true);
        this._enableFiche(false);
        s_oGame.updateCurBet(-1);
    };
    
    this._onButGiveupRelease = function(){
        s_oGame.onGiveUp();
    };
    
    this._onButClearBetRelease = function(){
        s_oGame.resetBet();
        _oButAllin.enable();
        _oButGiveup.enable();
        _oMakeTextBack.visible = true;
        _oMakeText.visible = true;
        this.enableArrow(false);
        this._enableFiche(true);
    };
    
    this._onArrowHigh = function(){
        _oButArrowLow.disable();
        _oButClearBet.disable();
        _oButGiveup.disable();
        this._enableFiche(false);
        _oBlockHighLow.x = CANVAS_WIDTH/2 - 230; 
        _oBlockHighLow.y = CANVAS_HEIGHT/2 - 140;
        _oBlockHighLow.visible = true;
        s_oGame.onPlayerSelection("high");
    };
    
    this._onArrowLow = function(){
        _oButArrowHigh.disable();
        _oButClearBet.disable();
        _oButGiveup.disable();
        this._enableFiche(false);
        _oBlockHighLow.x = CANVAS_WIDTH/2 + 100; 
        _oBlockHighLow.y = CANVAS_HEIGHT/2 - 50;
        _oBlockHighLow.visible = true;
        s_oGame.onPlayerSelection("low");
    };
    
    this._enableFiche = function(bEnable){

        if(bEnable){
            _oBlockFiche.visible = false;
        } else {
            _oBlockFiche.visible = true;

        }   
    };
    
    this.enableArrow = function(bEnable){
        if(bEnable){
            _oButArrowHigh.enable();
            _oButArrowLow.enable();
        } else {
            _oButArrowHigh.disable();
            _oButArrowLow.disable();
        }
    };
    
    this._onFicheClicked = function(iIndex){
        playSound("chip",1,false);
        
        _oMakeTextBack.visible = false;
        _oMakeText.visible = false;
        
        this.enableArrow(true);
        
        _oButAllin.enable();
        _oButGiveup.disable();
        var iValue = s_oGameSettings.getFichesValueAt(iIndex);
        s_oGame.updateCurBet(iValue);
    };

    this._onAudioToggle = function(){
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    
    this._onExit = function(){
        $(s_oMain).trigger("end_level",1);
        $(s_oMain).trigger("end_session");
		window.location.href = '/eng/casino/content';
        s_oGame.onExit(); 
    };
    
    this._onFullscreenRelease = function(){
	if(s_bFullscreen) { 
		_fCancelFullScreen.call(window.document);
	}else{
		_fRequestFullScreen.call(window.document.documentElement);
	}
	
	sizeHandler();
    };
    
    s_oInterface = this;
    this._init();
    
    return this;
}

var s_oInterface = null;