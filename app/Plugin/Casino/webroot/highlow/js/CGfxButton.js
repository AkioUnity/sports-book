function CGfxButton(iXPos,iYPos,oSprite,bStandard){
    var _bDisable;
    var _iWidth;
    var _iHeight;
    var _aCbCompleted;
    var _aCbOwner;
    var _aParams = [];
    var _oButton;
    
    this._init =function(iXPos,iYPos,oSprite,bStandard){
        _bDisable = false;
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
        
        _iWidth = oSprite.width;
        _iHeight = oSprite.height;
        
        if(bStandard){
            _oButton = createBitmap( oSprite);
            
            _oButton.regX = oSprite.width/2;
            _oButton.regY = oSprite.height/2;
            
        } else {
            
            var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: oSprite.width/2, height: oSprite.height, regX: (oSprite.width/2)/2, regY: oSprite.height/2}, 
                        animations: {state_true:[0],state_false:[1]}
                   };
                   
            var oSpriteSheet = new createjs.SpriteSheet(oData);         
            _oButton = createSprite(oSpriteSheet, "state_false",(oSprite.width/2)/2,oSprite.height/2,oSprite.width/2,oSprite.height);
        }
        
        _oButton.x = iXPos;
        _oButton.y = iYPos; 
        _oButton.cursor = "pointer";
        s_oStage.addChild(_oButton);
        
        
        this._initListener();
    };
    
    this.unload = function(){
       _oButton.off("mousedown", this.buttonDown);
       _oButton.off("pressup" , this.buttonRelease); 
       
       s_oStage.removeChild(_oButton);
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
    
    this.addEventListenerWithParams = function(iEvent,cbCompleted, cbOwner,aParams){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner;
        _aParams = aParams;
    };
    
    this.buttonRelease = function(){
        if(_bDisable){
            return;
        }
        
        playSound("click",1,false);
        
        _oButton.scaleX = 1;
        _oButton.scaleY = 1;

        if(_aCbCompleted[ON_MOUSE_UP]){
            _aCbCompleted[ON_MOUSE_UP].call(_aCbOwner[ON_MOUSE_UP],_aParams);
        }
    };
    
    this.buttonDown = function(){
        if(_bDisable){
            return;
        }
        _oButton.scaleX = 0.9;
        _oButton.scaleY = 0.9;

       if(_aCbCompleted[ON_MOUSE_DOWN]){
           _aCbCompleted[ON_MOUSE_DOWN].call(_aCbOwner[ON_MOUSE_DOWN],_aParams);
       }
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

    this.enable = function(){
        _bDisable = false;
        
        if(!bStandard){
            _oButton.gotoAndStop("state_true");
        }
        

    };
    
    this.disable = function(){
        _bDisable = true;
        if(!bStandard){
            _oButton.gotoAndStop("state_false");
        }

    };
    
    this.getButtonImage = function(){
        return _oButton;
    };
    
    this.getDisable = function(){
        return _bDisable;
    };
    
    this.getX = function(){
        return _oButton.x;
    };
    
    this.getY = function(){
        return _oButton.y;
    };

    this._init(iXPos,iYPos,oSprite,bStandard);
    
    return this;
}