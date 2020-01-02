function CWinText (szType,iX, iAmount){
    
    var _iTextWidth;
    
    var _oText;
    var _oTextOutline;
    var _oTextContainer;
    var _oParent;
    
    this._init = function(szType,iX,iAmount){    
        
        _oTextContainer = new createjs.Container();
        _oTextContainer.x=0;
        _oTextContainer.y=CANVAS_HEIGHT/2 - 135;        
        _oTextContainer.alpha = 1;        
        s_oStage.addChild(_oTextContainer);

        _oText = new createjs.Text("","60px "+PRIMARY_FONT, "#ffffff");
        _oText.textAlign="center";	  
        _oText.text = szType;
        _oText.textBaseline = "alphabetic";
        _oTextContainer.addChild(_oText);
        
        _oTextOutline = new createjs.Text("","60px "+PRIMARY_FONT, "#000000");
        _oTextOutline.textAlign="center";	  
        _oTextOutline.text = szType;
        _oTextOutline.outline = 3;
        _oTextOutline.textBaseline = "alphabetic";
        _oTextContainer.addChild(_oTextOutline);
        
        _iTextWidth = _oTextOutline.getMeasuredWidth(); 
    };
    
    this.show = function(){        
        createjs.Tween.get(_oTextContainer).to({x:iX}, SHOWTEXT_SPEED/4, createjs.Ease.elasticOut).wait(SHOWTEXT_SPEED/2).
                to({x:CANVAS_WIDTH + _iTextWidth/2}, SHOWTEXT_SPEED/4, createjs.Ease.quartOut).call(function(){
                    _oParent.unload();
                    s_oGame.tryShowAd();
                    s_oGame.refreshGame(iAmount);                    
                });
    };
  
    this.unload = function(){
        s_oStage.removeChild(_oTextContainer);
    };

    _oParent = this;    
    this._init(szType,iX,iAmount);
    
}