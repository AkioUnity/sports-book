function CSeat(){ 
    var _iBetTie;
    var _iBetBanker;
    var _iBetPlayer;
    var _iCardDealedToPlayer;
    var _iCredit;
    var _aFichesOnTable;
    var _aPotentialWins;
    var _vAttachPos;
    
    var _oGroup;
    var _oCardOffset;
    
    var _aFichesController;
    var _aCbCompleted;
    var _aCbOwner;
    
    this._init = function(){
        _oGroup = new createjs.Container();
        _oGroup.x = CANVAS_WIDTH/2 - 150;
        _oGroup.y = 230;

        s_oStage.addChild(_oGroup);

        _aFichesController = new Array();
        for(var k=0;k<3;k++){
            _aFichesController[k] = new CFichesController();
        }       
        
        _iCredit = 0;
		
        this.reset();
        
        _oCardOffset = new CVector2();
        _oCardOffset.set(0,0);
        _vAttachPos=new CVector2(_oCardOffset.getX(),_oCardOffset.getY());

        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
    
    this.unload = function(){
        s_oStage.removeChild(_oGroup);
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.reset = function(){
        _iBetTie = 0;
        _iBetBanker = 0;
        _iBetPlayer = 0;
        _iCardDealedToPlayer=0;

        for(var i=0;i<_aFichesController.length;i++){
            _aFichesController[i].reset();
        }

        _aFichesOnTable=new Array();
        
        for(var k=0;k<3;k++){
            _aFichesOnTable[k]=new Array();
        }

    };

    
    this.clearBet = function(){
        _iBetTie = 0;
        _iBetBanker = 0;
        _iBetPlayer = 0;
        
        _aFichesOnTable = new Array();
        for(var k=0;k<3;k++){
            _aFichesController[k].reset();
            _aFichesOnTable[k] = new Array();
        }
    };

    this.setCredit = function(iNewCredit){
        _iCredit = iNewCredit;
    };
    
    this.increaseCredit = function(iCreditToAdd){
        _iCredit += iCreditToAdd;
    };

    
    this.bet = function(iFicheValue,iTypeBet,iFicheIndex){
        var iValue = 0;
        switch(iTypeBet){
            case BET_TIE:{
                    _iBetTie += iFicheValue;
                    iValue = _iBetTie;
                    break;
            }
            case BET_BANKER:{
                    _iBetBanker += iFicheValue;
                    iValue = _iBetBanker;
                    break;
            }
            case BET_PLAYER:{
                    _iBetPlayer += iFicheValue;
                    iValue = _iBetPlayer;
                    break;
            }
        }

        _aFichesController[iTypeBet].createFichesPile(iValue.toFixed(1),POS_BET[iTypeBet].x,POS_BET[iTypeBet].y);
    };
    
    this.calculatePotentialWins = function(){
        _aPotentialWins = new Array();
        
        _aPotentialWins[BET_TIE] = _iBetTie * MULTIPLIERS[BET_TIE];
        _aPotentialWins[BET_BANKER] = _iBetBanker * MULTIPLIERS[BET_BANKER];
        _aPotentialWins[BET_PLAYER] = _iBetPlayer * MULTIPLIERS[BET_PLAYER];
        
        _aFichesController[0].setPrevValue(_iBetTie);
        _aFichesController[1].setPrevValue(_iBetBanker);
        _aFichesController[2].setPrevValue(_iBetPlayer);
    };
    
    this.decreaseCredit = function(iCreditToSubtract){
        _iCredit -= iCreditToSubtract;
    };
    
    this.refreshFiches = function(iFicheValue,iFicheIndex,iXPos,iYPos,iTypeBet){
        _aFichesOnTable[iTypeBet].push({value:iFicheValue,index:iFicheIndex});
        _aFichesController[iTypeBet].refreshFiches(_aFichesOnTable[iTypeBet],iXPos,iYPos);
    };
    
    this.initMovement = function(iBetIndex,iEndX,iEndY){
        _aFichesController[iBetIndex].initMovement(iEndX,iEndY);
    };

    this.newCardDealed = function(){
        _iCardDealedToPlayer++;
    };
    
    this.rebet = function(){
        var iTotBet = 0;
        for(var i=0;i<_aFichesController.length;i++){
            var iValue = parseFloat(_aFichesController[i].getPrevBet().toFixed(2));
            if(iValue > 0){
                iTotBet+= iValue;
                this.decreaseCredit(iValue);
                
                switch(i){
                    case BET_TIE:{
                            _iBetTie += iValue;
                            break;
                    }
                    case BET_BANKER:{
                            _iBetBanker += iValue;
                            break;
                    }
                    case BET_PLAYER:{
                            _iBetPlayer += iValue;
                            break;
                    }
                }

                _aFichesController[i].createFichesPile(iValue,POS_BET[i].x,POS_BET[i].y);
            }
            
        }
        
        return iTotBet;
    };
     
    this.checkIfRebetIsPossible = function(){
        var iTotBet = 0;
        for(var i=0;i<_aFichesController.length;i++){
            var iValue = parseFloat(_aFichesController[i].getPrevBet().toFixed(2));
            iTotBet+= iValue;
        }
        
        if(iTotBet > _iCredit){
            return false;
        }else{
            return true;
        }
    };

    this.updateFichesController = function(iTime){
        for(var i=0;i<_aFichesController.length;i++){
            _aFichesController[i].update(iTime);
        }       
    };
    
    this.getAttachCardOffset = function(){
        _vAttachPos.set(_oGroup.x+_oCardOffset.getX()+((CARD_WIDTH/2)*_iCardDealedToPlayer),
                                                                _oGroup.y+_oCardOffset.getY());
                
        return _vAttachPos;
    };
    
    this.getTotBet = function(){
        return _iBetTie+_iBetBanker+_iBetPlayer;
    };
    
    this.getBetArray = function(){
        return [_iBetTie,_iBetBanker,_iBetPlayer];
    };
    
    this.getCredit = function(){
        return _iCredit;
    };

    this.getCardOffset = function(){
        return _oCardOffset;
    };

    this.getPotentialWin = function(iIndex){
        return _aPotentialWins[iIndex];
    };
    
    this.getStartingBet = function(){
        var iValue = 0;
        for(var i=0;i<_aFichesController.length;i++){
            iValue += _aFichesController[i].getValue();
        }
        
        return iValue;
    };
    
    this._init();
}