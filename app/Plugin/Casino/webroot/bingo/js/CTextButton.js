function CTextButton(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize,iOffsetY,oParentContainer){
    var _bDisable;
    var _iWidth;
    var _iHeight;
    var _iScale;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oButton;
    var _oText;
    var _oTextBack;
    var _oParentContainer;
    
    this._init =function(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize,iOffsetY){
        _bDisable = false;
        
        _iScale = 1;
        _iWidth = oSprite.width;
        _iHeight = oSprite.height;
        _aCbCompleted=new Array();
        _aCbOwner =new Array();

        var oButtonBg = createBitmap( oSprite);

        var iStepShadow = Math.ceil(iFontSize/20);

        _oTextBack = new createjs.Text(szText,iFontSize+"px "+szFont, "#000000");
        _oTextBack.textAlign = "center";
        _oTextBack.textBaseline = "alphabetic";
        var oBounds = _oTextBack.getBounds();    
        _oTextBack.x = oSprite.width/2 + iStepShadow;
        _oTextBack.y = Math.floor((oSprite.height)/2) +(oBounds.height/3) + iStepShadow - iOffsetY;

        _oText = new createjs.Text(szText,iFontSize+"px "+szFont, szColor);
        _oText.textAlign = "center";
        _oText.textBaseline = "alphabetic";
        var oBounds = _oText.getBounds();    
        _oText.x = oSprite.width/2;
        _oText.y = Math.floor((oSprite.height)/2) +(oBounds.height/3) - iOffsetY;

        _oButton = new createjs.Container();
        _oButton.x = iXPos;
        _oButton.y = iYPos;
        _oButton.regX = oSprite.width/2;
        _oButton.regY = oSprite.height/2;
        _oButton.cursor = "pointer";
        _oButton.addChild(oButtonBg,_oTextBack,_oText);

        _oParentContainer.addChild(_oButton);

        this._initListener();
    };
    
    this.unload = function(){
       _oButton.off("mousedown");
       _oButton.off("pressup");
       
       _oParentContainer.removeChild(_oButton);
    };
    
    this.setVisible = function(bVisible){
        _oButton.visible = bVisible;
    };
    
    this._initListener = function(){
       _oButton.on("mousedown", this.buttonDown);
       _oButton.on("pressup" , this.buttonRelease);      
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.enable = function(){
        _bDisable = false;
		
	_oButton.filters = [];

        _oButton.cache(0,0,_iWidth,_iHeight);
    };
    
    this.disable = function(){
        _bDisable = true;
		
	var matrix = new createjs.ColorMatrix().adjustSaturation(-100).adjustBrightness(40);
        _oButton.filters = [
                                new createjs.ColorMatrixFilter(matrix)
                           ];
        _oButton.cache(0,0,_iWidth,_iHeight);
    };
    
    this.buttonRelease = function(){
        if(_bDisable){
            return;
        }
        
        playSound("press_but",1,0);
        
        _oButton.scaleX = 1*_iScale;
        _oButton.scaleY = 1*_iScale;

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP]);
        }
    };
    
    this.buttonDown = function(){
        if(_bDisable){
            return;
        }
        
        _oButton.scaleX = 0.9*_iScale;
        _oButton.scaleY = 0.9*_iScale;

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN]);
       }
    };
    
    
    this.setTextPosition = function(iY){
        _oText.y= iY;
        _oTextBack.y = iY+2;
    };
    
    this.setText = function(szText){
        _oText.text = szText;
        _oTextBack.text = szText;
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oButton.x = iXPos;
         _oButton.y = iYPos;
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

    this.setScale = function(iVal){
        _iScale = iVal;
        _oButton.scaleX = iVal;
        _oButton.scaleY = iVal;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize,iOffsetY);
    
    return this;
    
}
