function CDisplayPanel(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize){
    
    var _bBlock;
    
    var _iScale;
    
    var _aCbCompleted;
    var _aCbOwner;
    var _oPanel;
    var _oText;
    var _oTextBack;
    
    this._init =function(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize){
        
        _bBlock = false;
        
        _iScale = 1;
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();

        var oButtonBg = createBitmap( oSprite);

        var iStepShadow = Math.ceil(iFontSize/20);

        _oTextBack = new createjs.Text(szText,iFontSize+"px "+szFont, "#000000");
        _oTextBack.textAlign = "center";
        _oTextBack.textBaseline = "alphabetic";
        var oBounds = _oTextBack.getBounds();    
        _oTextBack.x = oSprite.width/2 + iStepShadow;
        _oTextBack.y = Math.floor((oSprite.height)/2) +(oBounds.height/3) + iStepShadow;

        _oText = new createjs.Text(szText,iFontSize+"px "+szFont, szColor);
        _oText.textAlign = "center";
        _oText.textBaseline = "alphabetic";
        var oBounds = _oText.getBounds();    
        _oText.x = oSprite.width/2;
        _oText.y = Math.floor((oSprite.height)/2) +(oBounds.height/3);

        _oPanel = new createjs.Container();
        _oPanel.x = iXPos;
        _oPanel.y = iYPos;
        _oPanel.regX = oSprite.width/2;
        _oPanel.regY = oSprite.height/2;
        _oPanel.addChild(oButtonBg,_oTextBack,_oText);

        s_oStage.addChild(_oPanel);
    };
    
    this.unload = function(){       
       s_oStage.removeChild(_oPanel);
    };
    
    this.setVisible = function(bVisible){
        _oPanel.visible = bVisible;
    };    
    
    this.setTextPosition = function(iY){
        _oText.y= iY;
        _oTextBack.y = iY+2;
    };
    
    this.setText = function(szText){
        _oText.text = szText;
        _oTextBack.text = szText;
    };
    
    this.setPosition = function(iXPos,iYPos){
         _oPanel.x = iXPos;
         _oPanel.y = iYPos;
    };
    
    this.setX = function(iXPos){
         _oPanel.x = iXPos;
    };
    
    this.setY = function(iYPos){
         _oPanel.y = iYPos;
    };
    
    this.getButtonImage = function(){
        return _oPanel;
    };

    this.getX = function(){
        return _oPanel.x;
    };
    
    this.getY = function(){
        return _oPanel.y;
    };

    this.setScale = function(iVal){
        _iScale = iVal;
        _oPanel.scaleX = iVal;
        _oPanel.scaleY = iVal;
    };

    this._init(iXPos,iYPos,oSprite,szText,szFont,szColor,iFontSize);
    
    return this;
    
}
