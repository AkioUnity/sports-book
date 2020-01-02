function CGame(oData){
    var _bUpdate = false;
    var _bFirstSpin;
    var _bBonus;
    var _bFirstPlay = true;
    var _iCurState;
    var _iCurReelLoops;
    var _iNextColToStop;
    var _iNumReelsStopped;
    var _iLastLineActive;
    var _iTimeElaps;
    var _iCurWinShown;
    var _iCurBet;
    var _iTotBet;
    var _iMoney;
    var _iNumIndexHold;
    var _iNumItemInBonus = 0;
    var _iTotWin;
    var _iAdsShowingCont;
    var _iNumSpinCont;
    var _aMovingColumns;
    var _aStaticSymbols;
    var _aWinningLine;
    var _aReelSequence;
    var _aFinalSymbolCombo;
    var _aHoldText;
    var _aHitAreaColumn;
    var _aSelectCol;
    var _aIndexColumnHold;
    var _oReelSound;
    var _oCurSymbolWinSound;
    var _oBg;
    var _oFrontSkin;
    var _oInterface;
    var _oPayTable = null;
    var _oBonusPanel;
    
    this._init = function(){
        _iCurState = GAME_STATE_IDLE;
        _bFirstSpin = true;
        _iCurReelLoops = 0;
        _iNumReelsStopped = 0;
        _iNumIndexHold = 0;
        _iNumSpinCont = 0;
        
        _aReelSequence = new Array(0,1,2,3,4);
        _iNextColToStop = _aReelSequence[0];
        _iLastLineActive = NUM_PAYLINES;
        _iMoney = TOTAL_MONEY;
        _iCurBet = MIN_BET;
        _iTotBet = _iCurBet * _iLastLineActive;
        
        _aFinalSymbolCombo = new Array();
        for(var i=0;i<NUM_ROWS;i++){
            _aFinalSymbolCombo[i] = new Array();
            for(var j=0;j<NUM_REELS;j++){
                _aFinalSymbolCombo[i][j] = 0;
            }
        }
        
        s_oTweenController = new CTweenController();
        
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(_oBg);

        this._initReels();

        _oFrontSkin = createBitmap(s_oSpriteLibrary.getSprite('mask_slot'));
        s_oStage.addChild(_oFrontSkin);

        
        this._initStaticSymbols();
        
        this._initHitAreaColumn();
        _oInterface = new CInterface(_iCurBet,_iTotBet,_iMoney);
        
        _oBonusPanel = new CBonusPanel();
        _oPayTable = new CPayTablePanel();
		
        if(_iMoney < _iTotBet){
                _oInterface.disableSpin();
        }

        _bUpdate = true;
    };
    
    this.unload = function(){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.stop();
            s_oSoundTrack = null;
        }
        
        s_oStage.removeChild(_oBg);
        s_oStage.removeChild(_oFrontSkin);
        _oInterface.unload();
        _oPayTable.unload();
        
        for(var k=0;k<_aMovingColumns.length;k++){
            _aMovingColumns[k].unload();
        }
        
        for(var i=0;i<NUM_ROWS;i++){
            for(var j=0;j<NUM_REELS;j++){
                _aStaticSymbols[i][j].unload();
            }
        } 
        
        _oBonusPanel.unload();
    };
    
    this._initReels = function(){  
        var iXPos = REEL_OFFSET_X;
        var iYPos = REEL_OFFSET_Y;
        
        var iCurDelay = 0;
        _aMovingColumns = new Array();
        for(var i=0;i<NUM_REELS;i++){ 
            _aMovingColumns[i] = new CReelColumn(i,iXPos,iYPos,iCurDelay);
            _aMovingColumns[i+NUM_REELS] = new CReelColumn(i+NUM_REELS,iXPos,iYPos + (SYMBOL_SIZE*NUM_ROWS),iCurDelay );
            iXPos += SYMBOL_SIZE + SPACE_BETWEEN_SYMBOLS;
            iCurDelay += REEL_DELAY;
        }
    };
    
    this._initStaticSymbols = function(){
        var iXPos = REEL_OFFSET_X;
        var iYPos = REEL_OFFSET_Y;
        _aStaticSymbols = new Array();
        for(var i=0;i<NUM_ROWS;i++){
            _aStaticSymbols[i] = new Array();
            for(var j=0;j<NUM_REELS;j++){
                var oSymbol = new CStaticSymbolCell(i,j,iXPos,iYPos);
                _aStaticSymbols[i][j] = oSymbol;
                
                iXPos += SYMBOL_SIZE + SPACE_BETWEEN_SYMBOLS;
            }
            iXPos = REEL_OFFSET_X;
            iYPos += SYMBOL_SIZE;
        }
    };
    
    this._initHitAreaColumn = function(){
        _aIndexColumnHold = new Array();
        _aSelectCol = new Array();
        iX = 376;
        iY = 120;
        for(var j=0;j<NUM_REELS;j++){
            var oSelect = createBitmap( s_oSpriteLibrary.getSprite('hold_col'));
            oSelect.x = iX;
            oSelect.y = iY;
            oSelect.visible = false;
            s_oStage.addChild(oSelect);
            
            iX += 150;
            
            _aSelectCol.push(oSelect);
            _aIndexColumnHold[j] = false;
        }
        
        _aHoldText = new Array();
        _aHitAreaColumn = new Array();
        
        var iX = 381;
        var iY = 108;
        var oSprite = s_oSpriteLibrary.getSprite('hit_area_col');
        for(var i=0;i<NUM_REELS;i++){
            var oText = new createjs.Text(TEXT_HOLD,"22px "+FONT_GAME, "#ffffff");
            oText.visible = false;
            oText.x = iX + oSprite.width/2;
            oText.y = iY + oSprite.height - 20;
            oText.shadow = new createjs.Shadow("#000", 1, 1, 2);
            oText.textAlign = "center";
            s_oStage.addChild(oText);
            _aHoldText[i] = oText;
            
            var oHitArea = new CGfxButton(iX + (oSprite.width/2),iY +(oSprite.height/2),oSprite);
            oHitArea.setVisible(false);
            oHitArea.addEventListenerWithParams(ON_MOUSE_UP, this._onHitAreaCol, this,{index:i});
            
            iX += 150;
            
            _aHitAreaColumn.push(oHitArea);
        }
        
        
    };
    
    this._generateRandSymbols = function() {
        var aRandSymbols = new Array();
        for (var i = 0; i < NUM_ROWS; i++) {
                var iRandIndex = Math.floor(Math.random()* s_aRandSymbols.length);
                aRandSymbols[i] = s_aRandSymbols[iRandIndex];
        }

        return aRandSymbols;
    };
    
    this.reelArrived = function(iReelIndex,iCol) {
        if(_iCurReelLoops>MIN_REEL_LOOPS ){
            
            if (_iNextColToStop === iCol) {
                
                if (_aMovingColumns[iReelIndex].isReadyToStop() === false) {
                    var iNewReelInd = iReelIndex;
                    if (iReelIndex < NUM_REELS) {
                            iNewReelInd += NUM_REELS;
                            
                            _aMovingColumns[iNewReelInd].setReadyToStop();
                            
                            _aMovingColumns[iReelIndex].restart(new Array(_aFinalSymbolCombo[0][iReelIndex],
                                                                          _aFinalSymbolCombo[1][iReelIndex],
                                                                          _aFinalSymbolCombo[2][iReelIndex]), true);
                            
                    }else {
                            iNewReelInd -= NUM_REELS;
                            _aMovingColumns[iNewReelInd].setReadyToStop();
                            
                            _aMovingColumns[iReelIndex].restart(new Array(_aFinalSymbolCombo[0][iNewReelInd],
                                                                          _aFinalSymbolCombo[1][iNewReelInd],
                                                                          _aFinalSymbolCombo[2][iNewReelInd]), true);    
                    }
                    
                }
            }else {
                    _aMovingColumns[iReelIndex].restart(this._generateRandSymbols(),false);
            }   
        }else {    
            _aMovingColumns[iReelIndex].restart(this._generateRandSymbols(), false);
            if(iReelIndex === 0){
                _iCurReelLoops++;
            }
            
        }
    };
    
    this.increaseReelLoops = function(){
        _iCurReelLoops += 2;
    };
    
    
    this.stopNextReel = function() {
        _iNumReelsStopped++;
        if(_iNumReelsStopped%2 === 0){
            
            if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                createjs.Sound.play("reel_stop",{volume:0.3});
            }
            
            _iNextColToStop = _aReelSequence[_iNumReelsStopped/2];
            
            if (_iNumReelsStopped === (NUM_REELS*2) ) {
                this._endReelAnimation();
            }
        }    
    };
    
    this._endReelAnimation = function(){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oReelSound.stop();
        }

        _iCurReelLoops = 0;
        _iNumReelsStopped = 0;
        _iNextColToStop = _aReelSequence[0];
        
        for(var k=0;k<NUM_REELS;k++){
            _aIndexColumnHold[k] =  false;
            _aSelectCol[k].visible = false;
            _aMovingColumns[k].setHold(false);
            _aMovingColumns[k+NUM_REELS].setHold(false);
        }
        
        _iNumIndexHold = 0;
        
        //INCREASE MONEY IF THERE ARE COMBOS
        if(_aWinningLine.length > 0){
            //HIGHLIGHT WIN COMBOS IN PAYTABLE
            for(var i=0;i<_aWinningLine.length;i++){
                _oPayTable.highlightCombo(_aWinningLine[i].value,_aWinningLine[i].num_win);
                
                if(_aWinningLine[i].line !== -1){
                    _oInterface.showLine(_aWinningLine[i].line);
                }
                var aList = _aWinningLine[i].list;
                for(var k=0;k<aList.length;k++){
                    _aStaticSymbols[aList[k].row][aList[k].col].show(aList[k].value);
                }

            }

            _iTotWin *=_iCurBet;
            _iMoney += _iTotWin;
            SLOT_CASH -= _iTotWin;
            
            if(_iTotWin>0){
                    _oInterface.refreshMoney(_iMoney);
                    _oInterface.refreshWinText(_iTotWin);
            }
            _iTimeElaps = 0;
            _iCurState = GAME_STATE_SHOW_ALL_WIN;
            
            if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                _oCurSymbolWinSound = createjs.Sound.play("win");
            }
            
            _bFirstSpin = true;
            if(_bBonus === false){
                _oInterface.disableBetBut(false);
                _oInterface.enableGuiButtons();
            }
        }else{
            if(_bFirstSpin){
                this.enableColumnHitArea();
                _bFirstSpin = false;
                _oInterface.enableSpin();
                _oInterface.disableMaxBet();
            }else{
                _oInterface.disableBetBut(false);
                _oInterface.enableGuiButtons();
                _bFirstSpin = true;
            }
            _iCurState = GAME_STATE_IDLE;
        }
        
        
        if(_iMoney < _iTotBet){
            _oInterface.disableSpin();
        }
        
        _iNumSpinCont++;
        if(_iNumSpinCont === _iAdsShowingCont){
            _iNumSpinCont = 0;
            
            $(s_oMain).trigger("show_interlevel_ad");
        }

        $(s_oMain).trigger("save_score",_iMoney);
    };

    this.hidePayTable = function(){
        _oPayTable.hide();
    };
    
    this._showWin = function(){
        var iLineIndex;
        if(_iCurWinShown>0){ 
            if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                _oCurSymbolWinSound.stop();
            }
            
            if(_aWinningLine[_iCurWinShown-1].line !== -1){
                iLineIndex = _aWinningLine[_iCurWinShown-1].line;
                _oInterface.hideLine(iLineIndex);
            }
            var aList = _aWinningLine[_iCurWinShown-1].list;
            for(var k=0;k<aList.length;k++){
                _aStaticSymbols[aList[k].row][aList[k].col].stopAnim();
            }
        }
        
        if(_iCurWinShown === _aWinningLine.length){
            _iCurWinShown = 0;
        }
        
        if(_aWinningLine[_iCurWinShown].line !== -1){
            iLineIndex = _aWinningLine[_iCurWinShown].line;
            _oInterface.showLine(iLineIndex);
        }

        var aList = _aWinningLine[_iCurWinShown].list;
        for(var k=0;k<aList.length;k++){
            _aStaticSymbols[aList[k].row][aList[k].col].show(aList[k].value);
        }
            

        _iCurWinShown++;
        
    };
    
    this._hideAllWins = function(){
        for(var i=0;i<_aWinningLine.length;i++){
            var aList = _aWinningLine[i].list;
            for(var k=0;k<aList.length;k++){
                _aStaticSymbols[aList[k].row][aList[k].col].stopAnim();
            }
        }
        
        _oInterface.hideAllLines();

        _iTimeElaps = 0;
        _iCurWinShown = 0;
        _iTimeElaps = TIME_SHOW_WIN;
        _iCurState = GAME_STATE_SHOW_WIN;
        
        if(_bBonus){
            _oBonusPanel.show(_iNumItemInBonus,_iCurBet);
        }
    };
    
    this.enableColumnHitArea = function(){
        for(var i=0;i<NUM_REELS;i++){
            _aHoldText[i].visible = true;
            _aHitAreaColumn[i].setVisible(true);
        }
    };

    this.disableColumnHitArea = function(){
        for(var i=0;i<NUM_REELS;i++){
            _aHoldText[i].visible = false;
            _aHitAreaColumn[i].setVisible(false);
        }
    };
    
    this.activateLines = function(iLine){
        _iLastLineActive = iLine;
        this.removeWinShowing();
		
		var iNewTotalBet = _iCurBet * _iLastLineActive;

		_iTotBet = iNewTotalBet;
		_oInterface.refreshTotalBet(_iTotBet);
		_oInterface.refreshNumLines(_iLastLineActive);
		
		
		if(iNewTotalBet>_iMoney){
			_oInterface.disableSpin();
		}else{
			_oInterface.enableSpin();
		}
    };
	
    this.addLine = function(){
        if(_iLastLineActive === NUM_PAYLINES){
            _iLastLineActive = 1;  
        }else{
            _iLastLineActive++;    
        }
		
		var iNewTotalBet = _iCurBet * _iLastLineActive;

		_iTotBet = iNewTotalBet;
		_oInterface.refreshTotalBet(_iTotBet);
		_oInterface.refreshNumLines(_iLastLineActive);
		
		
		if(iNewTotalBet>_iMoney){
			_oInterface.disableSpin();
		}else{
			_oInterface.enableSpin();
		}
    };
    
    this.changeCoinBet = function(){
        var iNewBet = Math.floor((_iCurBet+0.05) * 100)/100;
		var iNewTotalBet;
		
        if(iNewBet>MAX_BET){
            _iCurBet = MIN_BET;
            _iTotBet = _iCurBet * _iLastLineActive;
            _oInterface.refreshBet(_iCurBet);
            _oInterface.refreshTotalBet(_iTotBet);
			iNewTotalBet = _iTotBet;
        }else{
            iNewTotalBet = iNewBet * _iLastLineActive;

			_iCurBet += 0.05;
			_iCurBet = Math.floor(_iCurBet * 100)/100;
			_iTotBet = iNewTotalBet;
			_oInterface.refreshBet(_iCurBet);
			_oInterface.refreshTotalBet(_iTotBet);       
        }
        
        if(iNewTotalBet>_iMoney){
			_oInterface.disableSpin();
		}else{
			_oInterface.enableSpin();
		}
		
    };
	
    this.onMaxBet = function(){
        var iNewBet = MAX_BET;
		_iLastLineActive = NUM_PAYLINES;
        
        var iNewTotalBet = iNewBet * _iLastLineActive;

		_iCurBet = MAX_BET;
		_iTotBet = iNewTotalBet;
		_oInterface.refreshBet(_iCurBet);
		_oInterface.refreshTotalBet(_iTotBet);
		_oInterface.refreshNumLines(_iLastLineActive);
        
		if(iNewTotalBet>_iMoney){
			_oInterface.disableSpin();
		}else{
			_oInterface.enableSpin();
			this.onSpin();
		}
    };
    
    this._onHitAreaCol = function(oParam){
        var iIndexCol = oParam.index;
        if(_aIndexColumnHold[iIndexCol] === true){
            _aIndexColumnHold[iIndexCol] =  false;
            _aSelectCol[iIndexCol].visible = false;
            _aHoldText[iIndexCol].visible = true;
            
            _iNumIndexHold--;
            
            _aMovingColumns[iIndexCol].setHold(false);
            _aMovingColumns[iIndexCol+NUM_REELS].setHold(false);
            
        }else if(_iNumIndexHold < MAX_NUM_HOLD){
            _aIndexColumnHold[iIndexCol] =  true;
            _iNumIndexHold++; 
            _aSelectCol[iIndexCol].visible = true;
            _aHoldText[iIndexCol].visible = false;
            _aMovingColumns[iIndexCol].setHold(true);
            _aMovingColumns[iIndexCol+NUM_REELS].setHold(true);
            
            if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                createjs.Sound.play("press_hold");
            }
        }
    };
    
    this.removeWinShowing = function(){
        _oPayTable.resetHighlightCombo();
        
        _oInterface.resetWin();
        
        for(var i=0;i<NUM_ROWS;i++){
            for(var j=0;j<NUM_REELS;j++){
                _aStaticSymbols[i][j].hide();
            }
        }
        
        for(var k=0;k<_aMovingColumns.length;k++){
            _aMovingColumns[k].activate();
        }
        
        _iCurState = GAME_STATE_IDLE;
    };
    
    this.endBonus = function(iBonus){
        iBonus *= _iCurBet;
        _iMoney += iBonus;
        _oInterface.refreshMoney(_iMoney);
        
	SLOT_CASH -= iBonus;
		
        _oInterface.disableBetBut(false);
        _oInterface.enableGuiButtons();
    };
    
    this.onSpin = function(){
		var data = {
			"amount": _iCurBet,
			"lines": _iLastLineActive,
			"hold" : _aIndexColumnHold
		};
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/slotChristmasPlay",
			dataType: "json",
			data: {data:JSON.stringify(data)},
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
				if(_oCurSymbolWinSound){
					_oCurSymbolWinSound.stop();
				}
				_oReelSound = createjs.Sound.play("reels",{volume:0.3});
			}
			
			self.disableColumnHitArea();
			_oInterface.disableBetBut(true);
			self.removeWinShowing();
			
			//FIND MIN WIN
			MIN_WIN = s_aSymbolWin[0][s_aSymbolWin[0].length-1];
			for(var i=0;i<s_aSymbolWin.length;i++){
				var aTmp = s_aSymbolWin[i];
				for(var j=0;j<aTmp.length;j++){
					if(aTmp[j] !== 0 && aTmp[j] < MIN_WIN){
						MIN_WIN = aTmp[j];
					}
				}
			}
			
		MIN_WIN *= _iCurBet;
			
		if(_bFirstSpin){
				_iMoney -= _iTotBet;
				_oInterface.refreshMoney(_iMoney);
				SLOT_CASH += _iTotBet;
				$(s_oMain).trigger("bet_placed",{bet:_iCurBet,tot_bet:_iTotBet});
			}
			
			localStorage.setItem("bonus", 0);
			localStorage.setItem("bonus", response[1]);
			bWin = response[0][0];
			_bBonus = response[0][1];
			_aWinningLine = response[0][2];
			_aFinalSymbolCombo = response[0][3];
			_iTotWin = response[0][4];
			_iNumItemInBonus = response[0][5];

			_oInterface.hideAllLines();
			_oInterface.disableGuiButtons();
			_bFirstPlay = false;
			
			_iCurState = GAME_STATE_SPINNING;
		});
    };
    
    this.onInfoClicked = function(){
        if(_iCurState === GAME_STATE_SPINNING){
            return;
        }
        
        if(_oPayTable.isVisible()){
            _oPayTable.hide();
        }else{
            _oPayTable.show();
        }
    };

    this.onExit = function(){
        this.unload();
        s_oMain.gotoMenu();
		
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("share_event", {
                img: "200x200.jpg",
                title: TEXT_CONGRATULATIONS,
                msg:  TEXT_MSG_SHARE1+ _iMoney + TEXT_MSG_SHARE2,
                msg_share: TEXT_MSG_SHARING1 + _iMoney + TEXT_MSG_SHARING2
            });
    };
    
    this.getState = function(){
        return _iCurState;
    };
    
    this.update = function(){
        if(_bUpdate === false){
            return;
        }
        
        switch(_iCurState){
            case GAME_STATE_SPINNING:{
                for(var i=0;i<_aMovingColumns.length;i++){
                    _aMovingColumns[i].update(_iNextColToStop);
                }
                break;
            }
            case GAME_STATE_SHOW_ALL_WIN:{
                    _iTimeElaps += s_iTimeElaps;
                    if(_iTimeElaps> TIME_SHOW_ALL_WINS){  
                        this._hideAllWins();
                    }
                    break;
            }
            case GAME_STATE_SHOW_WIN:{
                _iTimeElaps += s_iTimeElaps;
                if(_iTimeElaps > TIME_SHOW_WIN){
                    _iTimeElaps = 0;

                    this._showWin();
                }
                break;
            }
        }
        
	
    };
    
    s_oGame = this;
    
    WIN_OCCURRENCE = oData.win_occurrence;
    SLOT_CASH = oData.slot_cash;
    BONUS_OCCURRENCE = oData.bonus_occurrence;
    MIN_REEL_LOOPS = oData.min_reel_loop;
    REEL_DELAY = oData.reel_delay;
    TIME_SHOW_WIN = oData.time_show_win;
    TIME_SHOW_ALL_WINS = oData.time_show_all_wins;
    TOTAL_MONEY = oData.money;
    MIN_BET = oData.min_bet;
    MAX_BET = oData.max_bet;
    MAX_NUM_HOLD = oData.max_hold;
    PERC_WIN_BONUS_PRIZE_1 = oData.perc_win_bonus_prize_1;
    PERC_WIN_BONUS_PRIZE_2= oData.perc_win_bonus_prize_2;
    PERC_WIN_BONUS_PRIZE_3= oData.perc_win_bonus_prize_3;
    _iAdsShowingCont = oData.num_spin_ads_showing;
    
    new CSlotSettings();
	
	BONUS_PRIZE[0] = JSON.parse(localStorage.getItem("bonus_1"));
	BONUS_PRIZE[1] = JSON.parse(localStorage.getItem("bonus_2"));
	BONUS_PRIZE[2] = JSON.parse(localStorage.getItem("bonus_3"));
    
    this._init();
}

var s_oGame;
var s_oTweenController;