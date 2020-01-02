function CWheelTopAnim(iX,iY){
    var _iCurWheelIndex;
    var _aWheelSprites;
    var _oCurSprite;
    var _oBall;
    var _oContainer;
    
    this._init = function(iX,iY){
        _iCurWheelIndex = 0;
        
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        s_oStage.addChild(_oContainer);

        _aWheelSprites = new Array();
        for(var i=0;i<NUM_WHEEL_TOP_FRAMES;i++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('wheel_top_'+i));
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelSprites.push(oImage);
        }
        
        
        _oBall = createBitmap(s_oSpriteLibrary.getSprite('ball_spin'));
        _oBall.visible = false;
        _oBall.x = 68;
        _oBall.y = 80;
        _oContainer.addChild(_oBall);
        
        _oCurSprite = _aWheelSprites[0];
        _oCurSprite.visible = true;
    };
    
    this.hideBall = function(){
        _oBall.visible = false;
    };
    
    this.showBall = function(){
        _oBall.visible = true;
    };
    
    this.playToFrame = function(iFrame){
        _oCurSprite.visible = false;
        
        _iCurWheelIndex = iFrame;
        _aWheelSprites[_iCurWheelIndex].visible= true;
        _oCurSprite = _aWheelSprites[_iCurWheelIndex];
    };
    
    this.stopAnim = function(){
        
    };
    
    this.nextFrame = function(){
        _oCurSprite.visible = false;
        _iCurWheelIndex++;
        _aWheelSprites[_iCurWheelIndex].visible= true;
        _oCurSprite = _aWheelSprites[_iCurWheelIndex];
    };
    
    this. getCurrentFrame = function(){
        return _iCurWheelIndex;
    };
    
    this._init(iX,iY);
}