function CGame(oData){
    var _bTouchActive;
    var _bInitGame;
    var _bHighSelected;
    var _bGuess;
    
    var _iCurMoney;
    var _iCurBet;
    var _iCurCard;
    var _iCurTurn;
    var _iCurGuess;
    var _iCurCash;
    var _iNumHighGuess;
    var _iNumHigh;
    var _iNumLowGuess;
    var _iNumLow;
    var _iAdCounter;

    var _aCard;
    var _aLowCard;
    var _aHighCard;
    var _aAceCard;
    var _oCurCard;
    var _oHideCard;

    var _oInterface;
    var _oStartPoint;
    var _oEndPanel = null;
    var _oParent;
    var _oFiches;
    var _oFichesContainer;
    
    this._init = function(){
        _bTouchActive=false;
        _bInitGame=true;
        _bHighSelected = false;
        _bGuess = false;
        
        _iCurMoney=START_MONEY;
        _iCurCash = GAME_CASH;
        _iCurBet=0;
        _iCurCard =0;
        _iCurTurn=1;
        _iCurGuess = 0;
        _iNumHighGuess = 0;
        _iNumHigh = 0;
        _iNumLowGuess = 0;
        _iNumLow = 0;
        _iAdCounter = 0;

        _aCard = new Array();
        _aLowCard = new Array();
        _aHighCard = new Array();
        _aAceCard = new Array();
        
        s_oGameSettings = new CGameSettings();       
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(oBg); 

        _oInterface = new CInterface();       
    
        _oFichesContainer = new createjs.Container();
        s_oStage.addChild(_oFichesContainer);
        
        
        _oStartPoint = {x: CANVAS_WIDTH/2, y: CANVAS_HEIGHT/2 - 30};                      
        
        this._setCards();

        this._setHideCardOnTable();

        _oFiches = new CFichesController(463, 519, _oFichesContainer);
        
        if(_iCurMoney <= 0){
            this.gameOver();
        }else{
            new CHelpPanel();
        }
    };      
    
    this._setCards = function(){
        _aCard = [];
        _aCard = s_oGameSettings.getShuffledCardDeck();
        _aLowCard = [];
        _aHighCard = [];
        _aAceCard = [];
        
        for(var i=0; i<_aCard.length; i++){
            var iValue = s_oGameSettings.getCardValue(_aCard[i]);
            if(iValue > 1 && iValue < 8 ){
                _aLowCard.push(_aCard[i]);
            } else if(iValue > 7 && iValue < 14){
                _aHighCard.push(_aCard[i]);
            } else {
                _aAceCard.push(_aCard[i]);
            }            
        }        
    };  
    
    this.tryShowAd = function(){
        _iAdCounter++;
        if(_iAdCounter === AD_SHOW_COUNTER){
            _iAdCounter = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
    };
    
    this.unload = function(){
        _bInitGame = false;
        
        _oInterface.unload();
        if(_oEndPanel !== null){
            _oEndPanel.unload();
        }
        
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();
   
    };
 
    this.resetBet = function (){
        _oFiches.reset();
        _iCurMoney += _iCurBet;
        _iCurBet = 0;
        _oInterface.refreshMoney(_iCurMoney);
    };
 
    this.updateCurBet = function(iValue){   
        if(iValue < 0){
            iValue = _iCurMoney;
        }
       
        _iCurMoney -= iValue;
        _iCurBet += iValue;        
        
        if(_iCurMoney < 0){
            _iCurBet += _iCurMoney; //_iCurMoney will be negative or zero
            _iCurMoney = 0;
            _oInterface.disableAllIn();
            _aCard = [];
            _aCard = s_oGameSettings.getShuffledCardDeck();
        } else if (_iCurMoney === 0){
            _oInterface.disableAllIn();
        }
        
        _oInterface.refreshMoney(_iCurMoney);
        _oFiches.createFichesPile(_iCurBet);
     
    };
 
    this._setHideCardOnTable = function(){
        _oHideCard = new CCard(_oStartPoint.x, _oStartPoint.y, s_oStage, 0, s_oGameSettings.getCardValue(0));	
    };
 
    this._pickCard = function(szType){
        if(szType === "high"){
            var oCard = _aHighCard.pop(); 
            _oCurCard = new CCard(_oStartPoint.x, _oStartPoint.y, s_oStage, oCard, s_oGameSettings.getCardValue(oCard));
        } else if(szType === "low") {
            var oCard = _aLowCard.pop(); 
            _oCurCard = new CCard(_oStartPoint.x, _oStartPoint.y, s_oStage, oCard, s_oGameSettings.getCardValue(oCard));
        } else {
            var oCard = _aAceCard.pop(); 
            _oCurCard = new CCard(_oStartPoint.x, _oStartPoint.y, s_oStage, oCard, s_oGameSettings.getCardValue(oCard));
        }       
    };
	
	
 
    this.onPlayerSelection = function(szSelected){
		//Casino
		var data = {
			"amount": _iCurBet.toFixed(2)
		};
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/highlowPlay",
			dataType: "json",
			data: {data:JSON.stringify(data)},
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			_oHideCard.unload();
        
			_iCurCash += _iCurBet;
			
			$(s_oMain).trigger("bet_placed",_iCurBet);
			
			if(response.result === "win"){
				//Win Case
				var iAceSpawn = Math.random();
				var iAceProbability = 4/(52 - _iCurCard);
				
				if(szSelected === "high"){
					_bHighSelected = true;
					if( (iAceSpawn < iAceProbability && _aAceCard.length !== 0) || _aHighCard.length === 0 ){
						self._pickCard("ace");
					} else if (_aAceCard.length === 0){
						self._pickCard("high");
					} else  {
						self._pickCard("high");
					}
					
				} else {
					_bHighSelected = false;
					if( (iAceSpawn < iAceProbability && _aAceCard.length !== 0) || _aLowCard.length === 0 ){
						self._pickCard("ace");
					} else if (_aAceCard.length === 0){
						self._pickCard("low");
					} else  {
						self._pickCard("low");
					}
				}            
				
			} else {
				//Lose Case
				if(szSelected === "high"){
					_bHighSelected = true;
					self._pickCard("low");
				} else {
					_bHighSelected = false;
					self._pickCard("high");
				}
				
			}
		 
			_oCurCard.showCard();
		});
		////////////////////////////////////////
        
    };
 
    this.checkWin = function(oCard){
        if(!_bHighSelected && oCard.getRank() < 8){
            var iAmount = _iCurBet*2;
            var oText = new CWinText(TEXT_WIN + " " + TEXT_CURRENCY + iAmount.toFixed(2) + "!" , _oStartPoint.x, iAmount);
            oText.show();            
            _iCurGuess++;
            _iNumLowGuess++;
            _iNumLow++;
            _bGuess = true;
            
            playSound("win",0.3,false);
            
        } else if(_bHighSelected && (oCard.getRank() >= 8 || oCard.getRank() === 1) ){
            var iAmount = _iCurBet*2;
            var oText = new CWinText(TEXT_WIN + " " + TEXT_CURRENCY + iAmount.toFixed(2) + "!" , _oStartPoint.x, iAmount);
            oText.show();
            _iCurGuess++;
            _iNumHighGuess++;
            _iNumHigh++;
            _bGuess = true;
            
             playSound("win",0.3,false);
        } else {
            playSound("game_over",0.3,false);
            
            var iAmount = _iCurBet;
            var oText = new CWinText(TEXT_LOSE + " " + TEXT_CURRENCY + iAmount.toFixed(2) + "!" , _oStartPoint.x, 0);
            oText.show();
            _bGuess = false;
            if (oCard.getRank() < 8){
                _iNumLow++;
            } else {
                _iNumHigh++;
            }
        }
       
        
    };
 
    this._calculateStats = function (){
        
        var iRatio = ((_iCurGuess/_iCurTurn)*100).toFixed(2);
        _oInterface.refreshGuess(_iCurGuess, iRatio);
        
        if(_iNumHigh !== 0){
            var iHighRatio = ((_iNumHighGuess/_iNumHigh)*100).toFixed(2);
            _oInterface.refreshHighs(_iNumHighGuess, _iNumHigh, iHighRatio);
        }
        
        if(_iNumLow !== 0){
            var iLowRatio = ((_iNumLowGuess/_iNumLow)*100).toFixed(2);
            _oInterface.refreshLows(_iNumLowGuess, _iNumLow, iLowRatio);        
        }
    };
 
    this.refreshGame = function(iValue){        
        _iCurMoney += iValue;
        _iCurCash -= iValue;
        
        $(s_oMain).trigger("save_score",[_iCurMoney,"standard"]);

        this._calculateStats();

        _oInterface.refreshMoney(_iCurMoney);
        
        if(_iCurMoney === 0){
            this.gameOver();
            return;
        }
        
        _iCurBet = 0;
        _oFiches.reset();
        
        _oCurCard.unload();
        _iCurCard++;
        _iCurTurn++;
        _oInterface.refreshTurn(_iCurTurn);

        if( (_aLowCard.length === 0) || (_aHighCard.length === 0)){
            _iCurCard = 0;
            this._setCards();
        }
        
        this._setHideCardOnTable();
        
        _oInterface.initState();
    };
 
    this.onGiveUp = function(){
        new CGiveupPanel(s_oSpriteLibrary.getSprite('msg_box'), _iCurMoney);
    };
 
    this.onExit = function(){
	$(s_oMain).trigger("save_score",[_iCurMoney,"standard"]);
        this.unload();
        s_oMain.gotoMenu();
        
        $(s_oMain).trigger("share_event",[_iCurMoney]);
    };
    
    this._onExitHelp = function () {
         _bStartGame = true;
    };
    
    this.gameOver = function(){  
        
        _oEndPanel = CEndPanel(s_oSpriteLibrary.getSprite('msg_box'));
        _oEndPanel.show();
    };

    
    this.update = function(){
        
    };

    s_oGame=this;
    
    WIN_OCCURRENCE = oData.win_occurrence;
    
    START_MONEY = oData.starting_money;   
    GAME_CASH = oData.starting_cash;
    FICHES_VALUE = oData.fiches_value;
    
    TURN_CARD_SPEED = oData.turn_card_speed;
    SHOWTEXT_SPEED = oData.showtext_timespeed;
    
    AD_SHOW_COUNTER = oData.ad_show_counter;
    
    _oParent=this;
    this._init();
}

var s_oGame;
