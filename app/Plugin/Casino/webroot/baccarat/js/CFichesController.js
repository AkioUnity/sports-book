function CFichesController(){
    var _bFichesUpdate;
    var _iTimeElaps;
    var _iValue;
    var _iPrevValue;
    var _pFicheStartPos;
    var _pStartingPoint;
    var _pEndingPoint;

    var _oFichesAttach;
    var _oTextAttach;
    var _oBetAmountText;
    var _oBetAmountBackText;
    
    var _aCbCompleted;
    var _aCbOwner;
    
    this._init= function(){
        _oFichesAttach = new createjs.Container();
        s_oStage.addChild(_oFichesAttach);

	_pFicheStartPos=new CVector2();
        _pFicheStartPos.set(_oFichesAttach.x,_oFichesAttach.y);
        
        _oTextAttach  = new createjs.Container();
        s_oStage.addChild(_oTextAttach);
        
        _oBetAmountBackText =  new createjs.Text("","28px "+FONT_GAME_1, "#000");
        _oBetAmountBackText.textAlign = "left";
        _oTextAttach.addChild(_oBetAmountBackText);
        
        _oBetAmountText =  new createjs.Text("","28px "+FONT_GAME_1, "#fff");
        _oBetAmountText.textAlign = "left";
        _oTextAttach.addChild(_oBetAmountText);

        _iTimeElaps=0;
        _iValue = _iPrevValue = 0;
        _bFichesUpdate = false;

        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.reset = function(){
        _bFichesUpdate=false;

        _iValue=0;

        _oFichesAttach.removeAllChildren();

        _oFichesAttach.x=_pFicheStartPos.getX();
        _oFichesAttach.y=_pFicheStartPos.getY();
        
        _oBetAmountBackText.text = "";
        _oBetAmountText.text = "";
    };
    
    this.setPrevValue = function(iValue){
        _iPrevValue = iValue;
    };
    
    this.refreshFiches = function(aFiches,iXPos,iYPos){
        aFiches = aFiches.sortOn('value','index');

        var iXOffset=iXPos;
        var iYOffset=iYPos;

        _iValue = 0;

        var iCont=0;
        for(var i=0;i<aFiches.length;i++){
                var oNewFiche = createBitmap(s_oSpriteLibrary.getSprite("fiche_"+aFiches[i].index));
                oNewFiche.scaleX=0.7;
                oNewFiche.scaleY=0.7;

                _oFichesAttach.addChild(oNewFiche);

                oNewFiche.x = iXOffset;
                oNewFiche.y = iYOffset;
                
                iYOffset -= 5;
                iCont++;
                if(iCont>9 ){
                    iCont=0;
                    iXOffset += FICHE_WIDTH;
                    iYOffset=iYPos;	
                }

                _iValue+=aFiches[i].value;
        }
        
        playSound("chip", 1, 0);
        
        
        _oBetAmountText.x = iXPos;
        _oBetAmountText.y = iYPos + 30;
        _oBetAmountText.text = _iValue.toFixed(2) + TEXT_CURRENCY;
        _oBetAmountBackText.x = iXPos + 2;
        _oBetAmountBackText.y = iYPos + 32;
        _oBetAmountBackText.text = _iValue.toFixed(2) + TEXT_CURRENCY;
    };
		
    this.createFichesPile = function(iAmount,iX,iY){
        this.reset();
        var aFichesValue = s_oGameSettings.getFichesValues();
        var aFichesPile = new Array();

        do{
            var iMinValue=aFichesValue[aFichesValue.length-1];
            var iCont=aFichesValue.length-1;
            while(iMinValue>iAmount){
                    iCont--;
                    iMinValue=aFichesValue[iCont];
            }

             var iNumFiches=Math.floor(iAmount/iMinValue);
            for(var i=0;i<iNumFiches;i++){
                    aFichesPile.push({value:iMinValue,index:s_oGameSettings.getIndexForFiches(iMinValue)});
            }
            
            if(Math.floor(iAmount/iMinValue) === (iAmount/iMinValue)){
                var iRestAmount = 0;
            }else{
                var iRestAmount=iAmount%iMinValue;
            }

            iAmount=iRestAmount.toFixed(2);
        }while(iRestAmount>0);			

        this.refreshFiches(aFichesPile,iX,iY);
    };
	
    this.initMovement = function(iXEnd,iYEnd){
        _iPrevValue = _iValue;
        _pStartingPoint=new CVector2(_oFichesAttach.x,_oFichesAttach.y);
        _pEndingPoint=new CVector2(iXEnd,iYEnd);
        
        _oBetAmountText.text = "";
        _oBetAmountBackText.text = "";
        
        _bFichesUpdate = true;
    };
		
    this.getValue = function(){
        return _iValue;
    };
    
    this.getPrevBet = function(){
        return _iPrevValue;
    };
	
    this.update = function(iTime){
        if(!_bFichesUpdate){
                return;
        }

        _iTimeElaps+=iTime;
        if(_iTimeElaps>TIME_FICHES_MOV){
            _iTimeElaps=0;
            _bFichesUpdate=false;
            
        }else{

            var fLerp = easeInOutCubic( _iTimeElaps, 0, 1, TIME_FICHES_MOV);
            var oPoint = new CVector2();
            var oPoint = tweenVectors(_pStartingPoint, _pEndingPoint, fLerp,oPoint);

            _oFichesAttach.x=oPoint.getX();
            _oFichesAttach.y=oPoint.getY();
        }
    };
    
    this._init();
    
}