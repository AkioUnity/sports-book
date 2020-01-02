function CFichesController(iX, iY, oParentContainer){
	
    var _bSplitActive;
    var _bWinningHand;
    var _bFichesRemoving;
    var _iTimeElaps;
    var _iValue;
    var _pStartingPoint;
    var _pEndingPoint;

    var _oFichesAttach;
    var _oBetNumBack;
    var _oBetNum;

    
    var _aCbCompleted;
    var _aCbOwner;
    
    this._init= function(iX, iY, oParentContainer){
        _oFichesAttach = new createjs.Container();
        _oFichesAttach.x = iX;
        _oFichesAttach.y = iY;
        oParentContainer.addChild(_oFichesAttach);

        _iTimeElaps=0;
        _iValue=0;
        _bSplitActive=false;
        _bWinningHand=false;

        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this.reset = function(){
        _bSplitActive=false;
        _bFichesRemoving=false;
        _iValue=0;

        _oFichesAttach.removeAllChildren();
    };
		
    this.refreshFiches = function(aFiches,iXPos,iYPos){
        aFiches = aFiches.sortOn('value','index');

        var iXOffset=iXPos;
        var iYOffset=iYPos;

        _iValue=0;
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
                    iXOffset+=FICHE_WIDTH;
                    iYOffset=iYPos;	
                }

                _iValue+=aFiches[i].value;
        }
        playSound("chip",1,false);
        
    };
    
    this._createFichesAmountText = function(iX, iY, iAmount){
        _oBetNumBack = new createjs.Text(TEXT_CURRENCY + " " + iAmount.toFixed(2),"24px "+PRIMARY_FONT, "#000000");
        _oBetNumBack.x = iX + 3;
        _oBetNumBack.y = iY + 3;
        _oBetNumBack.textAlign = "center";
        _oBetNumBack.textBaseline = "alphabetic";
        _oBetNumBack.lineWidth = 200;
        _oFichesAttach.addChild(_oBetNumBack);
        
        _oBetNum = new createjs.Text(TEXT_CURRENCY + " " + iAmount.toFixed(2),"24px "+PRIMARY_FONT, "#ffffff");
        _oBetNum.x = iX;
        _oBetNum.y = iY;
        _oBetNum.textAlign = "center";
        _oBetNum.textBaseline = "alphabetic";
        _oBetNum.lineWidth = 200;
        _oFichesAttach.addChild(_oBetNum);
    };
		
    this.createFichesPile = function(iAmount){
        _oFichesAttach.removeAllChildren();
        
        this._createFichesAmountText(10,60, iAmount);
        
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
            var iRestAmount=iAmount%iMinValue;
            iAmount=iRestAmount;
        }while(iRestAmount>0);			

        this.refreshFiches(aFichesPile,0,0);
    };
		
    this.initMovement = function(iXEnd,iYEnd,bWin){
        _bWinningHand=bWin;
        _pStartingPoint=new CVector2(_oFichesAttach.x,_oFichesAttach.y);
        _pEndingPoint=new CVector2(iXEnd,iYEnd);
    };
		
    this.getValue = function(){
        return _iValue;        
    };
	
    this.update = function(iTime){
        if(_bFichesRemoving){
                return;
        }

        _iTimeElaps+=iTime;
        if(_iTimeElaps>TIME_FICHES_MOV){
                _iTimeElaps=0;
                _bFichesRemoving=true;
                if(_aCbCompleted[FICHES_END_MOV]){
                    _aCbCompleted[FICHES_END_MOV].call(_aCbOwner[FICHES_END_MOV],_bWinningHand,_iValue);
                }

        }else{
                var fLerp = easeInOutCubic( _iTimeElaps, 0, 1, TIME_FICHES_MOV);
                var oPoint = new CVector2();
                
                var oPoint = tweenVectors(_pStartingPoint, _pEndingPoint, fLerp,oPoint);
                _oFichesAttach.x=oPoint.getX();
                _oFichesAttach.y=oPoint.getY();
        }
    };
    
    this._init(iX, iY, oParentContainer);
    
}