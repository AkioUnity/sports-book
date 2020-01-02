function CAnimText(iX,iY,oParentContainer){
    var _pStartPos;
    var _oText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oSpriteBg = s_oSpriteLibrary.getSprite("win_bg");
        var oBg = createBitmap(oSpriteBg);
        _oContainer.addChild(oBg);
        
        _oText = new createjs.Text("","28px "+FONT_GAME_1, "#fff");
        _oText.x = oSpriteBg.width/2;
        _oText.y = 0;
        _oText.alphabetic = "middle";
        _oText.textAlign = "center";
        _oText.lineWidth = oSpriteBg.width;
        _oContainer.addChild(_oText);
    };
    
    this.show = function(pStartPos,pEndPos,szText){
        _pStartPos = pStartPos;
        
        _oText.text = szText;
        _oContainer.x = pStartPos.x;
        _oContainer.y = pStartPos.y;
        _oContainer.visible = true;
        createjs.Tween.get(_oContainer).to({x:pEndPos.x,y:pEndPos.y}, 1000,createjs.Ease.elasticOut);
    };
    
    this.hide = function(){
        _oContainer.visible = false;
        _oContainer.x = _pStartPos.x;
        _oContainer.y = _pStartPos.y;
    };
    
    this.isVisible = function(){
        return _oContainer.visible;
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}