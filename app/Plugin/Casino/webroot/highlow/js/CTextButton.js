function CTextButton(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize, bStandard, oParentContainer){
    
    var _bDisable;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oButton;
    var _oText;
    var _oTextBack;
    var _oButtonBg;
    
    this._init =function(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize, bStandard, oParentContainer){
        _bDisable = false;
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();

        _oButtonBg = createBitmap( oSprite);           

        var iStepShadow = Math.ceil(iFontSize/20);

        _oTextBack = new createjs.Text(szText,iFontSize+"px "+szFont, "#000000");
        _oTextBack.textAlign = "center";
        _oTextBack.textBaseline = "alphabetic";
        var oBounds = _oTextBack.getBounds();    
        _oTextBack.x = oSprite.width/2 + iStepShadow;
        _oTextBack.y = Math.floor((oSprite.height)/2) +(oBounds.height/3) + iStepShadow -7;

        _oText = new createjs.Text(szText,iFontSize+"px "+szFont, szColor);
        _oText.textAlign = "center";
        _oText.textBaseline = "alphabetic";
        var oBounds = _oText.getBounds();    
        _oText.x = oSprite.width/2;
        _oText.y = Math.floor((oSprite.height)/2) +(oBounds.height/3) - 7;

        _oButton = new createjs.Container();
        _oButton.x = iXPos;
        _oButton.y = iYPos;
        _oButton.regX = oSprite.width/2;
        _oButton.regY = oSprite.height/2;        
        _oButton.cursor = "pointer";
        if(!bStandard){
            var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/2, height: oSprite.height, regX:(oSprite.width/2)/2, regY:oSprite.height/2}, 
                        animations: {state_true:[0],state_false:[1]}
                   };
                   
            var oSpriteSheet = new createjs.SpriteSheet(oData);         
            _oButtonBg = createSprite(oSpriteSheet, "state_false",(oSprite.width/2)/2,oSprite.height/2,oSprite.width/2,oSprite.height);
            
            var iOffset = 7;
            
            _oTextBack.x = iStepShadow - iOffset;
            _oTextBack.y = iStepShadow + iOffset;
            _oText.x = -iOffset;
            _oText.y = iOffset;
            _oButton.regX = 0;
            _oButton.regY = 0;
            
        }
        _oButton.addChild(_oButtonBg,_oTextBack,_oText);

        oParentContainer.addChild(_oButton);

        this._initListener();
    };
    
    this.unload = function(){
       _oButton.off("mousedown");
       _oButton.off("pressup");
       
       oParentContainer.removeChild(_oButton);
    };
    
    this.setVisible = function(bVisible){
        _oButton.visible = bVisible;
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
        _oButton.scaleX = 1;
        _oButton.scaleY = 1;

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP]);
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
    
    this.enable = function(){
        _bDisable = false;
        
        if(!bStandard){
            _oButtonBg.gotoAndStop("state_true");
        }
        

    };
    
    this.disable = function(){
        _bDisable = true;
        if(!bStandard){
            _oButtonBg.gotoAndStop("state_false");
        }

    };
    
    this.setTextPosition = function(iX, iY){
        
        var iStepShadow = Math.ceil(iFontSize/20);
        
        _oTextBack.x = iX + iStepShadow;
        _oTextBack.y = iY + iStepShadow;
        _oText.x = iX;
        _oText.y = iY;
        
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

    this._init(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize, bStandard, oParentContainer);
    
    return this;
    
}
