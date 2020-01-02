function CShowResult(szWin, iType, iPos, iMult){
    var _iWin;
    
    var _oTextNumber;
    var _oTextBack;
    var _oText;
    var _oParent;
   
    var _aX;
    var _aY;
    
    var _iCurAlpha;
    
    this._init = function(szWin, iType, iPos, iMult){
        
        _iCurAlpha=0;
        
        ///////Icon position///////
        _aX = new Array();
        _aY = new Array();
        
        for (var i=0; i<3; i++){
            var xStart=352;
            _aX.push(xStart+i*260);
            
        }
        
        for (var i=0; i<3; i++){
            var yStart=150;
            _aY.push(yStart+i*35);
            
        }
        
        
        if(szWin==="win"){
            _iWin = PRIZE[iType]*iMult;
            _oTextNumber = new createjs.Text(_iWin.formatDecimal(2, ".", ",") +" "+TEXT_CURRENCY," 28px "+PRIMARY_FONT, "#FFBF00"); 
            _oTextNumber.alpha=0;
            s_oStage.addChild(_oTextNumber);
            this._highlight(iType);
            this._advice(szWin,iPos);
        } else{
            this._advice(szWin,iPos);
        }
      
    };
    
    this.unload = function(){
        s_oStage.removeChild(_oTextNumber);
        s_oStage.removeChild(_oTextBack);
        s_oStage.removeChild(_oText);
    };
    
    this._highlight = function (iType){
        var i;
        var j;
        
        switch(iType) {
                    case 0: {
                        i=0;
                        j=0;
                            }
                            break;       
                    
                    
                    case 1: {
                        i=0;
                        j=1;
                            }
                            break;
                            
                    case 2: {
                        i=0;
                        j=2;
                            }
                            break;
                    
                    case 3: {
                        i=1;
                        j=0;
                            }
                            break;
                            
                            
                    case 4: {
                        i=1;
                        j=1;
                            }
                            break;
                           
                    case 5: {
                        i=1;
                        j=2;
                            }
                            break;     
                    
                    case 6: {
                        i=2;
                        j=0;
                            }
                            break;
                            
                    case 7: {
                        i=2;
                        j=1;
                            }
                            break;
                            
                    case 8: {
                        i=2;
                        j=2;
                            }
                            break;
                            
        }
        
        
        _oTextNumber.x = _aX[i]+110;
        _oTextNumber.y = _aY[j]+10;

        this._flicker();
       
    };
    
    this._flicker = function(){
        if(_iCurAlpha === 1){
            _iCurAlpha = 0;
            createjs.Tween.get(_oTextNumber).to({alpha:_iCurAlpha }, 250,createjs.Ease.cubicOut).call(function(){_oParent._flicker()});
        }else{
            _iCurAlpha = 1;
            createjs.Tween.get(_oTextNumber).to({alpha:_iCurAlpha }, 250,createjs.Ease.cubicOut).call(function(){_oParent._flicker()});
        }
    };
    
    this._advice = function(szWin,iPos){
		s_oScratchSfx.stop();
		
        var iStartPos = {x:CANVAS_WIDTH + 400, y: 330 + iPos*110 };
        
        _oTextBack = new createjs.Text(""," 40px "+PRIMARY_FONT, "#000000");
        _oTextBack.x = iStartPos.x+2;
        _oTextBack.y = iStartPos.y+2;
        _oTextBack.textAlign="center";
		
        _oText = new createjs.Text(""," 40px "+PRIMARY_FONT, "#ffffff");          
        _oText.x = iStartPos.x;
        _oText.y = iStartPos.y;
        _oText.textAlign="center";
        s_oStage.addChild(_oTextBack);
        s_oStage.addChild(_oText);
        
        if(szWin==="win"){
            _oTextBack.text = TEXT_ADVICE_WIN + " " + _oTextNumber.text;
            _oText.text = TEXT_ADVICE_WIN + " " +_oTextNumber.text;
            
            playSound("win",1,false);
            
        } else {
            _oTextBack.text = TEXT_ADVICE_LOSE;
            _oText.text = TEXT_ADVICE_LOSE;
            
            playSound("loose",1,false);
            
        }
        
        
        createjs.Tween.get(_oTextBack).to({x:CANVAS_WIDTH/2+2, y: 332 + iPos*110 }, 2000,createjs.Ease.elasticOut);
        createjs.Tween.get(_oText).to({x:CANVAS_WIDTH/2, y: 330 + iPos*110 }, 2000,createjs.Ease.elasticOut).call(function(){s_oGame.checkEndScratch();}); 
    };
    
    this.getWin = function(){
        return _iWin;
    };
    
    _oParent=this;
    this._init(szWin, iType, iPos, iMult);
    
    return this;
}