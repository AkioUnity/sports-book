function CCard(iX,iY,iScale,oParentContainer,tempGrid,tempNumber){
    var _iCellSize;
    var _iRowHighlighted;
    var _iWinInterval = -1;
    var _aGrid;
    var _aCardCells;
    var _aRows;
    
    var _oHighlight1;
    var _oHighlight2;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(iX,iY,iScale,tempGrid,tempNumber){
        //GENERATE SEQUENCE OF NUMBERS FOR EVERY COLUM
        _iRowHighlighted = 0;
		
		var aTmpNum = tempNumber;
        _aGrid = new Array();
		_aGrid = tempGrid;

        
        //CREATE CARD GRAPHICALLY
        _oContainer = new createjs.Container();
        _oContainer.scaleX = _oContainer.scaleY = iScale;
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        
        
        var oSprite = s_oSpriteLibrary.getSprite('card_cell');
        _iCellSize = oSprite.width/4;
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: _iCellSize, height: _iCellSize}, 
                        animations: {state_empty:[0],state_fill:[1],state_extracted:[2],state_highlight:[3]}
                   };
                   
        var oSpriteSheet = new createjs.SpriteSheet(oData);  
        
        var iXPos = 0;
        var iYPos = 0;
        _aCardCells = new Array();
        for(var i=0;i<CARD_ROWS;i++){
            _aCardCells[i] = new Array();
            for(var j=0;j<CARD_COLS;j++){
                var oCell = createSprite(oSpriteSheet,"state_"+_aGrid[i][j],0,0,_iCellSize,_iCellSize);
                
                oCell.x = iXPos;
                oCell.y = iYPos;
                _oContainer.addChild(oCell);
                
                _aCardCells[i][j] = oCell;
                if(_aGrid[i][j] === LABEL_FILL){
                    var oText = new createjs.Text(aTmpNum[j][0]," 64px " +PRIMARY_FONT, "#000");
                    oText.x = iXPos + _iCellSize/2;
                    oText.y = iYPos + _iCellSize/2;
                    oText.textAlign = "center";
                    oText.textBaseline = "middle";
                    _oContainer.addChild(oText);
                    _aGrid[i][j] = aTmpNum[j][0];
                    aTmpNum[j].splice(0,1);
                }else{
                    _aGrid[i][j] = 0;
                }
                
                iXPos += _iCellSize; 
            }
            
            iXPos = 0;
            iYPos += _iCellSize;
        }
        
        
        
        this.initRows();
        
        //ADD HIGHLIGHT FRAMES
        _oHighlight1 = createBitmap(s_oSpriteLibrary.getSprite('card_highlight_1'));
        _oHighlight1.x = -28;
        _oHighlight1.y = -27;
        _oHighlight1.visible = false;
        _oContainer.addChild(_oHighlight1);
        
        _oHighlight2 = createBitmap(s_oSpriteLibrary.getSprite('card_highlight_2'));
        _oHighlight2.x = -28;
        _oHighlight2.y = -27;
        _oHighlight2.visible = false;
        _oContainer.addChild(_oHighlight2);
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oContainer);
    };
    
    this.initRows = function(){
        _aRows = new Array();
        for(var i=0;i<CARD_ROWS;i++){
            _aRows[i] = new Array();
            for(var j=0;j<CARD_COLS;j++){
                if(_aGrid[i][j] !== 0){
                    _aRows[i].push(parseInt(_aGrid[i][j]));
                }
            }
        }
    };

    this.reset = function(){
        if(_iWinInterval !== -1){
            clearInterval(_iWinInterval);
            _iWinInterval = -1;
        }
        
        _iRowHighlighted = 0;
        _oHighlight1.visible = false;
        _oHighlight2.visible = false;
        
        for(var i=0;i<_aCardCells.length;i++){
            for(var j=0;j<_aCardCells[i].length;j++){
                if(_aGrid[i][j] === 0){
                    _aCardCells[i][j].gotoAndStop("state_empty");
                }else{
                    _aCardCells[i][j].gotoAndStop("state_fill");
                }
                
            }
        }
    };
    
    this.hideHighlight = function(){
        _oHighlight1.visible = false;
        _oHighlight2.visible = false;
    };
    
    this.checkNumberExtracted = function(aNums){
        var aWinningRow = new Array();
        for(var i=0;i<_aRows.length;i++){
            var iCont = 0;
            for(var j=0;j<_aRows[i].length;j++){
                for(var k=0;k<aNums.length;k++){
                    if(aNums[k] === _aRows[i][j]){
                        iCont++;
                    }
                }
            }

            if(iCont === 5){
                aWinningRow.push(i);
            }
        }
        return aWinningRow;  
    };
    
    this.findNumberExtracted = function(iNum){
        var bFound = false;
        var iRow = 0;
        var iCol = 0;
        for(var i=0;i<_aGrid.length;i++){
            for(var j=0;j<_aGrid[i].length;j++){
                if(_aGrid[i][j] === iNum){
                    bFound = true;
                    iRow = i;
                    iCol = j;
                    break;
                }
            }
        }
        
        if(bFound){
            _aCardCells[iRow][iCol].gotoAndStop("state_extracted");
            
            this._checkWins();
        }
    };
    
    this._checkWins = function(){
        for(var i=0;i<_aCardCells.length;i++){
            var iColCont = 0;
            for(var j=0;j<_aCardCells[i].length;j++){
                if(_aCardCells[i][j].currentFrame === 2){
                    iColCont++;
                }
            }
            
            if(iColCont === 5){
                //ROW COMPLETED!!
                for(var k=0;k<_aCardCells[i].length;k++){
                    _oHighlight2.visible = true;
                    _oHighlight1.visible = false;

                    if(_aCardCells[i][k].currentAnimation  !== "state_empty"){
                        _aCardCells[i][k].gotoAndStop("state_highlight"); 
                    }  
                }
                _iRowHighlighted++;
                
                playSound("win_row",1,0);
            }else if(iColCont === 4 && !_oHighlight2.visible){
                _oHighlight2.visible = false;
                _oHighlight1.visible = true;
            }
        }

    };
    
    this.initWinAnim = function(){
        _oHighlight2.visible = true;
        
        var oParent = this;
        _iWinInterval = setInterval(function(){oParent._playWinAnim();},300);
    };
    
    this._playWinAnim = function(){
        _oHighlight2.visible = !_oHighlight2.visible;
    };
    
    this.printGrid = function(){
        for(var i=0;i<CARD_ROWS;i++){
            for(var j=0;j<CARD_COLS;j++){
                trace("_aGrid["+i+"]["+j+"]: "+_aGrid[i][j]);
            }
        }
    };
    
    this.getRow = function(iRow){
        return _aRows[iRow];
    };
    
    this.getRowHighlighted = function(){
        return _iRowHighlighted;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init(iX,iY,iScale,tempGrid,tempNumber);
    
}