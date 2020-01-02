function CInterface(){
    var _oTextHead;
    var _oText;
    var _oTextBack;
    var _oParent;
    var _oAudioToggle;
    var _oButExit;
    var _oBuyCardBut;
    var _oBetDisplay;
    var _oCreditText;
    var _oCreditNum;
    var _oButPlus;
    var _oButMinus;
    var _oTotWinText;
    var _oTotWinNum;
    var _oBetText;
    var _oButPlayAgain;
    var _oButFullscreen;
    
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    var _aX;
    var _aY;
    var _aTextNumber;
    
    var _pStartPosExit;
    var _pStartPosAudio;
    var _pStartPosFullscreen;
    
    this._init = function(){
        
        _oTextHead = new createjs.Text(TEXT_HEAD," 50px "+PRIMARY_FONT, "#ffe603");
        _oTextHead.x = 980;
        _oTextHead.y = 110;
        _oTextHead.textAlign="right";
        _oTextHead.textBaseline = "middle";
        _oTextHead.shadow = new createjs.Shadow("#000000", 2, 2, 2);
        s_oStage.addChild(_oTextHead);
        
        var oSprite = s_oSpriteLibrary.getSprite('fruits_icon');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: 40, height: 40}, 
                        animations: {  symbol0: [0], symbol1:[1], symbol2: [2], symbol3: [3], symbol4: [4],
                            symbol5: [5], symbol6: [6], symbol7: [7], symbol8: [8]}
                   };
        
        var oObjSpriteSheet = new createjs.SpriteSheet(oData);
        
        ///////Icon position///////
        _aX = new Array();
        _aY = new Array();
        
        for (var i=0; i<3; i++){
            var xStart = 352;//170;
            _aX.push(xStart+i*260);
            
        }
        
        for (var i=0; i<3; i++){
            var yStart=150;
            _aY.push(yStart+i*35);
            
        }
        ///////////////////////////
        
        var index=0;
        /////////TEXT OF CURRENCY////////////
        _aTextNumber = new Array();
        for (var i=0; i<3; i++){
            _aTextNumber[i] = new Array();
            for (var j=0; j<3; j++){
                _aTextNumber[i][j] = new createjs.Text(PRIZE[index].formatDecimal(2, ".", ",") +" "+TEXT_CURRENCY," 28px "+PRIMARY_FONT, "#ffffff");
                _aTextNumber[i][j].x = _aX[i]+110;
                _aTextNumber[i][j].y = _aY[j]+10;
                s_oStage.addChild(_aTextNumber[i][j]);
                
                index++;
            }
        }
        this.refreshPayout(BET[0]);
        
        var tag;
        var iCont=0;
        var oIcon;
        
        for (var i=0; i<3; i++){
            for (var j=0; j<3; j++){
                tag = "symbol"+iCont;
                iCont++;
                
                for (var s=0; s<3; s++){
                    oIcon = createSprite(oObjSpriteSheet, tag,0,0,40,40);
                    oIcon.x += _aX[i] + s*30;
                    oIcon.y = _aY[j];
                    oIcon.visible=true;
                    s_oStage.addChild(oIcon);
                }
            }
        }
        
        
        var oExitX;
        
        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
        _pStartPosExit = {x: CANVAS_WIDTH - (oSprite.height/2)- 5, y: (oSprite.height/2) + 5};
        _oButExit = new CGfxButton(_pStartPosExit.x, _pStartPosExit.y, oSprite,s_oStage);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);
        
        oExitX = CANVAS_WIDTH - (oSprite.width/2) - 70;
        _pStartPosAudio = {x: oExitX, y: (oSprite.height/2) + 5};
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            var oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,oSprite,s_bAudioActive, s_oStage);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);          
        }
        
        var doc = window.document;
        var docEl = doc.documentElement;
        _fRequestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        _fCancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

        if(ENABLE_FULLSCREEN === false){
            _fRequestFullScreen = false;
        }

        if (_fRequestFullScreen && screenfull.enabled){            
            oSprite = s_oSpriteLibrary.getSprite("but_fullscreen");
            _pStartPosFullscreen = {x:_pStartPosAudio.x - oSprite.width/2 - 5,y:(oSprite.height/2) + 5};
            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen, s_oStage);
            _oButFullscreen.addEventListener(ON_MOUSE_UP,this._onFullscreenRelease,this);
        }
        
        
        var oSprite = s_oSpriteLibrary.getSprite('but_generic');
        _oBuyCardBut = new CTextToggle(CANVAS_WIDTH/2,485,oSprite,TEXT_BUYCARD,PRIMARY_FONT,"#ffffff",30,false,s_oStage);
        _oBuyCardBut.addEventListener(ON_MOUSE_UP, s_oGame.startPlay, this);        
        
        var pBetPos = {x:CANVAS_WIDTH/2,y:418};
        
        _oBetText = new createjs.Text(TEXT_CARD_VALUE," 28px "+PRIMARY_FONT, "#ffffff");
        _oBetText.x = pBetPos.x;
        _oBetText.y = pBetPos.y-40;
        _oBetText.textAlign="center";
        _oBetText.textBaseline = "middle";
        _oBetText.shadow = new createjs.Shadow("#000000", 2, 2, 2);
        s_oStage.addChild(_oBetText);
        
        var oSprite = s_oSpriteLibrary.getSprite('plus_display');
        _oBetDisplay = new CTextToggle(pBetPos.x,pBetPos.y,oSprite,BET[0].formatDecimal(2, ".", ",")+TEXT_CURRENCY,PRIMARY_FONT,"#ffffff",40,true,s_oStage);        
        _oBetDisplay.setScale(0.6);
        _oBetDisplay.block(true);
        _oBetDisplay.setTextPosition(110,38);        

        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _oButMinus = new CTextToggle(pBetPos.x - 86,pBetPos.y,oSprite,TEXT_MINUS,PRIMARY_FONT,"#ffffff",40,false,s_oStage);
        _oButMinus.addEventListener(ON_MOUSE_UP, this._onButMinRelease, this);
        _oButMinus.setScale(0.6);
        _oButMinus.setScaleX(-1);
        _oButMinus.setTextPosition(-1,-5);
        
        var oSprite = s_oSpriteLibrary.getSprite('but_plus');
        _oButPlus = new CTextToggle(pBetPos.x + 86,pBetPos.y,oSprite,TEXT_PLUS,PRIMARY_FONT,"#ffffff",40,false,s_oStage);
        _oButPlus.addEventListener(ON_MOUSE_UP, this._onButPlusRelease, this);        
        _oButPlus.setScale(0.6);
        _oButPlus.setTextPosition(-1,-5);
        
        
        var pCreditPos = {x:452,y:600};
         
        _oCreditText = new createjs.Text(TEXT_CREDIT," 28px "+PRIMARY_FONT, "#ffffff");
        _oCreditText.x = pCreditPos.x;
        _oCreditText.y = pCreditPos.y-45;
        _oCreditText.textAlign="center";
        _oCreditText.textBaseline = "middle";
        _oCreditText.shadow = new createjs.Shadow("#000000", 2, 2, 2);
        s_oStage.addChild(_oCreditText);
        
        var oSprite = s_oSpriteLibrary.getSprite('plus_display');
        _oCreditNum = new CTextToggle(pCreditPos.x,pCreditPos.y,oSprite,CREDIT.formatDecimal(2, ".", ",")+TEXT_CURRENCY,PRIMARY_FONT,"#ffffff",30,true,s_oStage);        
        _oCreditNum.setScale(0.75);
        _oCreditNum.setTextPosition(110,38);
        _oCreditNum.block(true);
        
        var pTotWinPos = {x:1050,y:600};
         
        _oTotWinText = new createjs.Text(TEXT_WIN_AMOUNT," 28px "+PRIMARY_FONT, "#ffffff");
        _oTotWinText.x = pTotWinPos.x;
        _oTotWinText.y = pTotWinPos.y-45;
        _oTotWinText.textAlign="center";
        _oTotWinText.textBaseline = "middle";
        _oTotWinText.shadow = new createjs.Shadow("#000000", 2, 2, 2);
        s_oStage.addChild(_oTotWinText);
        
        var iStartTotWin = 0;
        var oSprite = s_oSpriteLibrary.getSprite('plus_display');
        _oTotWinNum = new CTextToggle(pTotWinPos.x,pTotWinPos.y,oSprite,iStartTotWin.formatDecimal(2, ".", ",")+TEXT_CURRENCY,PRIMARY_FONT,"#ffffff",30,true,s_oStage);        
        _oTotWinNum.setScale(0.75);
        _oTotWinNum.setTextPosition(110,38);
        _oTotWinNum.block(true);
        
        
        
        
        this.refreshButtonPos(s_iOffsetX,s_iOffsetY);

    };
    
    this.refreshButtonPos = function(iNewX,iNewY){
        _oButExit.setPosition(_pStartPosExit.x - iNewX,iNewY + _pStartPosExit.y);
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,iNewY + _pStartPosAudio.y);
        }
        if (_fRequestFullScreen && screenfull.enabled){
                _oButFullscreen.setPosition(_pStartPosFullscreen.x - iNewX, _pStartPosFullscreen.y + iNewY);
        }
    };
    
    this.refreshPayout = function(iMult){
        var iIndex = 0;
        for (var i=0; i<3; i++){
            for (var j=0; j<3; j++){
                var iPrize = PRIZE[iIndex]*iMult;
                _aTextNumber[i][j].text = iPrize.formatDecimal(2, ".", ",") +" "+TEXT_CURRENCY
                iIndex++;
            }
        }
    };
    
    this.refreshBet = function(szValue){
        _oBetDisplay.setText(szValue.formatDecimal(2, ".", ",")+TEXT_CURRENCY);
    };
    
    this.refreshCredit = function(iCredit){
        _oCreditNum.setText(iCredit.formatDecimal(2, ".", ",")+TEXT_CURRENCY);
    };
    
    this.refreshTotWin = function(iTotWin){
        _oTotWinNum.setText(iTotWin.formatDecimal(2, ".", ",")+TEXT_CURRENCY);
        if(iTotWin > 0){
            _oTotWinNum.highlight();
        }else {
            _oTotWinNum.stopHighlight();
        }
        
    };
    
    this.enableBuyOptions = function(bVal){
        _oBuyCardBut.setVisible(bVal);
        _oBetText.visible = bVal;
        _oButPlus.setVisible(bVal);
        _oButMinus.setVisible(bVal);
        _oBetDisplay.setVisible(bVal);  
    };
    
    this.enablePlayAgain = function(bVal){
        if(bVal){
            var oSprite = s_oSpriteLibrary.getSprite('but_generic');
            _oButPlayAgain = new CTextToggle(CANVAS_WIDTH/2,470,oSprite,TEXT_PLAY_AGAIN,PRIMARY_FONT,"#ffffff",30,false,s_oStage);
            _oButPlayAgain.addEventListener(ON_MOUSE_UP, s_oGame.onPlayAgainBut, this);
        } else {
            _oButPlayAgain.unload();
        }
        
    };
    
    this.enablePlus = function(bVal){
        if(bVal){
            _oButPlus.enable();
        }else {
            _oButPlus.disable();
        }        
    };
    
    this.enableMin = function(bVal){
        if(bVal){
            _oButMinus.enable();
        }else {
            _oButMinus.disable();
        } 
    };
    
    this.advice = function(type,pos){

        var iStartPos = {x:CANVAS_WIDTH + 400, y: 330 + pos*110 };
        
        _oTextBack = new createjs.Text(""," 40px "+PRIMARY_FONT, "#000000");
        _oTextBack.x = iStartPos.x+2;
        _oTextBack.y = iStartPos.y+2;
        _oTextBack.textAlign="center";
        _oText = new createjs.Text(""," 40px "+PRIMARY_FONT, "#ffffff");          
        _oText.x = iStartPos.x;
        _oText.y = iStartPos.y;
        _oText.textAlign="center";
        s_oStage.addChild(_oTextBack);
        s_oStage.addChild(_oText);
        
        if(type==="win"){
            _oTextBack.text = TEXT_ADVICE_WIN;
            _oText.text = TEXT_ADVICE_WIN;
            
        } else {
            _oTextBack.text = TEXT_ADVICE_LOSE;
            _oText.text = TEXT_ADVICE_LOSE;
            
        }

        createjs.Tween.get(_oTextBack).to({x:CANVAS_WIDTH/2+2, y: 332 + pos*110 }, 2000,createjs.Ease.elasticOut);
        createjs.Tween.get(_oText).to({x:CANVAS_WIDTH/2, y: 330 + pos*110 }, 2000,createjs.Ease.elasticOut).call(function(){s_oGame.checkEndScratch()});

    };

    this.unload = function(){
        
        _oButExit.unload();
        _oButExit = null;
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        
        if (_fRequestFullScreen && screenfull.enabled){
                _oButFullscreen.unload();
        }
        
        s_oInterface = null;
    };

    this._onButPlusRelease = function(){
        s_oGame.selectBet("add");
    };
    
    this._onButMinRelease = function(){
        s_oGame.selectBet("remove");
    };
    
    this.resetFullscreenBut = function(){
        if (_fRequestFullScreen && screenfull.enabled){
            _oButFullscreen.setActive(s_bFullscreen);
        }
    };
        
    this._onFullscreenRelease = function(){
	if(s_bFullscreen) { 
		_fCancelFullScreen.call(window.document);
	}else{
		_fRequestFullScreen.call(window.document.documentElement);
	}
	
	sizeHandler();
    };
    
    this._onAudioToggle = function(){
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    
    this._onExit = function(){
        s_oGame.cover();
        new CAreYouSurePanel(s_oInterface._onConfirmExit, s_oGame.uncover);
    };
    
    this._onConfirmExit = function(){
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("share_event", s_iCurCredit);
        s_oGame.onExit();  
    };

    _oParent = this;
    
    s_oInterface =  this;
    this._init();

    
    return this;
}

var s_oInterface = null;