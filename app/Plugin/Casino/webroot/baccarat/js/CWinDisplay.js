function CWinDisplay(iX,iY,oParentContainer){
    var _iStartingX;
    var _oDescText;
    var _oWinText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        _iStartingX = iX;
        
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oSpriteBg = s_oSpriteLibrary.getSprite('win_bg');
        var oBg = createBitmap(oSpriteBg);
        _oContainer.addChild(oBg);
        
        _oDescText =  new createjs.Text("","23px "+FONT_GAME_1, "#fff");
        _oDescText.x = oSpriteBg.width/2;
        _oDescText.y = oSpriteBg.height/2 - 20;
        _oDescText.textAlign = "center";
        _oDescText.textBaseline = "middle";
        _oContainer.addChild(_oDescText);
        
        _oWinText =  new createjs.Text("","29px "+FONT_GAME_1, "#fff");
        _oWinText.x = oSpriteBg.width/2;
        _oWinText.y = oSpriteBg.height/2 + 22;
        _oWinText.textAlign = "center";
        _oWinText.textBaseline = "middle";
        _oContainer.addChild(_oWinText);
    };
    
    this.show = function(szDesc,iWin){
        _oDescText.text = szDesc;
        
        if(iWin > 0){
            _oWinText.color = "#07a74f";
            _oWinText.text = TEXT_WIN + " " +iWin.toFixed(2);
        }else{
            _oWinText.color = "#ce0909";
            _oWinText.text = TEXT_NO_WIN;
        }
        
        _oContainer.visible = true;
        
        createjs.Tween.get(_oContainer).to({x:CANVAS_WIDTH/2 + 100}, 400,createjs.Ease.cubicOut);
    };
    
    this.hide = function(){
        createjs.Tween.get(_oContainer).to({x:_iStartingX}, 400,createjs.Ease.cubicOut).call(function(){_oContainer.visible = false;});
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY);
}