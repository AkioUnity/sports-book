function CDisplayText(iX,iY,oSpriteBg,szText,szTitle,iFontSize,oParentContainer){
    var _oText;
    var _oTitleText;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY,oSpriteBg,szText,szTitle,iFontSize){
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(oSpriteBg);
        _oContainer.addChild(oBg);
        
        _oText = new createjs.Text(szText,iFontSize+"px " +PRIMARY_FONT, "#fff");
        _oText.x = oSpriteBg.width/2;
        _oText.y = oSpriteBg.height/2 - 2;
        _oText.textAlign = "center";
        _oText.textBaseline = "middle";
        _oContainer.addChild(_oText);
        
        _oTitleText = new createjs.Text(szTitle," 36px " +PRIMARY_FONT, "#fff");
        _oTitleText.x = oSpriteBg.width/2;
        _oTitleText.y = -20;
        _oTitleText.textAlign = "center";
        _oTitleText.textBaseline = "middle";
        _oContainer.addChild(_oTitleText);
    };
    
    this.setPosition = function(iX,iY){
        _oContainer.x = iX;
        _oContainer.y = iY;
    };
    
    this.changeText = function(szText){
        _oText.text = szText;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY,oSpriteBg,szText,szTitle,iFontSize);
}