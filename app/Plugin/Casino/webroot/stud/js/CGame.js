function CGame(oData){
    var _bUpdate = false;
    var _bFold;
    var _iTimeElaps;
    var _iMaxBet;
    var _iState;
    var _iCurIndexDeck;
    var _iCardIndexToDeal;
    var _iGameCash;
    var _iCurMinWin;
    var _iTotWin;
    var _iCurDealerCardShown;
    var _iHandDealer;
    var _iHandPlayer;
    var _iAdsCounter;
    var _szHandResult;
    var _oActionAfterHandReset;
    
    var _aCardsDealing;
    var _aCardsInCurHandForDealer;
	var casinoDealerHand;
    var _aCardDeck;
    var _aCardsInCurHandForPlayer;
    var _aCurActiveCardOffset;
    var _aPlayerCardsInfo;
    var _aDealerCardsInfo;
    var _pStartingPointCard;
    
    var _oStartingCardOffset;
    var _oDealerCardOffset;
    var _oReceiveWinOffset;
    var _oFichesDealerOffset;
    var _oRemoveCardsOffset;
    var _oCardContainer;
    var _oHandEvaluator;
    var _oHelpCursorAnte;
    
    var _oBg;
    var _oInterface;
    var _oSeat;
    var _oMsgBox;
    var _oGameOverPanel;
    
    this._init = function(){
        _iMaxBet = MAX_BET;
        _iState = -1;
        _iTimeElaps = 0;
        _iAdsCounter = 0;
        _iCurIndexDeck = 0;

        s_oTweenController = new CTweenController();
        
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(_oBg);
        
        
        
        _oInterface = new CInterface(TOTAL_MONEY);
        
        _oCardContainer = new createjs.Container();
        s_oStage.addChild(_oCardContainer);
        
        _oHandEvaluator = new CHandEvaluator();
        
        _oSeat = new CSeat();
        _oSeat.setCredit(TOTAL_MONEY);
        
        _oHelpCursorAnte = new CHelpCursor(520,416,s_oSpriteLibrary.getSprite("help_cursor"),s_oStage);
        
        this.reset(false);

        _oStartingCardOffset = new CVector2();
        _oStartingCardOffset.set(1214,228);
        
        _oDealerCardOffset = new CVector2();
        _oDealerCardOffset.set(CANVAS_WIDTH/2 - 199,230);
        
        _oReceiveWinOffset = new CVector2();
        _oReceiveWinOffset.set(418,820);
        
        _oFichesDealerOffset = new CVector2();
        _oFichesDealerOffset.set(0,-CANVAS_HEIGHT);
        
        _oRemoveCardsOffset = new CVector2(454,230);
        
        _aCurActiveCardOffset=new Array(_oSeat.getCardOffset(),_oDealerCardOffset);

	_oGameOverPanel = new CGameOver();
	
        if(_oSeat.getCredit()<s_oGameSettings.getFichesValueAt(0)){
            this._gameOver();
            this.changeState(-1);
        }else{
            _bUpdate = true;
        }
        
        _pStartingPointCard = new CVector2(_oStartingCardOffset.getX(),_oStartingCardOffset.getY());
        
        _oMsgBox = new CMsgBox();
        this.changeState(STATE_GAME_WAITING_FOR_BET);
    };
    
    this.unload = function(){
	_bUpdate = false;
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.stop();
        }

        for(var i=0;i<_aCardsDealing.length;i++){
            _aCardsDealing[i].unload();
        }
        
        _oInterface.unload();
        _oGameOverPanel.unload();
        _oMsgBox.unload();
        s_oStage.removeAllChildren();
    };
    
    this.reset = function(bRebet){
        _iTimeElaps=0;
        _iCurIndexDeck = 0;
        _iCardIndexToDeal=0;
        _oSeat.reset();

        _aCardsDealing=new Array();
        _aCardsDealing.splice(0);
        
        _aCardsInCurHandForDealer = new Array();
        _aCardsInCurHandForPlayer = new Array();
        
        _oInterface.reset();

        _oInterface.enableBetFiches(bRebet);

        this.shuffleCard();
    };
    
    this.shuffleCard = function(){
        _aCardDeck=new Array();
        _aCardDeck=s_oGameSettings.getShuffledCardDeck();
    };
    
    this.changeState = function(iState){
        _iState=iState;
        
        switch(iState){
            case STATE_GAME_WAITING_FOR_BET:{
                    _oInterface.displayMsg(TEXT_DISPLAY_MSG_WAITING_BET,TEXT_MIN_BET+": "+MIN_BET + "\n" + TEXT_MAX_BET+": "+MAX_BET);
                    break;
            }
            case STATE_GAME_DEALING:{
                    _oInterface.disableButtons();
                    _oInterface.displayMsg(TEXT_DISPLAY_MSG_DEALING);
                    this._dealing();
                    break;
            }
        }
    };

    this.cardFromDealerArrived = function(oCard,bDealerCard,iCount){
        if(bDealerCard === false || (bDealerCard && iCount === 9)){
            oCard.showCard();
        }
        
        if(iCount<10){
            s_oGame._dealing();
        }
    };
    
    this._showWin = function(){
	if(_bFold){
		this._playerLose(); 
        }else if(_iHandDealer === NO_HAND && _szHandResult !== "dealer"){
                //DEALER PAYS 2xANTE BET
                this._playerWin(TEXT_DISPLAY_MSG_NOT_QUALIFY);
        }else if(_szHandResult === "player"){
            this._playerWin(TEXT_HAND_WON_PLAYER);
        }else if(_szHandResult === "dealer" && _iHandDealer !== NO_HAND){
                this._playerLose(); 
        }else{
            //STAND OFF
            this._standOff();
        }

        if(_szHandResult === "player"){
            playSound("win", 1, 0);
        }else{
            playSound("lose", 1, 0);
        }
        
        this.changeState(STATE_GAME_DISTRIBUTE_FICHES);
        _oInterface.refreshCredit(_oSeat.getCredit());
        
        setTimeout(function(){
                            _oSeat.resetBet();
                            s_oGame.changeState(STATE_GAME_WAITING_FOR_BET);
                            _oInterface.enableBetFiches(true);
                        },TIME_CARD_REMOVE*3);
    };
    
    this._playerWin = function(szText){
        _oSeat.increaseCredit(_iTotWin);
        _iGameCash -= _iTotWin;
        _oInterface.displayMsg(TEXT_DISPLAY_MSG_SHOWDOWN,TEXT_DISPLAY_MSG_PLAYER_WIN + " " + _iTotWin+TEXT_CURRENCY);

        _oSeat.initMovement(0,_oReceiveWinOffset.getX(),_oReceiveWinOffset.getY());
        _oSeat.initMovement(1,_oReceiveWinOffset.getX(),_oReceiveWinOffset.getY());
        _oInterface.showResultText(szText);
    };

    this._playerLose = function(bFold){
        _oInterface.displayMsg(TEXT_DISPLAY_MSG_SHOWDOWN,TEXT_DISPLAY_MSG_PLAYER_LOSE);
        _oSeat.initMovement(0,_oFichesDealerOffset.getX(),_oFichesDealerOffset.getY());
        
        if(!bFold){
            _oSeat.initMovement(1,_oFichesDealerOffset.getX(),_oFichesDealerOffset.getY());
        }
        _oInterface.showResultText(TEXT_HAND_WON_DEALER);
    };
    
    this._standOff = function(){
        _oSeat.increaseCredit(_iTotWin);
        _iGameCash -= _iTotWin;
        
        _oInterface.displayMsg(TEXT_DISPLAY_MSG_SHOWDOWN,TEXT_DISPLAY_MSG_STANDOFF);
        _oSeat.initMovement(0,_oReceiveWinOffset.getX(),_oReceiveWinOffset.getY());
        _oSeat.initMovement(1,_oReceiveWinOffset.getX(),_oReceiveWinOffset.getY());
        
        _oInterface.showResultText(TEXT_DISPLAY_MSG_STANDOFF);
    };
    
    this._dealing = function(){
        if(_iCardIndexToDeal<10){
                var oCard = new CCard(_oStartingCardOffset.getX(),_oStartingCardOffset.getY(),_oCardContainer);
                var pEndingPoint;

                //THIS CARD IS FOR THE DEALER
                if((_iCardIndexToDeal%_aCurActiveCardOffset.length) === 1){
                    pEndingPoint=new CVector2(_oDealerCardOffset.getX()+((CARD_WIDTH/2 + 7)*_iCardIndexToDeal),_oDealerCardOffset.getY());

                    var oInfo = _aDealerCardsInfo.splice(0,1);
                    var iFotogram = oInfo[0].fotogram;
                    var iValue = oInfo[0].rank;
                    oCard.setInfo(_pStartingPointCard,pEndingPoint,iFotogram,iValue,true,_iCardIndexToDeal);
                    oCard.addEventListener(ON_CARD_SHOWN,this._onCardShown);
					
					var cardInfo = [_pStartingPointCard,pEndingPoint,iFotogram,iValue,true,_iCardIndexToDeal];
					casinoDealerHand.push(cardInfo);
                    
                    _aCardsInCurHandForDealer.push(oCard);
                }else{
                    var oInfo = _aPlayerCardsInfo.splice(0,1);
                    var iFotogram = oInfo[0].fotogram;
                    var iValue = oInfo[0].rank;
                    oCard.setInfo(_pStartingPointCard,_oSeat.getAttachCardOffset(),iFotogram,
                                                    iValue,false,_iCardIndexToDeal);
                    
                    _oSeat.newCardDealed();
                    _aCardsInCurHandForPlayer.push(oCard);
                }

                _aCardsDealing.push(oCard);
                _iCardIndexToDeal++;

                oCard.addEventListener(ON_CARD_ANIMATION_ENDING,this.cardFromDealerArrived);

                playSound("card", 1, 0); 
        }else{
            setTimeout(function(){
                                    s_oGame.changeState(STATE_GAME_PLAYER_TURN);
                                    _oInterface.displayMsg(TEXT_DISPLAY_MSG_USER_TURN);
                                    _oInterface.enable(false,true,true);
                                },1000);
            
        }
    };
    
    this._onEndHand = function(){       
        var pRemoveOffset=new CVector2(_oRemoveCardsOffset.getX(),_oRemoveCardsOffset.getY());
        for(var i=0;i<_aCardsDealing.length;i++){
            _aCardsDealing[i].initRemoving(pRemoveOffset);
            _aCardsDealing[i].hideCard();
        }

        _oInterface.clearCardValueText();
        _iTimeElaps=0;
        s_oGame.changeState(STATE_GAME_SHOW_WINNER);

        playSound("fiche_collect", 1, 0);
        
        _iAdsCounter++;
        if(_iAdsCounter === AD_SHOW_COUNTER){
            _iAdsCounter = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
		
	$(s_oMain).trigger("save_score",[_oSeat.getCredit()]);
    };
    
    this._onCardShown = function(){
        if(_iState === STATE_GAME_PLAYER_TURN){
            if(_iCurDealerCardShown === 4){
                _oInterface.showHandValue(_iHandDealer,_iHandPlayer);
                _iState = STATE_GAME_SHOWDOWN;
                s_oGame._showWin();
            }else{
                s_oGame._showNextDealerCard();
            }
            
        }
        
    };
    
    this.setBet = function(iTypeBet,iFicheIndex){
        //CHECK IF THERE IS A PREVIOUS HAND TO RESET
        if(_oInterface.isResultPanelvisible()){
            _oInterface.disableBetFiches();
            _oSeat.clearBet();
            _oActionAfterHandReset = this.setBet;
            this._onEndHand();
            return;
        }

        var aFicheValues = s_oGameSettings.getFichesValues();
        var iFicheValue = aFicheValues[iFicheIndex];
        
        var iTotBet;
        if(iTypeBet === BET_ANTE){
            _iTimeElaps = 0;
            _oHelpCursorAnte.hide();
            iTotBet =_oSeat.getBetAnte() + iFicheValue;
            
			trace("totbet: "+(iTotBet*2));
			trace("credit: "+(_oSeat.getCredit()-iFicheValue));
            if(  (iTotBet*2) > _oSeat.getCredit()-iFicheValue){
                _oInterface.displayMsg(TEXT_NO_MONEY_FOR_RAISE);     
                return;
            }
            
            if( iTotBet > _iMaxBet){
                _oMsgBox.show(TEXT_ERROR_MAX_BET);
                return;
            }
        }else{
            iTotBet=_oSeat.getBetAnte() * 2;
        }

        

        $(s_oMain).trigger("bet_placed",iTotBet);
        
        if(iTypeBet === BET_ANTE){
            _oSeat.decreaseCredit(iFicheValue);
            _iGameCash += iFicheValue;
            _oSeat.betAnte(iFicheValue);
            _oInterface.enable(true,false,false);
        }else{
            _oSeat.decreaseCredit(iTotBet);
            _iGameCash += iTotBet;
            _oSeat.betRaise();
        }

        _oInterface.refreshCredit(_oSeat.getCredit());
    };
    
    this._gameOver = function(){
        _oGameOverPanel.show();
    };
    
    this._calculateTotalWin = function(){
        if(_iHandDealer === NO_HAND && _szHandResult !== "dealer"){
            //DEALER PAYS 2xANTE BET
            _iTotWin = (_oSeat.getBetAnte() * 2) + (_oSeat.getBetAnte() * 2);
        }else if(_szHandResult === "player"){
            _iTotWin = (_oSeat.getBetAnte() * 2) + ((_oSeat.getBetAnte() * 2) * PAYOUT_MULT[_iHandPlayer]);
        }else if(_szHandResult === "dealer" && _iHandDealer !== NO_HAND){
            _iTotWin = 0;
        }else{
            //STAND OFF
            _iTotWin = _oSeat.getBetAnte() + (_oSeat.getBetAnte() * 2);
        }
    };
    
    this.onRebet = function(){
        if(_oInterface.isResultPanelvisible()){
            _oActionAfterHandReset = this.rebet;
            this._onEndHand();
        }
    };

    this.onDeal = function(){
		var data = {
			"amount": _oSeat.getBetAnte()
		};
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/studDeal",
			dataType: "json",
			data: {data:JSON.stringify(data)},
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			_iCurMinWin = _oSeat.getBetAnte() * PAYOUT_MULT[PAYOUT_MULT.length-1];
			if(_oSeat.getBetAnte() < MIN_BET){
				_oMsgBox.show(TEXT_ERROR_MIN_BET);
				_oInterface.enableBetFiches(false);
				_oInterface.enable(false,false,false);

				return;
			}
			
		   _oCardContainer.removeAllChildren();
		   casinoDealerHand = new Array();
			
			_aPlayerCardsInfo = new Array();
			for(var i=0;i<5;i++){
				_aPlayerCardsInfo.push({fotogram:response[0][i].fotogram,rank:response[0][i].rank,suit:response[0][i].suit});
				_iCurIndexDeck++;
			}
			_aDealerCardsInfo = new Array();
			for(var i=0;i<5;i++){
				_aDealerCardsInfo.push({fotogram:response[1][i].fotogram,rank:response[1][i].rank,suit:response[1][i].suit});
				_iCurIndexDeck++;
			}
			
			var oRetPlayer = _oHandEvaluator.evaluate(_aPlayerCardsInfo);
			var oRetDealer = _oHandEvaluator.evaluate(_aDealerCardsInfo);
            _iHandPlayer = oRetPlayer.ret;
            _iHandDealer = oRetDealer.ret;
                
            _szHandResult = _oHandEvaluator.getWinnerComparingHands(oRetPlayer.sort_hand,oRetDealer.sort_hand,_iHandPlayer,_iHandDealer);
			self._calculateTotalWin();
			
			_oSeat.setPrevBet();
			if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
				createjs.Sound.play("card");
			}
			
			_bFold = false;
			self.changeState(STATE_GAME_DEALING);
		});
    };
    
    this.onFold = function(){
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/studFold",
			dataType: "json",
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			for(var i=0; i<5; i++) {
				_aCardsInCurHandForDealer[i].setInfo(casinoDealerHand[i][0],casinoDealerHand[i][1],response[1][i].fotogram,response[1][i].value,casinoDealerHand[i][4],casinoDealerHand[i][5]);
			}
			
			var playerCards = new Array();
			for(var i=0;i<5;i++){
				playerCards.push({fotogram:response[0][i].fotogram,rank:response[0][i].rank,suit:response[0][i].suit});
			}
			var dealerCards = new Array();
			for(var i=0;i<5;i++){
				dealerCards.push({fotogram:response[1][i].fotogram,rank:response[1][i].rank,suit:response[1][i].suit});
			}
			
			var oRetPlayer = _oHandEvaluator.evaluate(playerCards);
			var oRetDealer = _oHandEvaluator.evaluate(dealerCards);
            _iHandPlayer = oRetPlayer.ret;
            _iHandDealer = oRetDealer.ret;
                
            _szHandResult = _oHandEvaluator.getWinnerComparingHands(oRetPlayer.sort_hand,oRetDealer.sort_hand,_iHandPlayer,_iHandDealer);
			self._calculateTotalWin();

			_bFold = true;
			_szHandResult = "dealer";
			_iCurDealerCardShown = 0;
			self._showNextDealerCard();
		});
    };
    
    this.onRaise = function(){
        if(_iState === STATE_GAME_DISTRIBUTE_FICHES){
            return;
        }
		
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/studRaise",
			dataType: "json",
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			for(var i=0; i<5; i++) {
				_aCardsInCurHandForDealer[i].setInfo(casinoDealerHand[i][0],casinoDealerHand[i][1],response[1][i].fotogram,response[1][i].value,casinoDealerHand[i][4],casinoDealerHand[i][5]);
			}
			
			var playerCards = new Array();
			for(var i=0;i<5;i++){
				playerCards.push({fotogram:response[0][i].fotogram,rank:response[0][i].rank,suit:response[0][i].suit});
			}
			var dealerCards = new Array();
			for(var i=0;i<5;i++){
				dealerCards.push({fotogram:response[1][i].fotogram,rank:response[1][i].rank,suit:response[1][i].suit});
			}
			
			var oRetPlayer = _oHandEvaluator.evaluate(playerCards);
			var oRetDealer = _oHandEvaluator.evaluate(dealerCards);
            _iHandPlayer = oRetPlayer.ret;
            _iHandDealer = oRetDealer.ret;
                
            _szHandResult = _oHandEvaluator.getWinnerComparingHands(oRetPlayer.sort_hand,oRetDealer.sort_hand,_iHandPlayer,_iHandDealer);
			self._calculateTotalWin();

			self.setBet(BET_RAISE,_oInterface.getFicheSelected());
			_iCurDealerCardShown = 0;
			self._showNextDealerCard();
		});
    };
    
    this._showNextDealerCard = function(){
        _aCardsInCurHandForDealer[_iCurDealerCardShown].showCard();
        _iCurDealerCardShown++;
    };
    
    this._checkDeckLength = function(){
        if(_iCurIndexDeck >= _aCardDeck.length){
            _aCardDeck = s_oGameSettings.getShuffledCardDeck();
            _iCurIndexDeck = 0;
        }
    };
    
    this.clearBets = function(){
        if(_iState !== STATE_GAME_WAITING_FOR_BET){
            return;
        }
        _oInterface.enable(false,false,false);
        
        var iCurBet = _oSeat.getStartingBet();
        if(iCurBet>0){
            _oSeat.clearBet();
            _oSeat.increaseCredit(iCurBet);
            _iGameCash -= iCurBet;
            _oInterface.refreshCredit(_oSeat.getCredit());
            var bRebet = _oSeat.checkIfRebetIsPossible();
            _oInterface.enableBetFiches(bRebet);
        }
    };
    
    this.rebet = function(){
        this.clearBets();
        var iCurBet = _oSeat.rebet();
        _iGameCash -= iCurBet;
        
        _oInterface.enable(true,false,false);
        _oInterface.refreshCredit(_oSeat.getCredit());
        _iTimeElaps = BET_TIME;
    };
           
    this.onExit = function(){
        this.unload();
        $(s_oMain).trigger("save_score",[_oSeat.getCredit()]);
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("share_event",_oSeat.getCredit());
		
        s_oMain.gotoMenu();
        
    };
    
    this.getState = function(){
        return _iState;
    };
    
    this._updateDealing = function(){
        for(var i=0;i<_aCardsDealing.length;i++){
            _aCardsDealing[i].update();
        }
    };
    
    this._updateFiches = function(){
        _oSeat.updateFichesController();
    };
    
    this._updateShowWinner = function(){
        for(var k=0;k<_aCardsDealing.length;k++){
            _aCardsDealing[k].update();
        }

        _iTimeElaps+=s_iTimeElaps;
        if(_iTimeElaps>TIME_END_HAND){
            _iTimeElaps=0;
            var bRebet = _oSeat.checkIfRebetIsPossible();

            this.reset(bRebet);
            _oInterface.reset();

            if(_oSeat.getCredit()<s_oGameSettings.getFichesValueAt(0)){
                    this._gameOver();
                    this.changeState(-1);
            }else{
                if(_oSeat.getCredit()<s_oGameSettings.getFichesValueAt(0)){
                    this._gameOver();
                    this.changeState(-1);
                }else{
                    //EXECUTE USER ACTION BEFORE END HAND
                    this.changeState(STATE_GAME_WAITING_FOR_BET);
                    _oActionAfterHandReset.call(this,0,_oInterface.getFicheSelected());
                }
                    
            }
        }
        
    };
    
    this.update = function(){
        if(_bUpdate === false){
            return;
        }

        switch(_iState){
            case STATE_GAME_WAITING_FOR_BET:{
                    _iTimeElaps+=s_iTimeElaps;
                    if( _iTimeElaps > 6000){
                        _iTimeElaps = 0;
                        if(!_oHelpCursorAnte.isVisible() && _oSeat.getBetAnte() === 0){
                            //SHOW IT NEAR ANTE BET
                            _oHelpCursorAnte.show(1);
                        }
                        
                    }
                    break;
            }
            case STATE_GAME_DEALING:{
                    this._updateDealing();
                    break;
            }
            case STATE_GAME_DISTRIBUTE_FICHES:{
                    this._updateFiches();
                    break;
            }
            case STATE_GAME_SHOW_WINNER:{
                    this._updateShowWinner();
                    break;
            }
        }
        
	
    };
    
    s_oGame = this;

    TOTAL_MONEY      = oData.money;
    MIN_BET          = oData.min_bet;
    MAX_BET          = oData.max_bet;
    MULTIPLIERS      = oData.multiplier;
    BET_TIME         = oData.bet_time;
    BLACKJACK_PAYOUT = oData.blackjack_payout;
    WIN_OCCURRENCE   = oData.win_occurrence;
    BET_OCCURRENCE   = oData.bet_occurrence;
    _iGameCash       = oData.game_cash;
    PAYOUT_MULT      = oData.payout;
    TIME_END_HAND    = oData.time_show_hand;
    AD_SHOW_COUNTER  = oData.ad_show_counter; 
    
    this._init();
}

var s_oGame;
var s_oTweenController;