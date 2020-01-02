function CWheelAnim(iX,iY){
    var _bUpdate;
    var _bBallSpin;
    var _iWin;
    var _iCurBallSprite;
    var _iCurBallSpin;
    var _iCurWheelIndex;
    var _iFrameCont;
    var _iCurBallIndex;
    var _aWheelAnimSprites;
    var _aWheelMaskSprites;
    var _aBallPos;
    var _oBall;
    var _oCurWheelSprite;
    var _oCurWheelMaskSprite;
    var _oNumExtractedText;
    var _oResultText;
    var _oShowNumber;
    var _oNumberColorBg;
    var _oListenerClick;
    var _oContainer;
    
    this._init= function(iX,iY){
        _iCurWheelIndex= 0;
        _iFrameCont = 0;
        _bBallSpin = false;

        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oContainer.x = iX;
        _oContainer.y = iY;
	_oListenerClick = _oContainer.on("click",function(){});
        s_oStage.addChild(_oContainer);
        
	var oFade = new createjs.Shape();
        oFade.graphics.beginFill("rgba(0,0,0,0.7)").drawRect(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
        _oContainer.addChild(oFade);
		
        var oBgWheel = createBitmap(s_oSpriteLibrary.getSprite("bg_wheel"));
        oBgWheel.x = 240;
        oBgWheel.y = 159;
        _oContainer.addChild(oBgWheel);
        
        _aWheelAnimSprites = new Array();
        for(var i=0;i<NUM_MASK_BALL_SPIN_FRAMES;i++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('wheel_numbers_'+i)); 
            oImage.x = 418;
            oImage.y = 219;
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelAnimSprites.push(oImage);
        }
        
        this._initBall();

        
        _aWheelMaskSprites = new Array();
        for(var j=0;j<NUM_MASK_BALL_SPIN_FRAMES;j++){
            var oImage = createBitmap(s_oSpriteLibrary.getSprite('wheel_handle_'+j));
            oImage.x = 519;
            oImage.y = 186;
            oImage.visible = false;
            _oContainer.addChild(oImage);
            _aWheelMaskSprites.push(oImage);
        }
        
        _oCurWheelSprite = _aWheelAnimSprites[0];
        _oCurWheelSprite.visible = true;
        
        _oCurWheelMaskSprite = _aWheelMaskSprites[0];
        _oCurWheelMaskSprite.visible = true;
        
        _oShowNumber = new createjs.Container();
        _oShowNumber.visible = false;
        _oShowNumber.x = CANVAS_WIDTH/2;
        _oShowNumber.y = CANVAS_HEIGHT/2;
        _oContainer.addChild(_oShowNumber);
        
        var oSprite = s_oSpriteLibrary.getSprite("show_number_panel");
        var oBgShowNumber = createBitmap(oSprite);
        _oShowNumber.addChild(oBgShowNumber);
        
        var oData = {   
                        images: [s_oSpriteLibrary.getSprite("show_number_bg")], 
                        // width, height & registration point of each sprite
                        frames: {width: 117, height: 117, regX: 58, regY: 58}, 
                        animations: {black:[0],red:[1],green:[2]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        _oNumberColorBg = createSprite(oSpriteSheet, "black",58,58,117,117);
        _oNumberColorBg.x = oSprite.width/2;
        _oNumberColorBg.y = oSprite.height/2;
        _oShowNumber.addChild(_oNumberColorBg);
        
        _oNumExtractedText = new createjs.Text("36","80px "+FONT2, "#fff");
        _oNumExtractedText.textAlign = "center";
        _oNumExtractedText.textBaseline = "middle";
        _oNumExtractedText.x = oSprite.width/2;
        _oNumExtractedText.y = oSprite.height/2 + 7;
        _oShowNumber.addChild(_oNumExtractedText);
        
        var oSpriteResultBg = s_oSpriteLibrary.getSprite("but_bg");
        var oBgResult = createBitmap(oSpriteResultBg);
        oBgResult.regX = oSpriteResultBg.width/2;
        oBgResult.x = oSprite.width/2;
        oBgResult.y = oSprite.height - 12;
        _oShowNumber.addChild(oBgResult);
        
        _oResultText = new createjs.Text("","22px "+FONT1, "#fff");
        _oResultText.textAlign = "center";
        _oResultText.textBaseline = "middle";
        _oResultText.x = oSprite.width/2;
        _oResultText.y = oSprite.height + 20;
        _oShowNumber.addChild(_oResultText);
        
        _oShowNumber.regX = oSprite.width/2;
        _oShowNumber.regY = oSprite.height/2;
    };
    
    this.unload = function(){
        _oContainer.off("click",_oListenerClick);
    };
    
    this._initBall = function(){
        _aBallPos = new Array();
        
        _aBallPos.push({x:892.9,y:358.95});
        _aBallPos.push({x:889.4,y:338.95});
        _aBallPos.push({x:880.9,y:320.45});
        _aBallPos.push({x:870.9,y:303.45});
        _aBallPos.push({x:857.65,y:287.2});
        _aBallPos.push({x:842.4,y:272.2});
        _aBallPos.push({x:825.9,y:257.45});
        _aBallPos.push({x:808.15,y:245.7});
        _aBallPos.push({x:788.15,y:234.45});
        _aBallPos.push({x:767.9,y:224.45});
        _aBallPos.push({x:746.9,y:217.2});
        _aBallPos.push({x:724.4,y:210.7});
        _aBallPos.push({x:702.15,y:205.2});
        _aBallPos.push({x:680.15,y:201.7});
        _aBallPos.push({x:657.15,y:199.45});
        _aBallPos.push({x:634.15,y:198.95});
        _aBallPos.push({x:609.15,y:199.95});
        _aBallPos.push({x:586.4,y:202.2});
        _aBallPos.push({x:564.15,y:206.2});
        _aBallPos.push({x:541.65,y:211.2});
        _aBallPos.push({x:519.15,y:218.2});
        _aBallPos.push({x:498.9,y:227.45});
        _aBallPos.push({x:478.9,y:236.7});
        _aBallPos.push({x:461.15,y:248.95});
        _aBallPos.push({x:444.15,y:261.45});
        _aBallPos.push({x:429.15,y:275.7});
        _aBallPos.push({x:416.65,y:291.45});
        _aBallPos.push({x:406.65,y:308.95});
        _aBallPos.push({x:399.15,y:326.7});
        _aBallPos.push({x:394.4,y:345.7});
        _aBallPos.push({x:394.4,y:365.7});
        _aBallPos.push({x:396.65,y:385.7});
        _aBallPos.push({x:402.4,y:405.2});
        _aBallPos.push({x:411.65,y:424.95});
        _aBallPos.push({x:425.9,y:444.2});
        _aBallPos.push({x:444.15,y:462.2});
        _aBallPos.push({x:465.9,y:477.95});
        _aBallPos.push({x:491.15,y:492.45});
        _aBallPos.push({x:519.15,y:504.7});
        _aBallPos.push({x:549.9,y:512.95});
        _aBallPos.push({x:582.4,y:518.7});
        _aBallPos.push({x:615.4,y:520.45});
        _aBallPos.push({x:648.4,y:518.45});
        _aBallPos.push({x:681.4,y:513.45});
        _aBallPos.push({x:711.9,y:505.2});
        _aBallPos.push({x:739.65,y:493.45});
        _aBallPos.push({x:764.65,y:478.7});
        _aBallPos.push({x:786.15,y:461.95});
        _aBallPos.push({x:802.9,y:444.45});
        _aBallPos.push({x:816.15,y:424.7});
        _aBallPos.push({x:825.15,y:404.7});
        _aBallPos.push({x:829.9,y:384.7});
        _aBallPos.push({x:829.9,y:364.7});
        _aBallPos.push({x:825.9,y:345.95});
        _aBallPos.push({x:818.9,y:327.2});
        _aBallPos.push({x:808.15,y:310.2});
        _aBallPos.push({x:795.15,y:293.95});
        _aBallPos.push({x:779.65,y:279.45});
        _aBallPos.push({x:761.65,y:267.2});
        _aBallPos.push({x:742.4,y:256.45});
        _aBallPos.push({x:721.15,y:247.95});
        _aBallPos.push({x:698.65,y:240.45});
        _aBallPos.push({x:673.65,y:236.95});
        _aBallPos.push({x:650.65,y:234.45});
        _aBallPos.push({x:625.65,y:233.95});
        _aBallPos.push({x:603.15,y:235.45});
        _aBallPos.push({x:579.9,y:238.7});
        _aBallPos.push({x:556.9,y:246.2});
        _aBallPos.push({x:534.4,y:254.2});
        _aBallPos.push({x:514.4,y:265.7});
        _aBallPos.push({x:497.65,y:278.2});
        _aBallPos.push({x:482.15,y:292.45});
        _aBallPos.push({x:468.9,y:307.7});
        _aBallPos.push({x:460.65,y:326.2});
        _aBallPos.push({x:455.65,y:344.7});
        _aBallPos.push({x:454.4,y:364.7});
        _aBallPos.push({x:458.15,y:384.7});
        _aBallPos.push({x:466.9,y:403.7});
        _aBallPos.push({x:480.15,y:421.95});
        _aBallPos.push({x:498.15,y:438.2});
        _aBallPos.push({x:520.65,y:453.2});
        _aBallPos.push({x:546.65,y:463.7});
        _aBallPos.push({x:575.4,y:471.45});
        _aBallPos.push({x:605.4,y:475.2});
        _aBallPos.push({x:635.4,y:474.95});
        _aBallPos.push({x:664.4,y:469.95});
        _aBallPos.push({x:690.9,y:460.7});
        _aBallPos.push({x:714.15,y:447.95});
        _aBallPos.push({x:732.65,y:431.2});
        _aBallPos.push({x:743.4,y:418.7});
        _aBallPos.push({x:749.4,y:411.2});
        _aBallPos.push({x:752.15,y:397.95});
        _aBallPos.push({x:757.65,y:379.45});
        _aBallPos.push({x:757.65,y:379.45});
        _aBallPos.push({x:755.65,y:375.7});
        _aBallPos.push({x:756.15,y:366.2});
        _aBallPos.push({x:756.15,y:356.2});
        _aBallPos.push({x:753.65,y:344.95});
        _aBallPos.push({x:751.4,y:346.45});
        _aBallPos.push({x:749.9,y:346.45});
        _aBallPos.push({x:751.65,y:351.7});
        _aBallPos.push({x:754.15,y:356.7});
        _aBallPos.push({x:754.9,y:362.45});
        _aBallPos.push({x:755.9,y:367.45});
        _aBallPos.push({x:756.4,y:374.2});
        _aBallPos.push({x:756.4,y:380.2});
        _aBallPos.push({x:755.65,y:386.7});
        _aBallPos.push({x:754.4,y:392.45});
        _aBallPos.push({x:752.65,y:399.2});
        _aBallPos.push({x:750.15,y:405.45});
        _aBallPos.push({x:747.65,y:411.7});
        _aBallPos.push({x:744.4,y:416.95});
        _aBallPos.push({x:740.65,y:424.45});
        _aBallPos.push({x:736.15,y:429.7});
        _aBallPos.push({x:731.15,y:434.95});
        _aBallPos.push({x:725.65,y:440.95});
        _aBallPos.push({x:720.15,y:446.2});
        _aBallPos.push({x:713.65,y:451.2});
        _aBallPos.push({x:705.9,y:455.45});
        _aBallPos.push({x:698.65,y:460.2});
        _aBallPos.push({x:691.15,y:462.95});
        _aBallPos.push({x:682.15,y:466.7});
        _aBallPos.push({x:673.65,y:469.2});
        _aBallPos.push({x:664.65,y:471.45});
        _aBallPos.push({x:655.15,y:473.45});
        _aBallPos.push({x:646.15,y:475.2});
        _aBallPos.push({x:634.9,y:476.45});
        _aBallPos.push({x:624.9,y:476.45});
        _aBallPos.push({x:614.9,y:476.45});
        _aBallPos.push({x:604.9,y:475.7});
        _aBallPos.push({x:595.65,y:474.2});
        _aBallPos.push({x:586.4,y:472.45});
        _aBallPos.push({x:577.15,y:470.45});
        _aBallPos.push({x:568.65,y:466.95});
        _aBallPos.push({x:561.15,y:464.95});
        _aBallPos.push({x:553.15,y:460.95});
        _aBallPos.push({x:545.15,y:457.95});
        _aBallPos.push({x:539.9,y:452.95});
        _aBallPos.push({x:531.4,y:447.95});
        _aBallPos.push({x:525.9,y:443.45});
        _aBallPos.push({x:518.4,y:439.45});
        _aBallPos.push({x:513.4,y:433.7});
        _aBallPos.push({x:509.15,y:426.95});
        _aBallPos.push({x:504.15,y:420.45});
        _aBallPos.push({x:500.65,y:415.2});
        _aBallPos.push({x:497.4,y:409.7});
        _aBallPos.push({x:495.15,y:403.45});
        _aBallPos.push({x:494.65,y:398.45});
        _aBallPos.push({x:493.4,y:391.2});
        _aBallPos.push({x:492.4,y:385.7});
        _aBallPos.push({x:491.9,y:378.7});
        _aBallPos.push({x:492.4,y:373.7});
        _aBallPos.push({x:492.9,y:367.2});
        _aBallPos.push({x:493.4,y:361.95});
        _aBallPos.push({x:495.15,y:356.2});
        _aBallPos.push({x:497.65,y:350.95});
        _aBallPos.push({x:500.15,y:344.2});
        _aBallPos.push({x:502.65,y:339.2});
        _aBallPos.push({x:505.9,y:334.7});
        _aBallPos.push({x:510.65,y:328.95});
        _aBallPos.push({x:513.9,y:323.95});
        _aBallPos.push({x:518.9,y:318.95});
        _aBallPos.push({x:523.9,y:314.2});
        _aBallPos.push({x:528.9,y:311.2});
        _aBallPos.push({x:533.9,y:306.7});
        _aBallPos.push({x:539.65,y:301.7});
        _aBallPos.push({x:544.65,y:299.2});
        _aBallPos.push({x:550.65,y:295.95});
        _aBallPos.push({x:558.4,y:294.45});
        _aBallPos.push({x:564.9,y:289.95});
        _aBallPos.push({x:572.4,y:289.45});
        _aBallPos.push({x:579.9,y:286.95});
        _aBallPos.push({x:585.15,y:285.95});
        _aBallPos.push({x:592.65,y:283.45});
        _aBallPos.push({x:600.15,y:283.45});
        _aBallPos.push({x:607.9,y:283.45});
        _aBallPos.push({x:613.9,y:281.2});
        _aBallPos.push({x:621.9,y:280.7});
        _aBallPos.push({x:629.4,y:280.7});
        _aBallPos.push({x:636.9,y:280.7});
        _aBallPos.push({x:644.4,y:280.95});
        _aBallPos.push({x:651.9,y:281.95});
        _aBallPos.push({x:658.9,y:284.2});
        _aBallPos.push({x:665.65,y:287.45});
        _aBallPos.push({x:672.65,y:289.95});
        _aBallPos.push({x:679.65,y:291.2});
        _aBallPos.push({x:686.4,y:293.7});
        _aBallPos.push({x:692.4,y:296.2});
        _aBallPos.push({x:699.15,y:298.7});
        _aBallPos.push({x:704.15,y:301.95});
        _aBallPos.push({x:710.65,y:306.95});
        _aBallPos.push({x:715.65,y:309.45});
        _aBallPos.push({x:721.15,y:312.95});
        _aBallPos.push({x:726.15,y:316.95});
        _aBallPos.push({x:731.15,y:321.95});
        _aBallPos.push({x:736.15,y:324.95});
        _aBallPos.push({x:739.9,y:330.95});
        _aBallPos.push({x:742.4,y:335.7});
        _aBallPos.push({x:746.15,y:340.95});
        _aBallPos.push({x:748.65,y:346.45});
        
        
        _oBall = createBitmap(s_oSpriteLibrary.getSprite("ball"));
        _oBall.x = _aBallPos[0].x;
        _oBall.y = _aBallPos[0].y;
        _oContainer.addChild(_oBall);
        
        _iCurBallIndex = 0;
    };
    
    this.hide = function(){
        _oShowNumber.visible = false;
        _oContainer.visible = false;
        _iCurBallIndex = 0;
    };
    
    this.startSpin = function(iRandSpin,iStartFrame,iNumExtracted,iWin){
        this.playToFrame(iStartFrame);
        
        _iWin = iWin;
        _iCurBallSpin = iRandSpin;
        _iCurBallSprite = 2;
        _bBallSpin = true;
        _oContainer.visible = true;
        
        this.setShowNumberInfo(iNumExtracted);
        _bUpdate = true;
    };
    
    this.setShowNumberInfo = function(iNumExtracted){
        _oNumExtractedText.text = iNumExtracted;
        if(_iWin > 0){
            _oResultText.font = "18px "+FONT1;
            _oResultText.text = TEXT_YOU_WIN + " "+_iWin+TEXT_CURRENCY;
        }else{
             _oResultText.font = "22px "+FONT1;
            _oResultText.text = TEXT_YOU_LOSE;
        }
        
        
        switch(s_oGameSettings.getColorNumber(iNumExtracted)){
                case COLOR_BLACK:{
                    _oNumberColorBg.gotoAndStop("black");
                    break;
                }
                case COLOR_RED:{
                    _oNumberColorBg.gotoAndStop("red");
                    break;
                }
                case COLOR_ZERO:{
                    _oNumberColorBg.gotoAndStop("green");
                    break;
                }
        }  
    };
    
    this._showNumberExtracted = function(){
        _oShowNumber.scaleX = _oShowNumber.scaleY = 0.1;
        _oShowNumber.visible = true;
        createjs.Tween.get(_oShowNumber).to({scaleX:1,scaleY:1}, 800,createjs.Ease.cubicOut);  
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            if(_iWin>0){
                playSound("win",1,false);
            }else{
                playSound("lose",1,false);
            }
            
        }
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
        _oBall.x = _aBallPos[_iCurBallIndex].x;
        _oBall.y = _aBallPos[_iCurBallIndex].y;
        
        _iCurBallIndex++;
        if(_iCurBallIndex === (NUM_BALL_SPIN_FRAMES)){
            _bUpdate = false;
            _iCurBallIndex = NUM_BALL_SPIN_FRAMES-1;
            s_oGame._rouletteAnimEnded();
            this.hide();
        }else if(_iCurBallIndex === NUM_BALL_SPIN_FRAMES/2){
            this._showNumberExtracted();
        }
    };
    
    this.isVisible = function(){
        return _oContainer.visible;
    };
    
    this.update = function(){
        if(_bUpdate === false){
            return;
        }
        
        _iFrameCont++;
        
        if(_iFrameCont === 2){
            _iFrameCont = 0;
            if(_bBallSpin){
            
                this._ballSpin();
                
                if (  _iCurWheelIndex === (NUM_MASK_BALL_SPIN_FRAMES-1)) {
                    this.playToFrame(1);
                }else{
                    this.nextFrame();
                }
            }
        }
        
    };
    
    this._init(iX,iY);
}