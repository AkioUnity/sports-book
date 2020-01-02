function CPaytablePanel(iX,iY,oParentContainer){
    var _pStartPosPaytable;
    var _oTextBet;
    var _oTextMult;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        _pStartPosPaytable = {x:iX,y:iY};
        _oContainer = new createjs.Container();
        _oContainer.x = _pStartPosPaytable.x;
        _oContainer.y = _pStartPosPaytable.y;
        _oParentContainer.addChild(_oContainer);
        
        var oSpriteBg = s_oSpriteLibrary.getSprite("paytable_bg");
        var oBg = createBitmap(oSpriteBg);
        _oContainer.addChild(oBg);
        
        
        var szText1 = "";
        var szText2 = "";
        for(var i=0;i<PAYOUT_MULT.length;i++){
            szText1 += TEXT_EVALUATOR[i] + "\n";
            szText2 += PAYOUT_MULT[i] +":1"+ "\n";
        }
        
        
        _oTextBet = new createjs.Text(szText1,"20px "+FONT_GAME_1, "#ffde00");
        _oTextBet.x = 10;
        _oTextBet.y = 10;
        _oTextBet.textAlign = "left";
        _oTextBet.lineHeight = 20;
        _oContainer.addChild(_oTextBet);
        
        _oTextMult = new createjs.Text(szText2,"20px "+FONT_GAME_1, "#ffde00");
        _oTextMult.x = oSpriteBg.width - 10;
        _oTextMult.y = 10;
        _oTextMult.textAlign = "right";
        _oTextMult.lineHeight = 20;
        _oContainer.addChild(_oTextMult);
    };
    
    this.refreshButtonPos = function(iNewX,iNewY){
        _oContainer.x = _pStartPosPaytable.x - iNewX;
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}