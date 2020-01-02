function CWheelAnim(iX,iY){
    var _bBallSpin;
    var _iCurBallSprite;
    var _iCurBallSpin;
    var _iCurWheelIndex;
    var _aWheelAnimSprites;
    var _aWheelMaskSprites;
    var _aBallSpin;
    var _oCurWheelSprite;
    var _oCurWheelMaskSprite;
    var _oCurBallSpin = null;
    var _oContainer;
    
    this._init= function(iX,iY){
        _iCurWheelIndex= 0;
        _bBallSpin = false;
        
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        s_oStage.addChild(_oContainer);
        
        _aWheelAnimSprites = new Array();
        for(var i=0;i<NUM_WHEEL_TOP_FRAMES;i++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('wheel_anim_'+i)); 
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelAnimSprites.push(oImage);
        }
        
        //ATTACH THE BALL SPINS
        _aBallSpin = new Array();
        for(var s=0;s<3;s++){
            _aBallSpin[s] = new Array();
            for(var t=0;t<NUM_BALL_SPIN_FRAMES;t++){
                var oImage = createBitmap(s_oSpriteLibrary.getSprite("ball_spin"+(s+1)+"_"+t));
                oImage.visible = false;
                _oContainer.addChild(oImage);
                _aBallSpin[s].push(oImage);
            }
        }
        
        
        _aWheelMaskSprites = new Array();
        for(var j=0;j<NUM_WHEEL_TOP_FRAMES;j++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('mask_ball_spin_'+j));
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelMaskSprites.push(oImage);
        }
        
        _oCurWheelSprite = _aWheelAnimSprites[0];
        _oCurWheelSprite.visible = true;
        
        _oCurWheelMaskSprite = _aWheelMaskSprites[0];
        _oCurWheelMaskSprite.visible = true;
    };
    
    this.startSpin = function(iRandSpin,iStartFrame){
        this.playToFrame(iStartFrame);

        _iCurBallSpin = iRandSpin;
        _iCurBallSprite = 1;
        _bBallSpin = true;
    };
    
    this.playToFrame = function(iFrame){
        _oCurWheelSprite.visible = false;
        
        _iCurWheelIndex = iFrame;
        _aWheelAnimSprites[_iCurWheelIndex].visible= true;
        _oCurWheelSprite = _aWheelAnimSprites[_iCurWheelIndex];
        
        _oCurWheelMaskSprite.visible = false;
        _aWheelMaskSprites[_iCurWheelIndex].visible= true;
        _oCurWheelMaskSprite = _aWheelMaskSprites[_iCurWheelIndex];
    };
    
    this.nextFrame = function(){
        _oCurWheelSprite.visible = false;
        _iCurWheelIndex++;
        _aWheelAnimSprites[_iCurWheelIndex].visible= true;
        _oCurWheelSprite = _aWheelAnimSprites[_iCurWheelIndex];
        
        _oCurWheelMaskSprite.visible = false;
        _aWheelMaskSprites[_iCurWheelIndex].visible= true;
        _oCurWheelMaskSprite = _aWheelMaskSprites[_iCurWheelIndex];
    };
    
    this._ballSpin = function(){
        if(_oCurBallSpin !== null){
            _oCurBallSpin.visible = false;
        }
        _aBallSpin[_iCurBallSpin][_iCurBallSprite].visible = true;
        _oCurBallSpin = _aBallSpin[_iCurBallSpin][_iCurBallSprite];
        
        _iCurBallSprite++;
        if(_iCurBallSprite === (NUM_BALL_SPIN_FRAMES -1)){
            _iCurBallSprite = 200;
        }
    };
    
    this.update = function(){
        if (  _iCurWheelIndex === (NUM_WHEEL_TOP_FRAMES-1)) {
            this.playToFrame(1);
        }else{
            this.nextFrame();
        }
        
        if(_bBallSpin){
            this._ballSpin();
        }
    };
    
    this._init(iX,iY);
}