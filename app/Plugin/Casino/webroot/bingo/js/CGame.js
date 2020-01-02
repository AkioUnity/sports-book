function CGame(oData){
    var _bGameStarted;
    var _bFirstPlay;

    var _iBank;
    var _iCurPlayerMoney;
    var _iCurNumCards;
    var _iCurNumBalls;
    var _iCurCoinBet;
    var _iCurTotBet;
    var _iTotWin;
    var _iAdCounter;
    var _iCurBallExtracted;
    var _iTimeElaps;
    var _iWinOccurrence;
    
    var _aCards;
    var _aCurNumExtracted;
    var _aPrizes;

    var _oInterface;
    var _oEndPanel = null;
    var _oParent;
    var _oAnimBalls;
    var _oCardSelectionPanel;
    var _oNumberBoard;
    var _oPayTable;
    var _oBoardContainer;
    
    this._init = function(){
        _bGameStarted = false;
        _bFirstPlay = true;
        
        _iBank = BANK;
        _iCurPlayerMoney = START_PLAYER_MONEY;
        _iAdCounter = 0;
        _iTotWin = 0;
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(oBg); 

        _oBoardContainer = new createjs.Container();    
        s_oStage.addChild(_oBoardContainer);

        _oNumberBoard = new CNumberBoard(620,82,_oBoardContainer);
        _oInterface = new CInterface();
        _oCardSelectionPanel = new CCardSelection(_iCurPlayerMoney,s_oStage);
        
    };
    
    this.unload = function(){
        _oInterface.unload();
        _oPayTable.unload();
        _oAnimBalls.unload();
        
        if(_oEndPanel !== null){
            _oEndPanel.unload();
        }
        
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();
        
        s_oGame = null;
    };
    
    this.initGame = function(iNumCards,iCurNumBalls,iCurCoinBet,iMoney,iTotBet,iIndexNumBall, grids, numbers){
        setVolume(s_oSoundTrack,0.4);
        
        _iCurPlayerMoney -= iTotBet;
        _iBank += iTotBet;
        _oInterface.refreshMoney(_iCurPlayerMoney);
        
        _iCurNumCards = iNumCards;
        _iCurNumBalls = iCurNumBalls;
        _iCurCoinBet = iCurCoinBet;
        
        _iCurTotBet = iTotBet;
        _oInterface.refreshTotBet(_iCurTotBet,_iCurCoinBet);

        _oAnimBalls = new CAnimBalls(340, -30,_iCurNumBalls,_oBoardContainer);

        this._generateNewCardSet(grids, numbers);

        _aPrizes = PAYTABLE_INFO[iIndexNumBall];
        _oPayTable = new CPaytablePanel(s_oStage);
        _oPayTable.initPrizes(_aPrizes);
    };
    
    this._resetGame = function(){
        _bGameStarted = false;
        _iTotWin = 0;
        
        if(_oAnimBalls !== undefined){
            _oAnimBalls.unload();
        }
        
        _oAnimBalls = new CAnimBalls(340, -30,_iCurNumBalls,_oBoardContainer);
        for(var i=0;i<_aCards.length;i++){
            _aCards[i].reset();
        }
        _oNumberBoard.reset();
    };
    
    this._removeCards = function(){
        for(var i=0;i<_aCards.length;i++){
            _aCards[i].unload();
        }
    };
    
    this.startGame = function(){
		if(!_bFirstPlay){
			if(_iCurPlayerMoney - _iCurTotBet < 0){
				//NOT ENOUGH MONEY
				this.gameOver();
				return;
			}
		}
        
		var data = {};
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/bingoPlay",
			dataType: "json",
			data: {data:JSON.stringify(data)},
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			if(!_bFirstPlay){
				self._resetGame();
				
				_iCurPlayerMoney -= _iCurTotBet;
				_iBank += _iCurTotBet;
				_oInterface.refreshMoney(_iCurPlayerMoney);
			}
			_bFirstPlay = false;

			_iCurBallExtracted = 0;
			_aCurNumExtracted = new Array();
			_aCurNumExtracted = response.numbers;
			self.extractNextNumber();
			_iTimeElaps = 0;
			_bGameStarted = true;
		});
    };
    
    this._generateNewCardSet = function(grids, numbers){
        var aPos = CARD_POSITION["num_"+_iCurNumCards];
        
        _aCards = new Array();
        for(var i=0;i<_iCurNumCards;i++){
            
            var oCard = new CCard(aPos[i].x,aPos[i].y,aPos[i].scale,_oBoardContainer, grids[i], numbers[i]);
            _aCards.push(oCard);
        } 
    };
    
    this.extractNextNumber = function(){
        if(_iCurBallExtracted === _aCurNumExtracted.length){
            //EXTRACTION IS OVER
            _bGameStarted = false;
            this._calculateWins();
            
            _oInterface.enableGUI();
            
            for(var i=0;i<_aCards.length;i++){
                _aCards[i].hideHighlight();
            }
            
            _iAdCounter++;
            if(_iAdCounter === AD_SHOW_COUNTER){
                //SHOW ADS
                _iAdCounter = 0;
                $(s_oMain).trigger("show_interlevel_ad");
            }
            $(s_oMain).trigger("save_score",_iCurPlayerMoney);
            return;
        }
        
        _oAnimBalls.extractNextBall(_aCurNumExtracted[_iCurBallExtracted],_iCurBallExtracted+1);
        
        //REFRESH NUMBER BOARD
        _oNumberBoard.numExtracted(_aCurNumExtracted[_iCurBallExtracted]);
        
        //CHECK NUMBER EXTRACTED IN CARDS
        for(var i=0;i<_aCards.length;i++){
            _aCards[i].findNumberExtracted(_aCurNumExtracted[_iCurBallExtracted]);
        }
        
        _iCurBallExtracted++;
        
    };
    
    this._calculateWins = function(){
        _iTotWin = 0;
        for(var i=0;i<_aCards.length;i++){
            var iNumRow = _aCards[i].getRowHighlighted();
            if(iNumRow > 0){
                _iTotWin += _iCurCoinBet*_aPrizes[iNumRow-1];
                _aCards[i].initWinAnim();
            }
        }
        
        _oInterface.refreshWin(_iTotWin);
        
        if(_iTotWin > 0){
            _iCurPlayerMoney += _iTotWin;
            _oInterface.refreshMoney(_iCurPlayerMoney);
            _iBank -= _iTotWin;
            playSound("win",1,0);
        }
        
    };
    
    this.onPaytable = function(){
        _oPayTable.show();
    };
    
    this.onBuyNewCards = function(){
        this._resetGame();
        this._removeCards();
        this._resetGame();
        _bFirstPlay = true;
        
        _oCardSelectionPanel = new CCardSelection(_iCurPlayerMoney,s_oStage);
    };
 
    this.onExit = function(){
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("share_event",_iCurPlayerMoney);
        
        this.unload();
        s_oMain.gotoMenu();
    };
    
    this.gameOver = function(){  
        _oEndPanel = CEndPanel(s_oSpriteLibrary.getSprite('msg_box'));
        _oEndPanel.show();
    };

    this.update = function(){

    };

    s_oGame=this;
    
    WIN_OCCURRENCE = oData.win_occurrence;
    COIN_BETS = oData.coin_bet;
    STARTING_BET = oData.starting_bet;
    BANK = oData.bank_money;
    START_PLAYER_MONEY = oData.start_player_money;
    TIME_EXTRACTION = oData.time_extraction;
    PAYTABLE_INFO = oData.paytable;
    ENABLE_FULLSCREEN = oData.fullscreen;
    AD_SHOW_COUNTER = oData.ad_show_counter;
    
    _oParent=this;
    this._init();
}

var s_oGame;
