function CBetTextButton(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize,szName){
    var _bDisable;
    var _iWidth;
    var _iHeight;
    var _iBetMultiplier;
    var _iBetWin;
    var _iNumFiches;
    var _szName;
    var _aNumbersToBet;
    var _aCbCompleted;
    var _aCbOwner;
    var _oButton;
    var _oTextBack;
    var _oText;
    var _oButtonBg;
    
    this._init =function(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize,szName){
        _bDisable = false;
        _aCbCompleted=new Array();
        _aCbOwner =new Array();

        _oButtonBg = createBitmap( oSprite);
	_iWidth = oSprite.width;
        _iHeight = oSprite.height;
		
        var iStepShadow = Math.ceil(iFontSize/20);

        _oTextBack = new createjs.Text(szText,iFontSize+"px "+szFont, "#000000");
        _oTextBack.textAlign = "center";
        _oTextBack.lineWidth = _iWidth *0.8;
        var oBounds = _oTextBack.getBounds();    
        _oTextBack.x = oSprite.width/2 + iStepShadow;
        _oTextBack.y = ((oSprite.height) - oBounds.height)/2 + iStepShadow;

        _oText = new createjs.Text(szText,iFontSize+"px "+szFont, szColor);
        _oText.textAlign = "center";
        _oText.lineWidth = _iWidth *0.8;
        var oBounds = _oText.getBounds();    
        _oText.x = oSprite.width/2;
        _oText.y = ((oSprite.height) - oBounds.height)/2;

        _oButton = new createjs.Container();
        _oButton.x = iXPos;
        _oButton.y = iYPos;
        _oButton.regX = oSprite.width/2;
        _oButton.regY = oSprite.height/2;
	
        if (!s_bMobile){
            _oButton.cursor = "pointer";
	}
        _oButton.addChild(_oButtonBg,_oTextBack,_oText);

        s_oStage.addChild(_oButton);

        this._initListener();
        
        _szName = szName;
        _aNumbersToBet=new Array();
        _aNumbersToBet=szName.split("_");
        if(_aNumbersToBet.length>1){
                _aNumbersToBet.splice(0,1);
        }else{
                this._assignNumber(szName);
        }
        _iBetMultiplier = s_oGameSettings.getBetMultiplierForButton(szName);
        _iBetWin = s_oGameSettings.getBetWinForButton(szName);
        _iNumFiches = s_oGameSettings.getNumFichesPerBet(szName);
    };
    
    this._assignNumber = function(szName){
        _aNumbersToBet = s_oGameSettings.getNumbersForButton(szName);
    };
    
    this.unload = function(){
       _oButton.off("mousedown");
       _oButton.off("pressup");
       
       s_oStage.removeChild(_oButton);
    };
    
    this.setVisible = function(bVisible){
        _oButton.visible = bVisible;
    };
    
    this.enable = function(){
        _bDisable = false;
		
	_oButtonBg.filters = [];

        _oButtonBg.cache(0,0,_iWidth,_iHeight);
    };
    
    this.disable = function(){
        _bDisable = true;
		
	var matrix = new createjs.ColorMatrix().adjustSaturation(-100).adjustBrightness(40);
        _oButtonBg.filters = [
                 new createjs.ColorMatrixFilter(matrix)
        ];
        _oButtonBg.cache(0,0,_iWidth,_iHeight);
    };
    
    this._initListener = function(){
       oParent = this;

       _oButton.on("mousedown", this.buttonDown);
       _oButton.on("pressup" , this.buttonRelease);      
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.buttonRelease = function(){
        if(_bDisable){
            return;
        }
        
        playSound("click",1,false);
        
        _oButton.scaleX = 1;
        _oButton.scaleY = 1;

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP],
                                    {name:_szName,numbers:_aNumbersToBet,bet_mult:_iBetMultiplier,bet_win:_iBetWin,num_fiches:_iNumFiches},false);
        }
    };
    
    this.buttonDown = function(){
        if(_bDisable){
            return;
        }
        _oButton.scaleX = 0.9;
        _oButton.scaleY = 0.9;

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN]);
       }
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oButton.x = iXPos;
         _oButton.y = iYPos;
    };
    
    this.changeText = function(szText){
        _oText.text = szText;
        _oTextBack.text = szText;
    };
    
    this.setX = function(iXPos){
         _oButton.x = iXPos;
    };
    
    this.setY = function(iYPos){
         _oButton.y = iYPos;
    };
    
    this.getButtonImage = function(){
        return _oButton;
    };

    this.getX = function(){
        return _oButton.x;
    };
    
    this.getY = function(){
        return _oButton.y;
    };

    this._init(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize,szName);
    
    return this;
    
}