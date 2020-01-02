function CGame(oData){
    var _bUpdate = false;
    var _bWinAssigned;
    var _iState;
    var _iBetMult;
    var _iTimeElaps;
    var _iFactor;
    var _iFrameToStop;
    var _iNumberExtracted;
    var _iCasinoCash;
    var _iCountLastNeighbors;
    var _iHandCont;
    var _aBetMultHistory;
    var _aBetWinHistory;
    var _aNumFicheHistory;
    var _aNumExtractedHistory;
    var _aEnlights;
    var _aFichesToMove;
    var _aRebetHistory;
    var _oWheelSfx;
        
    var _oBg;
    var _oMySeat;
    var _oPlaceHolder;
    var _oInterface;
    var _oTableController;
    var _oAttachFiches;
    var _oMsgBox;
    var _oWheelTopAnim;
    var _oWheelAnim;
    var _oFinalBet;
    var _oNeighborsPanel;
    var _oGameOverPanel;
    var _oBlock;
    
    this._init = function(){
        s_oTweenController = new CTweenController();
        s_oGameSettings = new CRouletteSettings();
        
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(_oBg);
        
        this._initEnlights();
        
        _oAttachFiches = new createjs.Container();
        _oAttachFiches.x = 261;
        _oAttachFiches.y = 264;
        s_oStage.addChild(_oAttachFiches);
        
        _oTableController = new CTableController();
        _oTableController.addEventListener(ON_SHOW_ENLIGHT,this._onShowEnlight);
        _oTableController.addEventListener(ON_HIDE_ENLIGHT,this._onHideEnlight);
        _oTableController.addEventListener(ON_SHOW_BET_ON_TABLE,this._onShowBetOnTable);
        
        _iCountLastNeighbors = 0;
        _iHandCont = 0;
        _iState=-1;
        _iBetMult=37;
        _aBetMultHistory=new Array();
        _aBetWinHistory = new Array();
        _aNumFicheHistory = new Array();
        _aRebetHistory = new Array();

        _oMySeat = new CSeat();

        _oWheelTopAnim = new CWheelTopAnim(493,6);
        _oWheelAnim = new CWheelAnim(0,0);
        _oInterface = new CInterface();
        
        _oFinalBet = new CFinalBetPanel(160,569);
        
        _oNeighborsPanel  = new CNeighborsPanel(_oMySeat.getCredit());
        
        _oGameOverPanel = new CGameOver();
        
        _oMsgBox = new CMsgBox();
		
        var oGraphics = new createjs.Graphics().beginFill("rgba(0,0,0,0.01)").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        _oBlock = new createjs.Shape(oGraphics);
        _oBlock.on("click",function(){});
        _oBlock.visible= false;
        s_oStage.addChild(_oBlock);
		
        _aNumExtractedHistory=new Array();

        _iTimeElaps=0;
        this._onSitDown();
	
        _bUpdate = true;
    };
    
    this.unload = function(){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.stop();
        }

        _oInterface.unload();
        _oTableController.unload();
        _oMsgBox.unload();
        _oFinalBet.unload();
        _oNeighborsPanel.unload();
        _oGameOverPanel.unload();

        s_oStage.removeAllChildren();
    };
    
    this._initEnlights = function(){
        var oBmp;
        _aEnlights = new Array();
        
        /*********************NUMBER ENLIGHT*****************/
        oBmp = new CEnlight(288,175,s_oSpriteLibrary.getSprite('enlight_bet0'),s_oStage);
        _aEnlights["oEnlight_0"] = oBmp;
        
        oBmp = new CEnlight(318,244,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_1"] = oBmp;
        
        oBmp = new CEnlight(342,220,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_2"] = oBmp;
        
        oBmp = new CEnlight(368,198,s_oSpriteLibrary.getSprite('enlight_number3'),s_oStage);
        _aEnlights["oEnlight_3"] = oBmp;
        
        oBmp = new CEnlight(341,262,s_oSpriteLibrary.getSprite('enlight_number4'),s_oStage);
        _aEnlights["oEnlight_4"] = oBmp;
        
        oBmp = new CEnlight(367,238,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_5"] = oBmp;
        
        oBmp = new CEnlight(392,214,s_oSpriteLibrary.getSprite('enlight_number3'),s_oStage);
        _aEnlights["oEnlight_6"] = oBmp;
        
        oBmp = new CEnlight(366,279,s_oSpriteLibrary.getSprite('enlight_number4'),s_oStage);
        _aEnlights["oEnlight_7"] = oBmp;
        
        oBmp = new CEnlight(391,255,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_8"] = oBmp;
        
        oBmp = new CEnlight(416,231,s_oSpriteLibrary.getSprite('enlight_number3'),s_oStage);
        _aEnlights["oEnlight_9"] = oBmp;
        
        oBmp = new CEnlight(390,297,s_oSpriteLibrary.getSprite('enlight_number4'),s_oStage);
        _aEnlights["oEnlight_10"] = oBmp;
        
        oBmp = new CEnlight(415,273,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_11"] = oBmp;
        
        oBmp = new CEnlight(439,249,s_oSpriteLibrary.getSprite('enlight_number12'),s_oStage);
        _aEnlights["oEnlight_12"] = oBmp;
        
        oBmp = new CEnlight(414,315,s_oSpriteLibrary.getSprite('enlight_number4'),s_oStage);
        _aEnlights["oEnlight_13"] = oBmp;
        
        oBmp = new CEnlight(439,291,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_14"] = oBmp;
        
        oBmp = new CEnlight(464,266,s_oSpriteLibrary.getSprite('enlight_number12'),s_oStage);
        _aEnlights["oEnlight_15"] = oBmp;
        
        oBmp = new CEnlight(439,333,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_16"] = oBmp;
        
        oBmp = new CEnlight(464,308,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_17"] = oBmp;
        
        oBmp = new CEnlight(488,283,s_oSpriteLibrary.getSprite('enlight_number1'),s_oStage);
        _aEnlights["oEnlight_18"] = oBmp;
        
        oBmp = new CEnlight(466,351,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_19"] = oBmp;
        
        oBmp = new CEnlight(489,326,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_20"] = oBmp;
        
        oBmp = new CEnlight(513,301,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_21"] = oBmp;
        
        oBmp = new CEnlight(491,371,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_22"] = oBmp;
        
        oBmp = new CEnlight(515,344,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_23"] = oBmp;
        
        oBmp = new CEnlight(539,319,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_24"] = oBmp;
        
        oBmp = new CEnlight(516,389,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_25"] = oBmp;
        
        oBmp = new CEnlight(540,363,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_26"] = oBmp;
        
        oBmp = new CEnlight(564,338,s_oSpriteLibrary.getSprite('enlight_number16'),s_oStage);
        _aEnlights["oEnlight_27"] = oBmp;
        
        oBmp = new CEnlight(542,408,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_28"] = oBmp;
        
        oBmp = new CEnlight(566,381,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_29"] = oBmp;
        
        oBmp = new CEnlight(590,356,s_oSpriteLibrary.getSprite('enlight_number30'),s_oStage);
        _aEnlights["oEnlight_30"] = oBmp;
        
        oBmp = new CEnlight(568,428,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_31"] = oBmp;
        
        oBmp = new CEnlight(593,401,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_32"] = oBmp;
        
        oBmp = new CEnlight(617,376,s_oSpriteLibrary.getSprite('enlight_number30'),s_oStage);
        _aEnlights["oEnlight_33"] = oBmp;
        
        oBmp = new CEnlight(596,448,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_34"] = oBmp;
        
        oBmp = new CEnlight(619,421,s_oSpriteLibrary.getSprite('enlight_number25'),s_oStage);
        _aEnlights["oEnlight_35"] = oBmp;
        
        oBmp = new CEnlight(644,395,s_oSpriteLibrary.getSprite('enlight_number30'),s_oStage);
        _aEnlights["oEnlight_36"] = oBmp;
        
        /*********************OTHER ENLIGHTS*****************/
        oBmp = new CEnlight(624,470,s_oSpriteLibrary.getSprite('enlight_col'),s_oStage);
        _aEnlights["oEnlight_col1"] = oBmp;
        
        oBmp = new CEnlight(649,442,s_oSpriteLibrary.getSprite('enlight_col'),s_oStage);
        _aEnlights["oEnlight_col2"] = oBmp;
        
        oBmp = new CEnlight(672,415,s_oSpriteLibrary.getSprite('enlight_col'),s_oStage);
        _aEnlights["oEnlight_col3"] = oBmp;
        
        oBmp = new CEnlight(280,268,s_oSpriteLibrary.getSprite('enlight_first_twelve'),s_oStage);
        _aEnlights["oEnlight_first12"] = oBmp;
        
        oBmp = new CEnlight(377,340,s_oSpriteLibrary.getSprite('enlight_second_twelve'),s_oStage);
        _aEnlights["oEnlight_second12"] = oBmp;
        
        oBmp = new CEnlight(477,416,s_oSpriteLibrary.getSprite('enlight_third_twelve'),s_oStage);
        _aEnlights["oEnlight_third12"] = oBmp;
        
        oBmp = new CEnlight(241,305,s_oSpriteLibrary.getSprite('enlight_first18'),s_oStage);
        _aEnlights["oEnlight_first18"] = oBmp;
        
        oBmp = new CEnlight(288,343,s_oSpriteLibrary.getSprite('enlight_first18'),s_oStage);
        _aEnlights["oEnlight_even"] = oBmp;
        
        oBmp = new CEnlight(338,380,s_oSpriteLibrary.getSprite('enlight_black'),s_oStage);
        _aEnlights["oEnlight_black"] = oBmp;
        
        oBmp = new CEnlight(389,419,s_oSpriteLibrary.getSprite('enlight_red'),s_oStage);
        _aEnlights["oEnlight_red"] = oBmp;
        
        oBmp = new CEnlight(439,456,s_oSpriteLibrary.getSprite('enlight_odd'),s_oStage);
        _aEnlights["oEnlight_odd"] = oBmp;
        
        oBmp = new CEnlight(492,498,s_oSpriteLibrary.getSprite('enlight_second18'),s_oStage);
        _aEnlights["oEnlight_second18"] = oBmp;
    };
    
    this._setState = function(iState){
        _iState=iState;

        switch(iState){
            case STATE_GAME_WAITING_FOR_BET:{
                _oInterface.enableBetFiches();
                
		_oBlock.visible= false;
                break;
            }
        }
    };
    
    this._resetTable = function(){
        _iTimeElaps = 0;
        _iBetMult=37;
        _aBetMultHistory=new Array();
        _aBetWinHistory = new Array();
        _aNumFicheHistory = new Array();

        if(_oPlaceHolder !== null){
            s_oStage.removeChild(_oPlaceHolder);
            _oPlaceHolder = null;
        }

        _oMySeat.reset();
        _oNeighborsPanel.reset();

        if (_oMySeat.getCredit() < 0.1) {
            _iState = -1;
            _oBlock.visible= false;
            _oGameOverPanel.show();
        }else{
            _oInterface.enableRebet();
            this._setState(STATE_GAME_WAITING_FOR_BET);
        }
        
        _iHandCont++;
        if(_iHandCont === NUM_HAND_FOR_ADS){
            _iHandCont = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
    };
    
    this._startRouletteAnim = function(result){
        _oInterface.disableBetFiches();

        _iNumberExtracted = result;

        _aNumExtractedHistory.push(_iNumberExtracted);

        _iTimeElaps = 0;
        _iFactor = 0;
        _iFrameToStop = s_oGameSettings.getFrameForNumber(_iNumberExtracted);
    };
    
    this._startWheelTopAnim = function(){
        _oWheelTopAnim.playToFrame(_iFrameToStop);
        
    };
    
    this._startBallSpinAnim = function(){
        var iRand = Math.floor(Math.random() * 3);
        
        _oWheelAnim.startSpin(iRand,s_oGameSettings.getFrameForBallSpin(iRand,_iNumberExtracted));
    };
    
    this._rouletteAnimEnded = function(){
        _iTimeElaps = 0;
        _oWheelTopAnim.showBall();
        this._setState(STATE_GAME_SHOW_WINNER);
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oWheelSfx.stop();
        }

        var aNumbersBetted=_oMySeat.getNumbersBetted();
        var oWins=aNumbersBetted[_iNumberExtracted];
        var iWin=roundDecimal(oWins.win,2);
        _aFichesToMove = new Array();

        for(var j=0;j<aNumbersBetted.length;j++){
                var oRes=aNumbersBetted[j];
                if(oRes.win>0){
                    for(var k=0;k<oRes.mc.length;k++){
                        _aFichesToMove.push(oRes.mc[k]);
                        var oEndPos = s_oGameSettings.getAttachOffset("oDealerWin");
                        oRes.mc[k].setEndPoint(oEndPos.x,oEndPos.y);
                    }
                }
        }

        if(oWins.mc){
            for(var i=0;i<oWins.mc.length;i++){
                var oEndPos = s_oGameSettings.getAttachOffset("oReceiveWin");
                oWins.mc[i].setEndPoint(oEndPos.x,oEndPos.y);
            }

            _oInterface.showWin(iWin);
        }else{
            _oInterface.showLose();
        }
        _oInterface.refreshNumExtracted(_aNumExtractedHistory);

        //ATTACH PLACEHOLDER THAT SHOW THE NUMBER EXTRACTED
        _oPlaceHolder = createBitmap(s_oSpriteLibrary.getSprite('placeholder'));
        if(_iNumberExtracted === 0){
                _oPlaceHolder.x = _aEnlights["oEnlight_"+_iNumberExtracted].getX() +27;
                _oPlaceHolder.y = _aEnlights["oEnlight_"+_iNumberExtracted].getY() + 22;
        }else{
                _oPlaceHolder.x = _aEnlights["oEnlight_"+_iNumberExtracted].getX();
                _oPlaceHolder.y = _aEnlights["oEnlight_"+_iNumberExtracted].getY();
        }
        
        _oPlaceHolder.regX = 6;
        _oPlaceHolder.regY = 20;
        s_oStage.addChild(_oPlaceHolder);

        _oMySeat.showWin(iWin);
        if(iWin > 0){
            _iCasinoCash -= iWin;
        }else{
            _iCasinoCash += _oMySeat.getCurBet();
        }
                
	
        $(s_oMain).trigger("save_score",_oMySeat.getCredit());

        _oInterface.refreshMoney(_oMySeat.getCredit());
    };
    
    this.showMsgBox = function(szText){
        _oMsgBox.show(szText);
    };
    
    this.onRecharge = function() {
        _oMySeat.recharge(TOTAL_MONEY);
        _oInterface.refreshMoney(_oMySeat.getCredit());

        this._setState(STATE_GAME_WAITING_FOR_BET);
        
        _oGameOverPanel.hide();
        
        $(s_oMain).trigger("recharge");
    };
    
    this.onSpin = function(){
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/roulettePlay",
			dataType: "json",
			data: {data:JSON.stringify(_oMySeat.getNumbersBettedCasino())},
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			var result = response.result;
			if(_oNeighborsPanel.isVisible()){
                _oNeighborsPanel.onExit();
			}
		
			if (_oMySeat.getCurBet() === 0) {
					return;
			}
			
			if(_oMySeat.getCurBet() < MIN_BET){
				_oMsgBox.show(TEXT_ERROR_MIN_BET);
				_oInterface.enableBetFiches();
				_oInterface.enableSpin(true);
				return;
			}

			if(_oBlock.visible){
					return;
			}

			_oBlock.visible= true;

			_oWheelTopAnim.hideBall();
			_oNeighborsPanel.hide();
			_oFinalBet.hide();
			_oInterface.enableSpin(false);
			_oInterface.displayAction(TEXT_SPINNING);

			self._startRouletteAnim(result);
			self._startWheelTopAnim();
			self._startBallSpinAnim();

			self._setState(STATE_GAME_SPINNING);
			
			if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
					_oWheelSfx = createjs.Sound.play("wheel_sound");
			}
		});
    };
    
    this._onSitDown = function(){
        this._setState(STATE_GAME_WAITING_FOR_BET);
        _oMySeat.setInfo(TOTAL_MONEY, _oAttachFiches);
        _oInterface.refreshMoney(TOTAL_MONEY);
    };
    
    this._onShowBetOnTable = function(oParams,bRebet){
        var szBut = oParams.button;
        var aNumbers = oParams.numbers;
        _iBetMult -= oParams.bet_mult;
        _aBetMultHistory.push(oParams.bet_mult);
        

        var iBetWin = oParams.bet_win;
        var iNumFiches = oParams.num_fiches;
        var iIndexFicheSelected;
        var iFicheValue;
        
        if(!bRebet){
            iIndexFicheSelected = _oInterface.getCurFicheSelected();

            if(_aBetWinHistory.length === 0){
                _aRebetHistory = new Array();
                _oInterface.disableRebet();
            }
            _aRebetHistory.push({button:oParams.button,numbers:oParams.numbers,bet_mult:oParams.bet_mult,bet_win:oParams.bet_win,
                                                            num_fiches:oParams.num_fiches,neighbors:false,value:iIndexFicheSelected});
        }else{
            iIndexFicheSelected = oParams.value;
        }
        
        iFicheValue=s_oGameSettings.getFicheValues(iIndexFicheSelected);
        _aBetWinHistory.push(iBetWin);
        _aNumFicheHistory.push(iNumFiches);
        
        
        var iCurBet=_oMySeat.getCurBet();
        if( (_oMySeat.getCredit() - (iFicheValue * iNumFiches)) < 0){
            //SHOW MSG BOX
            _oMsgBox.show(TEXT_ERROR_NO_MONEY_MSG);
            _oNeighborsPanel.reset();
            return;
        }
        if( (iCurBet + (iFicheValue * iNumFiches)) > MAX_BET ){
            _oMsgBox.show(TEXT_ERROR_MAX_BET_REACHED);
            _oNeighborsPanel.reset();
            return;
        }

        switch(szBut){
                case "oBetVoisinsZero":{
                        _oMySeat.createPileForVoisinZero(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,iNumFiches);
                        break;
                }
                case "oBetTier":{
                        _oMySeat.createPileForTier(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,iNumFiches);
                        break;
                }
                case "oBetOrphelins":{
                        _oMySeat.createPileForOrphelins(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,iNumFiches);
                        break;
                }
                case "oBetFinalsBet":{
                        _oMySeat.createPileForMultipleNumbers(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,iNumFiches);
                        break;
                }
                default:{
                        _oMySeat.addFicheOnTable(iFicheValue,iIndexFicheSelected,aNumbers,iBetWin,szBut);
                }
        }
        _oInterface.refreshMoney(_oMySeat.getCredit());
        _oInterface.enableSpin(true);
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.play("chip");
        }
        
    };
    
    this._onShowBetOnTableFromNeighbors = function(oParams,bRebet){
        var aNumbers = oParams.numbers;
        _iBetMult -= oParams.bet_mult;
        _aBetMultHistory.push(oParams.bet_mult);

        var iBetWin = oParams.bet_win;
        var iNumFiches = oParams.num_fiches;
        if(!bRebet){
            if(_aBetWinHistory.length === 0){
                _aRebetHistory = new Array();
                _oInterface.disableRebet();
            }
            _aRebetHistory.push({button:oParams.button,numbers:oParams.numbers,bet_mult:oParams.bet_mult,bet_win:oParams.bet_win,
                                        num_fiches:oParams.num_fiches,value:_oInterface.getCurFicheSelected(),num_clicked:oParams.num_clicked,neighbors:true});
        }

        _aBetWinHistory.push(iBetWin);
        _aNumFicheHistory.push(iNumFiches);

        var iFicheValue=s_oGameSettings.getFicheValues(oParams.value);

        //var iCurBet=_oMySeat.getCurBet();

        if( (iFicheValue * iNumFiches)>_oMySeat.getCredit() ){
            //SHOW MSG BOX
            _oMsgBox.show(TEXT_ERROR_NO_MONEY_MSG);
            _oNeighborsPanel.reset();
            return;
        }
/*
        if( (iCurBet + (iFicheValue * iNumFiches)) > MAX_BET ){
            _oMsgBox.show(TEXT_ERROR_MAX_BET_REACHED);
            _oNeighborsPanel.reset();
            return;
        }*/

        _oMySeat.createPileForMultipleNumbers(iFicheValue,oParams.value,aNumbers,iBetWin,iNumFiches);
        
        _oInterface.refreshMoney(_oMySeat.getCredit());
        _oInterface.enableSpin(true);
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.play("chip");
        }
    };
    
    this._onShowEnlight = function(oParams){
        var aBets = oParams.numbers;
        
        for(var i=0;i<aBets.length;i++){
            _aEnlights["oEnlight_"+aBets[i]].show();
        }

        var szEnlight=oParams.enlight;
        if(szEnlight){
            _aEnlights["oEnlight_"+szEnlight].show();
        }
    };
    
    this._onHideEnlight = function(oParams){
        var aBets=oParams.numbers;
        for(var i=0;i<aBets.length;i++){
                _aEnlights["oEnlight_"+aBets[i]].hide();
        }

        var szEnlight=oParams.enlight;
        if(szEnlight){
            _aEnlights["oEnlight_"+szEnlight].hide();
        }
    };
    
    this.onClearLastBet = function(){
        if(_aBetMultHistory.length>0){
                var iBetMultToRemove = _aBetMultHistory.pop();
                _iBetMult += iBetMultToRemove;
        }
		
        if(_aBetMultHistory.length === 0){
                _oInterface.enableSpin(false);
        }
		
        _oMySeat.clearLastBet(_aBetWinHistory.pop(),_aNumFicheHistory.pop());
        _oInterface.refreshMoney(_oMySeat.getCredit());
        _oNeighborsPanel.clearLastBet();
        if(_aRebetHistory.length > 0){
            _aRebetHistory.pop();
        }  
    };
    
    this.onClearAllBets = function(){
        _oMySeat.clearAllBets();
        _oInterface.refreshMoney(_oMySeat.getCredit());
        _oInterface.enableSpin(false);
        _oNeighborsPanel.reset();
        _aRebetHistory = new Array();
        _iBetMult=37;
    };
    
    this.onRebet = function(){
        for(var i=0;i<_aRebetHistory.length;i++){
            if(_aRebetHistory[i].neighbors === true){
                //this._onShowBetOnTableFromNeighbors(_aRebetHistory[i],true);
                _oNeighborsPanel.rebet(_aRebetHistory[i].num_clicked);
            }else{
                this._onShowBetOnTable(_aRebetHistory[i],true);
            }
            
        }
    };
    
    this.onFinalBetShown = function(){
        if(_oFinalBet.isVisible()){
            _oFinalBet.hide();
        }else{
            _oFinalBet.show();	
        }
    };
    
    this.onOpenNeighbors = function(){
        _oFinalBet.hide();
        _oNeighborsPanel.showPanel(_oInterface.getCurFicheSelected(),_oMySeat.getCredit());
    };
   
    this.onExit = function(){
        this.unload();
        s_oMain.gotoMenu();
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("share_event",_oMySeat.getCredit());
    };
    
    this._updateWaitingBet = function(){
        if(_oNeighborsPanel.isVisible()){
            return;
        }

        if(TIME_WAITING_BET === 0){
            _oInterface.displayAction(TEXT_MIN_BET+": "+MIN_BET+"\n"+TEXT_MAX_BET+": "+MAX_BET,
                                                                                TEXT_DISPLAY_MSG_WAITING_BET);
        }else{
            _iTimeElaps += s_iTimeElaps;
            if(_iTimeElaps > TIME_WAITING_BET){
                    _iTimeElaps = 0;
                    this.onSpin();
            }else{
                    var iCountDown=Math.floor((TIME_WAITING_BET - _iTimeElaps)/1000);

                    _oInterface.displayAction(TEXT_MIN_BET+": "+MIN_BET+"\n"+TEXT_MAX_BET+": "+MAX_BET,
                                                                                    TEXT_DISPLAY_MSG_WAITING_BET+" "+iCountDown);
            }
        }
        
        
    };
    
    this._updateSpinning = function(){
        _iTimeElaps += s_iTimeElaps;
        
        if (  _oWheelTopAnim.getCurrentFrame() === (NUM_WHEEL_TOP_FRAMES-1)) {
            _oWheelTopAnim.playToFrame(1);
        }else{
            _oWheelTopAnim.nextFrame();
        }
        
        if (_iTimeElaps > TIME_SPINNING) {
            if ( _oWheelTopAnim.getCurrentFrame() === _iFrameToStop) {
                this._rouletteAnimEnded();
            }
        }
    };
    
    this._updateShowWinner = function(){
        _iTimeElaps+=s_iTimeElaps;
        if(_iTimeElaps>TIME_SHOW_WINNER){
            _iTimeElaps=0;
            this._setState(STATE_DISTRIBUTE_FICHES);
        }
    };
    
    this._updateDistributeFiches = function(){
        _iTimeElaps += s_iTimeElaps;
        if(_iTimeElaps > TIME_FICHES_MOV){
            _iTimeElaps = 0;
            createjs.Sound.play("fiche_collect");
            this._resetTable();
        }else{
            var fLerp = easeInOutCubic( _iTimeElaps, 0, 1, TIME_FICHES_MOV);
            for(var i=0;i<_aFichesToMove.length;i++){
                _aFichesToMove[i].updatePos(fLerp);
            }
        }
    };
    
    this.update = function(){
        if(_bUpdate === false){
            return;
        }
        
        switch(_iState){
            case STATE_GAME_WAITING_FOR_BET:{
                    this._updateWaitingBet();
                    break;
            }
            case STATE_GAME_SPINNING:{
                    this._updateSpinning();
                    break;
            }
            case STATE_GAME_SHOW_WINNER:{
                    this._updateShowWinner();
                    break;
            }
            case STATE_DISTRIBUTE_FICHES:{
                    this._updateDistributeFiches();
                    break;
            }
        }
        
        _oWheelAnim.update();
    };
    
    s_oGame = this;
    
    TOTAL_MONEY = oData.money;
    MIN_BET = oData.min_bet;
    MAX_BET = oData.max_bet;
    TIME_WAITING_BET = oData.time_bet;
    TIME_SHOW_WINNER = oData.time_winner;
    WIN_OCCURRENCE = oData.win_occurrence;
    ENABLE_FULLSCREEN = oData.fullscreen;
    NUM_HAND_FOR_ADS = oData.num_hand_before_ads;
    _iCasinoCash = oData.casino_cash;
    
    this._init();
}

var s_oGame;
var s_oTweenController;
var s_oGameSettings;