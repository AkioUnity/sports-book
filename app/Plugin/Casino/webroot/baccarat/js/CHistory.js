function CHistory(iX,iY,oParentContainer){
    var _iNextCellY;
    var _iNextPlayerValueCard;
    var _iNextDealerValueCard;
    var _iNextWinBet;
    var _iMaskHeight;
    var _aRows;
    
    var _oBg;
    var _oHighlight;
    var _oSpriteCell;
    var _oMask;
    var _oContainer;
    var _oContainerRows;
    var _oParentContainer;
    
    var _oThis;
    
    this._init = function(iX,iY){
        _oSpriteCell = s_oSpriteLibrary.getSprite('history_cell');
        _iNextCellY  = 0;
        
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('history_bg'));
        _oBg.alpha = 0;
        _oContainer.addChild(_oBg);
        
        _oContainerRows = new createjs.Container();
        _oContainerRows.x = 5;
        _oContainerRows.y = 5;
        _oContainer.addChild(_oContainerRows);
        
        _iMaskHeight = _oSpriteCell.height*7;
        _oMask = new createjs.Shape();
        _oMask.graphics.beginFill("rgba(255,0,0,0.01)").drawRect(_oContainerRows.x,_oContainerRows.y,_oSpriteCell.width,_iMaskHeight);
        _oContainer.addChild(_oMask);
        
        _oContainerRows.mask = _oMask;
        
        _oHighlight = createBitmap(s_oSpriteLibrary.getSprite('history_highlight'));
        _oHighlight.alpha = 0;
        _oHighlight.x = 5;
        _oHighlight.y = 5;
        _oContainer.addChild(_oHighlight);
        
        _aRows = new Array();
    };
    
    this.addHistoryRow = function(iPlayerValueCard,iDealerValueCard,iWinningBet){
        _iNextPlayerValueCard = iPlayerValueCard;
        _iNextDealerValueCard = iDealerValueCard;
        _iNextWinBet = iWinningBet;
        
        if(_aRows.length > 0){
            //MOVE DOWN ROWS HISTORY
            for(var i=0;i<_aRows.length;i++){
                _aRows[i].moveDown(this);
            }
        }else{
            createjs.Tween.get(_oBg).to({alpha:1}, 400,createjs.Ease.cubicOut);
            createjs.Tween.get(_oHighlight).to({alpha:1}, 400,createjs.Ease.cubicOut);
            
            var oRow = new CHistoryRow(0,0,_iNextPlayerValueCard,_iNextDealerValueCard,_iNextWinBet,_oSpriteCell,_oContainerRows);
            _aRows.push(oRow);
        }

    };
    
    this._showNextRow = function(oRow){
        if(oRow !== _aRows[0]){
            return;
        }
        
        //CHECK IF FIRST ROW MUST BE REMOVED
        if(_aRows.length > 0 && _aRows[0].getY() >= _iMaskHeight){
            _aRows[0].unload();
            _aRows.splice(0,1);
        }
        
        
        var oRow = new CHistoryRow(0,0,_iNextPlayerValueCard,_iNextDealerValueCard,_iNextWinBet,_oSpriteCell,_oContainerRows);
        _aRows.push(oRow);
    };
    
    this.setPosition = function(iX,iY){
        _oContainer.x = iX;
        _oContainer.y = iY;
    };
    
    _oThis = this;
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY);
}