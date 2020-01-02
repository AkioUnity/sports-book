function CHelpCursor(iX,iY,oSprite,oParentContainer){
    var _iStartX;
    var _oSprite;
    var _oParentContainer;
    var _oThis;
    
    this._init = function(iX,iY,oSprite){
        _iStartX = iX;
        
        _oSprite = createBitmap(oSprite);
        _oSprite.visible = false;
        _oSprite.x = iX;
        _oSprite.y = iY;
        oParentContainer.addChild(_oSprite);
    };
    
    this.show = function(iDir){
        if(iDir < 0){
            _oSprite.scaleX *= -1;
        }
        this._move(iDir,_iStartX + (30*iDir),600);
        _oSprite.visible = true;
    };
    
    this.hide = function(){
        createjs.Tween.removeTweens(_oSprite);
        _oSprite.x = _iStartX;
        _oSprite.visible = false;
    };
    
    this._move = function(iDir,iNextX,iTime){
        var oEasing;
        if(iDir > 0){
            oEasing = createjs.Ease.cubicIn;
        }else{
            oEasing = createjs.Ease.cubicOut;
        }
        createjs.Tween.get(_oSprite).to({x:iNextX}, iTime,oEasing).call(function(){iDir *= -1; _oThis._move(iDir,iNextX + (15*iDir),400);});  
    };
    
    this.isVisible = function(){
        return _oSprite.visible;
    };
    
    _oParentContainer = oParentContainer;
    _oThis = this;
    
    this._init(iX,iY,oSprite);
}