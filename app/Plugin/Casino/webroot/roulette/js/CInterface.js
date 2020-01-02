function CInterface(){
    var _iIndexFicheSelected;
    var _aFiches;
    var _aHistoryRows;
    
    var _oButExit;
    var _oAudioToggle;
    var _oTimeTextBack;
    var _oTimeText;
    var _oMoneyText;
    var _oMsgTitle;
    var _oMsgText;
    var _oDisplayBg;
    var _oSpinBut;
    var _oClearLastBet;
    var _oClearAllBet;
    var _oBetFinalsBet;
    var _oBetNeighbors;
    var _oBetOrphelins;
    var _oBetTier;
    var _oBetVoisinsZero;
    var _oRebetBut;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    this._init = function(){
        
        _oMoneyText = new createjs.Text("","12px "+FONT1, "#fff");
        _oMoneyText.textAlign = "center";
        _oMoneyText.x = CANVAS_WIDTH - 56;
        _oMoneyText.y = CANVAS_HEIGHT - 35;
        s_oStage.addChild(_oMoneyText);
        
        _oDisplayBg = createBitmap(s_oSpriteLibrary.getSprite('display_bg'));
        _oDisplayBg.x = 235;
        _oDisplayBg.y = 4;
        s_oStage.addChild(_oDisplayBg);

        _oMsgTitle = new createjs.Text("","20px "+FONT2, "#ffde00");
        _oMsgTitle.textAlign = "center";
        _oMsgTitle.lineHeight = 20;
        _oMsgTitle.x = _oDisplayBg.x + 160;
        _oMsgTitle.y = _oDisplayBg.y + 8;
        s_oStage.addChild(_oMsgTitle);

        _oMsgText = new createjs.Text("","16px "+FONT2, "#ffde00");
        _oMsgText.textAlign = "left";
        _oMsgText.lineHeight = 14;
        _oMsgText.x = _oDisplayBg.x + 120;
        _oMsgText.y = _oDisplayBg.y + 60;
        s_oStage.addChild(_oMsgText);
        
        _oSpinBut = new CGfxButton(575,221,s_oSpriteLibrary.getSprite('spin_but'));
        _oSpinBut.setVisible(false);
        _oSpinBut.addEventListener(ON_MOUSE_UP, this._onSpin, this);

        _oClearLastBet = new CTextButton(81,309,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_CLEAR_LAST_BET,FONT1,"#fff",14);
        _oClearLastBet.addEventListener(ON_MOUSE_UP, this._onClearLastBet, this);
        
        _oClearAllBet = new CTextButton(81,342,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_CLEAR_ALL_BET,FONT1,"#fff",14);
        _oClearAllBet.addEventListener(ON_MOUSE_UP, this._onClearAllBet, this);
        
        _oBetVoisinsZero= new CBetTextButton(81,447,s_oSpriteLibrary.getSprite('but_game_bg'), TEXT_VOISINS_ZERO,FONT1,"#fff",14,"oBetVoisinsZero");
        _oBetVoisinsZero.addEventListener(ON_MOUSE_UP, this._onBetRelease, this);
        
        _oBetOrphelins = new CBetTextButton(81,515,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_ORPHELINS,FONT1,"#fff",14,"oBetOrphelins");
        _oBetOrphelins.addEventListener(ON_MOUSE_UP, this._onBetRelease, this);
        
        _oBetTier = new CBetTextButton(81,481,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_TIER,FONT1,"#fff",14,"oBetTier");
        _oBetTier.addEventListener(ON_MOUSE_UP, this._onBetRelease, this);
        
        _oBetFinalsBet = new CTextButton(81,582,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_FINALSBET,FONT1,"#fff",14);
        _oBetFinalsBet.addEventListener(ON_MOUSE_UP, this._onFinalBetShow, this);
        
        _oBetNeighbors = new CTextButton(81,549,s_oSpriteLibrary.getSprite('but_game_bg'),TEXT_NEIGHBORS,FONT1,"#fff",14);
        _oBetNeighbors.addEventListener(ON_MOUSE_UP, this._onNeighborsPanel, this);
        
        _oRebetBut = new CTextButton(692,538,s_oSpriteLibrary.getSprite('but_game_small'),TEXT_REBET,FONT1,"#fff",14);
        _oRebetBut.disable();
        _oRebetBut.addEventListener(ON_MOUSE_UP, this._onRebet, this);
        
        this._initFichesBut();
        this.disableBetFiches();
	this._initNumExtracted();
        
        _iIndexFicheSelected=0;
        _aFiches[_iIndexFicheSelected].select();

        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _oButExit = new CGfxButton((oSprite.width/2) + 5,(oSprite.height/2) + 5,oSprite,true);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        var pStartPosFullscreen = {};
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle = new CToggle(_oButExit.getX() + oSprite.width + 5,(oSprite.height/2) + 4,s_oSpriteLibrary.getSprite('audio_icon'));
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);
            
            pStartPosFullscreen = {x:_oAudioToggle.getX() + oSprite.width +5,y:_oAudioToggle.getY()};
        }else{
            pStartPosFullscreen = {x:_oButExit.getX() + oSprite.width + 5,y:(oSprite.height/2) + 4};
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
            _oButFullscreen = new CToggle(pStartPosFullscreen.x,pStartPosFullscreen.y,oSprite,s_bFullscreen,true);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
    };
    
    this.unload = function(){
        _oButExit.unload();
	if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
        }
        _oSpinBut.unload();
        _oClearLastBet.unload();
        _oClearAllBet.unload();
        _oBetFinalsBet.unload();
        _oBetNeighbors.unload();
        _oBetOrphelins.unload();
        _oBetTier.unload();
        _oBetVoisinsZero.unload();
        _oRebetBut.unload();
        if (_fRequestFullScreen && inIframe() === false){
            _oButFullscreen.unload();
        }
    };
    
    this.enableBetFiches = function(){
        for(var i=0;i<NUM_FICHE_VALUES;i++){
            _aFiches[i].enable();
        }
        _oClearLastBet.enable();
        _oClearAllBet.enable();

        _oBetFinalsBet.enable();
        _oBetNeighbors.enable();
        _oBetOrphelins.enable();
        _oBetTier.enable();
        _oBetVoisinsZero.enable();
    };
    
    this.disableBetFiches = function(){
        for(var i=0;i<NUM_FICHE_VALUES;i++){
            _aFiches[i].disable();
        }
        _oClearLastBet.disable();
        _oClearAllBet.disable();

        _oBetFinalsBet.disable();
        _oBetNeighbors.disable();
        _oBetOrphelins.disable();
        _oBetTier.disable();
        _oBetVoisinsZero.disable();
        _oRebetBut.disable();
    };
    
    this.enableRebet = function(){
        _oRebetBut.enable();
    };
    
    this.disableRebet = function(){
        _oRebetBut.disable();
    };

    this._initNumExtracted = function(){
        _aHistoryRows = new Array();
        
        var iXPos = 672;
        var iYPos = 11;
        for(var i=0;i<12;i++){
            var oRow = new CHistoryRow(iXPos,iYPos);
            _aHistoryRows[i] = oRow;
            iYPos += 22;
        }
    };
    
    this.deselectAllFiches = function(){
         for(var i=0;i<NUM_FICHES;i++){
             _aFiches[i].deselect();
         }
    };
    
    this.enableSpin = function(bEnable){
        _oSpinBut.setVisible(bEnable);
    };
    
    this._initFichesBut = function(){
        //SET FICHES BUTTON
        var aPos = [{x:296,y:410},{x:324,y:434},{x:352,y:456},{x:381,y:477},{x:409,y:500},{x:438,y:521}];
        _aFiches = new Array();
        for(var i=0;i<NUM_FICHES;i++){
            var oSprite = s_oSpriteLibrary.getSprite('fiche_'+i);
            _aFiches[i] = new CFicheBut(aPos[i].x,aPos[i].y,oSprite);
            _aFiches[i].addEventListenerWithParams(ON_MOUSE_UP, this._onFicheSelected, this,[i]);
        }
    };
    
    this.refreshTime = function(iTime){
        var szTime = formatTime(iTime);
        _oTimeText.text =  szTime;
        _oTimeTextBack.text = szTime;
    };

    this.refreshMoney = function(iMoney){
        _oMoneyText.text = TEXT_MONEY +"\n"+iMoney+"$";
    };
    
    this.displayAction = function(szText1,szText2){
        _oMsgTitle.text=szText1;
        _oMsgText.text=szText2;
    };
    
    this.showWin = function(iWinAmount){
        this.displayAction(TEXT_DISPLAY_MSG_PLAYER_WIN+"\n"+iWinAmount+"$");
    };
    
    this.showLose = function(){
        this.displayAction(TEXT_DISPLAY_MSG_PLAYER_LOSE);
    };
    
    this.refreshNumExtracted = function(aNumExtracted){
        var iLen=aNumExtracted.length-1;
        //TAKE ONLY THE FIRST 12 NUMBERS EXTRACTED
        var iLastNum=-1;

        if(aNumExtracted.length>11){
                iLastNum=iLen-12;
        }

        var iCurIndex=0;
        for(var i=iLen;i>iLastNum;i--){
            switch(s_oGameSettings.getColorNumber(aNumExtracted[i])){
                case COLOR_BLACK:{
                    _aHistoryRows[iCurIndex].showBlack(aNumExtracted[i]);
                    break;
                }
                case COLOR_RED:{
                    _aHistoryRows[iCurIndex].showRed(aNumExtracted[i]);
                    break;
                }
                case COLOR_ZERO:{
                    _aHistoryRows[iCurIndex].showGreen(aNumExtracted[i]);
                    break;
                }
            }

            iCurIndex++;
        }


    };
    
    this.gameOver = function(){
        
    };
    
    this._onBetRelease = function(oParams){
        var aBets=oParams.numbers;
        var iBetMult=oParams.bet_mult;
        var iBetWin=oParams.bet_win;
        if(aBets !== null){
            s_oGame._onShowBetOnTable({button:oParams.name,numbers:aBets,bet_mult:iBetMult,bet_win:iBetWin,num_fiches:oParams.num_fiches},false);
        }
    };
    
    this._onFicheSelected = function(aParams){
        createjs.Sound.play("fiche_select");
        this.deselectAllFiches();
        
        var iFicheIndex=aParams[0];

        for(var i=0;i<NUM_FICHE_VALUES;i++){
            if(i === iFicheIndex){
               _iIndexFicheSelected = i;
            }
        }
    };
    
    this._onSpin = function(){
            this.disableBetFiches();
            this.enableSpin(false);
            s_oGame.onSpin();    
    };
    
    this._onClearLastBet = function(){
        s_oGame.onClearLastBet();
    };
    
    this._onClearAllBet = function(){
        s_oGame.onClearAllBets();
    };
    
    this._onFinalBetShow = function(){
        s_oGame.onFinalBetShown();
    };
    
    this._onNeighborsPanel = function(){
        s_oGame.onOpenNeighbors();
    };
    
    this._onRebet = function(){
        _oRebetBut.disable();
        s_oGame.onRebet();
    };

    this._onExit = function(){
		window.location.href = '/eng/casino/content';
        s_oGame.onExit();  
    };
    
    this.getCurFicheSelected = function(){
        return _iIndexFicheSelected;
    };
    
    this._onAudioToggle = function(){
        createjs.Sound.setMute(!s_bAudioActive);
    };
    
    this._onFullscreenRelease = function(){
        if(s_bFullscreen) { 
            _fCancelFullScreen.call(window.document);
            s_bFullscreen = false;
        }else{
            _fRequestFullScreen.call(window.document.documentElement);
            s_bFullscreen = true;
        }
        
        sizeHandler();
    };
    
    s_oInterface = this;
    
    this._init();
    
    return this;
}

var s_oInterface;