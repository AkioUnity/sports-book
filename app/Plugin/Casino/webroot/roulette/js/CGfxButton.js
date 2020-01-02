function CGfxButton(iXPos,iYPos,oSprite,bAttach){
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oParams;
    var _oButton;
    
    this._init =function(iXPos,iYPos,oSprite,bAttach){
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
        
        _oButton =createBitmap( oSprite);
        _oButton.x = iXPos;
        _oButton.y = iYPos; 
                                   
        _oButton.regX = oSprite.width/2;
        _oButton.regY = oSprite.height/2;
       if (!s_bMobile){
            _oButton.cursor = "pointer";
	}
       if(bAttach !== false){
            s_oStage.addChild(_oButton);
        }
        
        this._initListener();
    };
    
    this.unload = function(){
       _oButton.off("mousedown", this.buttonDown);
       _oButton.off("pressup" , this.buttonRelease); 
       
       if(s_bMobile === false){
           _oButton.off("rollover",this.mouseOver);
           _oButton.off("rollout",this.mouseOut);
       }
       
       s_oStage.removeChild(_oButton);
    };
    
    this.setVisible = function(bVisible){
        _oButton.visible = bVisible;
    };
    
    this._initListener = function(){
       _oButton.on("mousedown", this.buttonDown);
       _oButton.on("pressup" , this.buttonRelease);   
       
       if(s_bMobile === false){
           _oButton.on("rollover",this.mouseOver);
           _oButton.on("rollout",this.mouseOut);
       }
       
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.addEventListenerWithParams = function(iEvent,cbCompleted, cbOwner,oParams){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner;
        _oParams = oParams;
    };
    
    this.buttonRelease = function(){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.play("click");
        }
        
        _oButton.scaleX = 1;
        _oButton.scaleY = 1;

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP],_oParams);
        }
    };
    
    this.buttonDown = function(){
        _oButton.scaleX = 0.9;
        _oButton.scaleY = 0.9;

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN],_oParams);
       }
    };
    
    this.mouseOver = function(){
        if(_aCbCompleted[ON_MOUSE_OVER]){
             _aCbCompleted[ON_MOUSE_OVER].call(_aCbOwner[ON_MOUSE_OVER],_oParams);
       }
    };
    
    this.mouseOut = function(){
        if(_aCbCompleted[ON_MOUSE_OUT]){
            _aCbCompleted[ON_MOUSE_OUT].call(_aCbOwner[ON_MOUSE_OUT],_oParams);
       }
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oButton.x = iXPos;
         _oButton.y = iYPos;
    };
    
    this.rotate = function(iAngle){
        _oButton.rotation = iAngle;
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

    this._init(iXPos,iYPos,oSprite,bAttach);
    
    return this;
}