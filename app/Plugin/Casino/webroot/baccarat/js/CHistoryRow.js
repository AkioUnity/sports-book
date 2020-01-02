function CHistoryRow(iX,iY,iValue1,iValue2,iWinner,oSpriteCell,oParentContainer){
    var _iHeightCell;
    var _oContainer;
    var _oParentContainer;
    
    var _oThis;
    
    this._init = function(iX,iY,iValue1,iValue2,oSpriteCell){
        _iHeightCell = oSpriteCell.height;
        
        _oContainer = new createjs.Container();
        _oContainer.alpha = 0;
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oData = {   // image to use
                        images: [oSpriteCell], 
                        // width, height & registration point of each sprite
                        frames: {width: oSpriteCell.width/2, height: oSpriteCell.height}, 
                        animations: {  state_lose: [0],state_win:[1]}
                        
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        var aCell = new Array();
        aCell[0] = createSprite(oSpriteSheet,"state_lose",0,0,oSpriteCell.width/2, oSpriteCell.height);
        _oContainer.addChild(aCell[0]);
        
        aCell[1] = createSprite(oSpriteSheet,"state_lose",0,0,oSpriteCell.width/2, oSpriteCell.height);
        aCell[1].x = oSpriteCell.width/2;
        _oContainer.addChild(aCell[1]);
        
        if(iWinner > 0){
            aCell[iWinner-1].gotoAndStop("state_win");
        }
        
        
        var oText1 = new createjs.Text(iValue1,"24px "+FONT_GAME_1, "#fff");
        oText1.x = aCell[0].x + oSpriteCell.width/4;
        oText1.y = aCell[0].y + oSpriteCell.height/2;
        oText1.textAlign = "center";
        oText1.textBaseline = "middle";
        _oContainer.addChild(oText1);
        
        var oText2 = new createjs.Text(iValue2,"24px "+FONT_GAME_1, "#fff");
        oText2.x = aCell[1].x + oSpriteCell.width/4;
        oText2.y = aCell[1].y + oSpriteCell.height/2;
        oText2.textAlign = "center";
        oText2.textBaseline = "middle";
        _oContainer.addChild(oText2);
        
        createjs.Tween.get(_oContainer).to({alpha:1}, 400,createjs.Ease.cubicOut);
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oContainer);
    };
    
    this.moveDown = function(oParent){
        var iNewY = _oContainer.y + _iHeightCell;
        
        createjs.Tween.get(_oContainer).to({y:iNewY}, 400,createjs.Ease.cubicOut).call(function(){oParent._showNextRow(_oThis);});
    };
    
    this.getY = function(){
        return _oContainer.y;
    };
    
    _oThis = this;
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY,iValue1,iValue2,oSpriteCell);
};