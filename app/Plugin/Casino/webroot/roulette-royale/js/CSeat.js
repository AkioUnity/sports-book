function CSeat(){  
    var _iCurBet;
    var _iCredit;
    var _aNumberBetted;
	var _aNumberBettedCasino;
    var _aNumbersSelected;
    var _aLastBetWinHistory;
    var _oFicheController;
    
    this._init = function(){
        this.reset();
    };
    
    this.reset = function(){
        _aNumberBetted=new Array();
        _aNumbersSelected = new Array();
		_aNumberBettedCasino=new Array();
        _aLastBetWinHistory = new Array();
        
        this.resetNumberWins();

        if(_oFicheController){
            _oFicheController.reset();
        }
        
        _iCurBet=0;
    };
    
    this.setInfo = function(iCredit,oContainerFiche){
        _iCredit=iCredit;
        _iCurBet=0;

        _oFicheController = new CFichesController(oContainerFiche);
    };
	
    this.resetNumberWins = function(){
	for(var i=0;i<NUMBERS_TO_BET;i++){
            _aNumberBetted[i] = {win:0,mc:null};
        }
        
        _aLastBetWinHistory = new Array();
    };
    
    this.setFicheBetted = function(iFicheValue,aNumbers,iWinForBet,aFichesMc,iNumFiches){

        var aTmpWin = new Array();
        var aTmpMc = new Array();
		
		if (aNumbers.length === 17 && aNumbers.every(function(v,i) { return v === [22, 18, 29, 7, 28, 12, 35, 3, 26, 0, 32, 15, 19, 4, 21, 2, 25][i]})) {
			_aNumberBettedCasino.push({numbers:[aNumbers[9], aNumbers[15], aNumbers[7]],amount:parseFloat(iFicheValue*2).toFixed(2)});
			_aNumberBettedCasino.push({numbers:[aNumbers[13], aNumbers[3]],amount:parseFloat(iFicheValue).toFixed(2)});
			_aNumberBettedCasino.push({numbers:[aNumbers[5], aNumbers[11]],amount:parseFloat(iFicheValue).toFixed(2)});
			_aNumberBettedCasino.push({numbers:[aNumbers[1], aNumbers[14]],amount:parseFloat(iFicheValue).toFixed(2)});
			_aNumberBettedCasino.push({numbers:[aNumbers[12], aNumbers[0]],amount:parseFloat(iFicheValue).toFixed(2)});
			_aNumberBettedCasino.push({numbers:[aNumbers[16], aNumbers[8], aNumbers[4], aNumbers[2]],amount:parseFloat(iFicheValue*2).toFixed(2)});
			_aNumberBettedCasino.push({numbers:[aNumbers[10], aNumbers[6]],amount:parseFloat(iFicheValue).toFixed(2)});
		} else {
			_aNumberBettedCasino.push({numbers:aNumbers,amount:parseFloat(iFicheValue*iNumFiches).toFixed(2)});
		}
        for(var i=0;i<aNumbers.length;i++){
            var iWin = ( parseFloat(_aNumberBetted[aNumbers[i]].win)+(iWinForBet* (iFicheValue*iNumFiches) )).toFixed(1);
			if (aNumbers.length === 17 && aNumbers.every(function(v,i) { return v === [22, 18, 29, 7, 28, 12, 35, 3, 26, 0, 32, 15, 19, 4, 21, 2, 25][i]})) {
				if (i == 9 || i == 15 || i == 7) {
					iWin = ( parseFloat(_aNumberBetted[aNumbers[i]].win)+((iFicheValue*24) )).toFixed(1);
				}
			}
            _aNumberBetted[aNumbers[i]]={win:iWin,mc:aFichesMc};

            aTmpWin.push((iWinForBet* (iFicheValue*iNumFiches) ));
            aTmpMc.push(aFichesMc);
        }
        
        _aLastBetWinHistory.push({win:aTmpWin,mc:aFichesMc});
        
        _aNumbersSelected.push(aNumbers);
        
        var iAmount = (iFicheValue * iNumFiches);
        iAmount = parseFloat(iAmount.toFixed(2));
        _iCurBet+= iAmount;
        _iCurBet = parseFloat(_iCurBet.toFixed(2));
        _iCredit -= iAmount;
        _iCredit = roundDecimal(_iCredit, 1);
    };
    
    this.createPileForVoisinZero = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,iNumFiches){
        var aFichesMc=new Array();
        _oFicheController.createPileForVoisinZero(iIndexFicheSelected,aFichesMc);
        this.setFicheBetted(iFicheValue,aNumbers,iBetMult,aFichesMc,iNumFiches);
    };
		
    this.createPileForTier = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,iNumFiches){
        var aFichesMc=new Array();
        _oFicheController.createPileForTier(iIndexFicheSelected,aFichesMc);
        this.setFicheBetted(iFicheValue,aNumbers,iBetMult,aFichesMc,iNumFiches);
    };
		
    this.createPileForOrphelins = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,iNumFiches){
        var aFichesMc=new Array();
        _oFicheController.createPileForOrphelins(iIndexFicheSelected,aFichesMc);
        
        var aTmpWin = new Array();
		
		_aNumberBettedCasino.push({numbers:[aNumbers[0]],amount:parseFloat(iFicheValue).toFixed(2)});
		_aNumberBettedCasino.push({numbers:[aNumbers[1], aNumbers[2]],amount:parseFloat(iFicheValue).toFixed(2)});
		_aNumberBettedCasino.push({numbers:[aNumbers[3], aNumbers[4]],amount:parseFloat(iFicheValue).toFixed(2)});
		_aNumberBettedCasino.push({numbers:[aNumbers[4], aNumbers[5]],amount:parseFloat(iFicheValue).toFixed(2)});
		_aNumberBettedCasino.push({numbers:[aNumbers[6], aNumbers[7]],amount:parseFloat(iFicheValue).toFixed(2)});
        
        var iWin = ( parseFloat(_aNumberBetted[aNumbers[0]].win)+(36* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[0]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(36* (iFicheValue));

        iWin = ( parseFloat(_aNumberBetted[aNumbers[1]].win)+(18* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[1]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(18* (iFicheValue));
        
        iWin = ( parseFloat(_aNumberBetted[aNumbers[2]].win)+(18* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[2]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(18* (iFicheValue));
        
        iWin = ( parseFloat(_aNumberBetted[aNumbers[3]].win)+(18* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[3]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(18* (iFicheValue));
        
        iWin = ( parseFloat(_aNumberBetted[aNumbers[4]].win)+(36* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[4]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(36* (iFicheValue));
        
        iWin = ( parseFloat(_aNumberBetted[aNumbers[5]].win)+(18* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[5]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(18* (iFicheValue));
        
        iWin = ( parseFloat(_aNumberBetted[aNumbers[6]].win)+(18* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[6]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(18* (iFicheValue));
        
        iWin = ( parseFloat(_aNumberBetted[aNumbers[7]].win)+(18* (iFicheValue) )).toFixed(1);
        _aNumberBetted[aNumbers[7]]={win:iWin,mc:aFichesMc};
        aTmpWin.push(18* (iFicheValue));
        
	_aNumbersSelected.push(aNumbers);
        var iAmount = (iFicheValue * iNumFiches);
        iAmount = parseFloat(iAmount.toFixed(2));
        _iCurBet+= iAmount;
        _iCredit -= iAmount;
        _iCredit = roundDecimal(_iCredit, 1);
        
        _aLastBetWinHistory.push({win:aTmpWin,mc:aFichesMc});
    };
		
    this.createPileForMultipleNumbers = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,iNumFiches){
        var aFichesMc=new Array();
        _oFicheController.createPileForMultipleNumbers(iIndexFicheSelected,aNumbers,aFichesMc);
        this.setFicheBetted(iFicheValue,aNumbers,iBetMult,aFichesMc,iNumFiches);
    };
		
    this.addFicheOnTable = function(iFicheValue,iIndexFicheSelected,aNumbers,iBetMult,szNameAttach){
        var aFichesMc=new Array();
        _oFicheController.setFicheOnTable(iIndexFicheSelected,szNameAttach,aFichesMc);
        this.setFicheBetted(iFicheValue,aNumbers,iBetMult,aFichesMc,1);
    };
    
    this.clearLastBet = function(){
        if(_aNumbersSelected.length === 0){
            return;
        }
        
        var iBet = _oFicheController.clearLastBet();
        _iCredit += iBet;
        _iCredit = roundDecimal(_iCredit, 1);
        _iCurBet -= iBet;

        var aLastNums = _aNumbersSelected.pop();
        var oLastWin = _aLastBetWinHistory.pop();
		_aNumberBettedCasino.pop();
        
        var aLastWin = oLastWin.win;
        for(var i=0;i<aLastNums.length;i++){
            
            if(_aLastBetWinHistory.length > 0){
                var oTmp = _aLastBetWinHistory[_aLastBetWinHistory.length - 1];
                _aNumberBetted[aLastNums[i]] = {win:_aNumberBetted[aLastNums[i]].win - aLastWin[i],mc:oTmp.mc};
            }else{
                _aNumberBetted[aLastNums[i]] = {win:_aNumberBetted[aLastNums[i]].win - aLastWin[i],mc:null};
            }
            
        }
    };
    
    this.clearAllBets = function(){
        this.resetNumberWins();
        _oFicheController.clearAllBets();
        _iCredit += _iCurBet;
        _iCredit = roundDecimal(_iCredit, 1);
        _iCurBet=0;
		_aNumberBettedCasino=new Array();
    };
    
    this.showWin = function(iWin){
        _iCredit += iWin;
        _iCredit = roundDecimal(_iCredit, 1);

    };
    
    this.recharge = function(iMoney) {
        _iCredit = iMoney;
    };
    
    this.getCurBet = function(){
        return _iCurBet;
    };
    
    this.getCredit = function(){
        return _iCredit;
    };
    
    this.getNumbersBetted = function(){
        return _aNumberBetted;
    };
	
	this.getNumbersBettedCasino = function(){
        return _aNumberBettedCasino;
    };
    
    this.getNumberSelected = function(){
        return _aNumbersSelected;
    };
    
    this._init();
}