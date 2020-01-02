function CTextToggle(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize, bStandard, oParentContainer){
    var _iScale = 1;
    var _oListenerMouseDown;
    var _oListenerMouseUp;
    var _oListenerMouseOver;
    
    var _bDisable;
    var _bBlock = false;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oButton;
    var _oTextHighlight;
    var _oText;
    var _oTextBack;
    var _oButtonBg;
    
    this._init =function(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize, bStandard, oParentContainer){
        _bDisable = false;
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();

        _oButtonBg = createBitmap( oSprite);           

        var iStepShadow = Math.ceil(iFontSize/20);

        _oTextBack = new createjs.Text(szText," "+iFontSize+"px "+szFont, "#000000");
        _oTextBack.textAlign = "center";
        _oTextBack.textBaseline = "middle";   
        _oTextBack.x = oSprite.width/2 + iStepShadow;

        _oText = new createjs.Text(szText," "+iFontSize+"px "+szFont, szColor);
        _oText.textAlign = "center";
        _oText.textBaseline = "middle";   
        _oText.x = oSprite.width/2;
        
        _oTextHighlight = new createjs.Text(szText," "+iFontSize+"px "+szFont, "#FFBF00");
        _oTextHighlight.textAlign = "center";
        _oTextHighlight.textBaseline = "middle";   
        _oTextHighlight.x = oSprite.width/2;
        _oTextHighlight.visible = false;

        _oButton = new createjs.Container();
        _oButton.x = iXPos;
        _oButton.y = iYPos;
        _oButton.regX = oSprite.width/2;
        _oButton.regY = oSprite.height/2;   

        if(!bStandard){
            var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/2, height: oSprite.height, regX:(oSprite.width/2)/2, regY:oSprite.height/2}, 
                        animations: {state_true:[0],state_false:[1]}
                   };
                   
            var oSpriteSheet = new createjs.SpriteSheet(oData);         
            _oButtonBg = createSprite(oSpriteSheet, "state_true",(oSprite.width/2)/2,oSprite.height/2,oSprite.width/2,oSprite.height);
            
            _oTextBack.x = iStepShadow;
            _oTextBack.y = iStepShadow;
            _oTextHighlight.x = 0;
            _oText.x = 0;
            _oButton.regX = 0;
            _oButton.regY = 0;
            
        }
        _oButton.addChild(_oButtonBg,_oTextBack,_oText,_oTextHighlight);

        oParentContainer.addChild(_oButton);

        this._initListener();
    };
    
    this.unload = function(){
       if(s_bMobile){
            _oButton.off("mousedown", _oListenerMouseDown);
            _oButton.off("pressup" , _oListenerMouseUp);
        } else {
            _oButton.off("mousedown", _oListenerMouseDown);
            _oButton.off("mouseover", _oListenerMouseOver);
            _oButton.off("pressup" , _oListenerMouseUp);
        }
        
        oParentContainer.removeChild(_oButton);
    };
    
    this.setVisible = function(bVisible){
        _oButton.visible = bVisible;
    };
    
    this._initListener = function(){
       if(s_bMobile){
            _oListenerMouseDown   = _oButton.on("mousedown", this.buttonDown);
            _oListenerMouseUp     = _oButton.on("pressup" , this.buttonRelease);
        } else {
            _oListenerMouseDown   = _oButton.on("mousedown", this.buttonDown);
            _oListenerMouseOver   = _oButton.on("mouseover", this.buttonOver);
            _oListenerMouseUp     = _oButton.on("pressup" , this.buttonRelease);
        }
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.buttonRelease = function(){
        if(_bDisable){
            return;
        }
        if(_bBlock){
            return;
        }
        
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
        if(_bBlock){
            return;
        }
        playSound("click",1,false);
        
        _oButton.scaleX = 0.9*_iScale;
        _oButton.scaleY = 0.9*_iScale;

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN]);
       }
    };
    
    this.buttonOver = function(evt){
        if(!s_bMobile){
            if(_bBlock){
                return;
            }
            evt.target.cursor = "pointer";
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
        _oTextHighlight.x = iX;
        _oTextHighlight.y = iY;
        
    };
    
    this.setText = function(szText){
        _oText.text = szText;
        _oTextBack.text = szText;
        _oTextHighlight.text = szText;
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

    this.block = function(bVal){
        _bBlock = bVal;
    };

    this.setScale = function(iVal){
        _iScale = iVal;
        _oButton.scaleX = iVal;
        _oButton.scaleY = iVal;
    };
    
    this.setScaleX = function(iVal){
        _oButtonBg.scaleX = iVal;
    };
    
    this.stopHighlight = function(){
        _oTextHighlight.visible = false;
        createjs.Tween.removeTweens(_oTextHighlight);
    };
    
    this.highlight = function(){
        _oTextHighlight.visible = true;
        _oTextHighlight.alpha = 0;
        
        this._flicker();
    };
    
    this._flicker = function(){
        createjs.Tween.get(_oTextHighlight, {loop:true}).to({alpha:1}, 250,createjs.Ease.cubicOut).to({alpha:0}, 250,createjs.Ease.cubicOut);//.call(function(){_oParent._flicker()});
    };
    
    this._init(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize, bStandard, oParentContainer);
    
    return this;
    
}
