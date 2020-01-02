function CGameSettings(){
    
    var _aCardDeck;
    var _aShuffledCardDecks;
    var _aCardValue;
    var _aFichesValue;
    
    this._init = function(){
        _aCardValue=new Array();
        _aCardDeck=new Array();
        for(var j=0;j<52;j++){
            _aCardDeck.push(j);
            var iRest=(j+1)%13;
            if(iRest === 0){
                iRest = 13;
            }
            _aCardValue.push(iRest);
        }        
        
        _aFichesValue=new Array();
        for(var i=0; i<FICHES_VALUE.length; i++){
            _aFichesValue[i] = FICHES_VALUE[i];
        }
    };
		
    this.getFichesValues = function(){
            return _aFichesValue;
    };
		
    this.getFichesValueAt = function(iIndex){
            return _aFichesValue[iIndex];
    };
		
    this.getIndexForFiches = function(iValue){
        var iRes=0;
        for(var i=0;i<_aFichesValue.length;i++){
                if(_aFichesValue[i] === iValue){
                        iRes=i;
                }
        }
        return iRes; 
    };
		
    this.generateFichesPile = function(iFichesValue){
        var aFichesPile=new Array();
        var iValueRest;
        var iCont=_aFichesValue.length-1;
        var iCurMaxFicheStake=_aFichesValue[iCont];


        do{
                iValueRest=iFichesValue%iCurMaxFicheStake;
                iValueRest=CMath.roundDecimal(iValueRest,1);

                var iDivision=Math.floor(iFichesValue/iCurMaxFicheStake);

                for(var i=0;i<iDivision;i++){
                        aFichesPile.push(iCurMaxFicheStake);
                }

                iCont--;
                iCurMaxFicheStake=_aFichesValue[iCont];
                iFichesValue=iValueRest;
        }while(iValueRest>0 && iCont>-1);

        return aFichesPile;
    };
		
    this.timeToString = function( iMillisec ){		
        iMillisec = Math.round((iMillisec/1000));

        var iMins = Math.floor(iMillisec/60);
        var iSecs = iMillisec-(iMins*60);

        var szRet = "";

        if ( iMins < 10 ){
                szRet += "0" + iMins + ":";
        }else{
                szRet += iMins + ":";
        }

        if ( iSecs < 10 ){
                szRet += "0" + iSecs;
        }else{
                szRet += iSecs;
        } 

        return szRet;   
    };
		
    this.getShuffledCardDeck = function(){
        var aTmpDeck=new Array();

        for(var i=0;i<_aCardDeck.length;i++){
                aTmpDeck[i]=_aCardDeck[i];
        }

        _aShuffledCardDecks = new Array();
        while (aTmpDeck.length > 0) {
                _aShuffledCardDecks.push(aTmpDeck.splice(Math.round(Math.random() * (aTmpDeck.length - 1)), 1)[0]);
        }

        return _aShuffledCardDecks;	
    };
		
    this.getCardValue = function(iId){
            return _aCardValue[iId];
    };
                
    this._init();
}