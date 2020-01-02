function CGame(oData){
    var _iSize;
    var _iPxlImage;
    var _iGridSize;
    var _iGridCellSize;
    var _iGridCellCenter;
    var _iTolerance;
    var _iTotalWin;    
    var _iPosWin;
    var _iContOver;
    var _iCurTouchX;
    var _iCurTouchY;    
    var _iContTouchMS;
    var _iCurBet;
    var _iCurCash;
    var _iAdCounter;

    var _bTouchActive;
    var _bScratchAudio=false;
    var _bStartPlay;

    var _oInterface;
    var _oEndPanel=null;
    var _oHelpPanel;      
    var _oSilver;
    var _oScratchMask;
    var _oParent;
    var _oTimeOutEndScratch;

    var _vFirstVector;
    var _vLastVector;
    
    var _aGridMatrix;
    var _aCells;
    var _aRect;
    var _aProbability;
    var _aProbWin;
    var _aCellsCheck;
    var _aRandom;
    var _aTypeWin;          
    var _aWin;  
    var _aResult;
    var _aRow;
    
    var _oFade;
    var _oRowContainer;
    var _oCoverContainer;
    
    this._init = function(){
        _bTouchActive=false;
        _iSize=SIZE*2;
        _iPxlImage=342;
        _iGridSize=36;
        _iGridCellSize=_iPxlImage/_iGridSize;
        _iGridCellCenter=_iGridCellSize/2;
        s_iCurCredit = CREDIT;
        _iCurBet = BET[0];
        _iCurCash = CASH_CREDIT;
        _iAdCounter = 0;
        _bStartPlay = false;
        
        _iTolerance=Math.floor(SCRATCH_RATIO * 1.44); //points to be spotted in a cell
        _iContOver=0;
        _iTotalWin=0;
        _aRect = new Array();
        _aProbability = new Array();
        _aProbWin = new Array();        
        _aTypeWin = new Array();
        
        _aCellsCheck = new Array();
        for(var i=0; i<3; i++){
            _aCellsCheck.push(false);
        }

        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_game'));
        s_oStage.addChild(oBg); 

        _oRowContainer = new createjs.Container();
        s_oStage.addChild(_oRowContainer);

        _oCoverContainer = new createjs.Container();
        s_oStage.addChild(_oCoverContainer);
        
        _aCells = new Array();
        for(var i=0; i<9; i++){
            _aCells[i]=false;
        }

        _aGridMatrix = new Array();
        for(var i=0; i<_iGridSize; i++){ 
            _aGridMatrix[i]= new Array(); 
            for(var j=0; j<_iGridSize; j++){
              _aGridMatrix[i][j]= {centerX:((i+1)*(_iGridCellSize)-_iGridCellCenter), centerY:((j+1)*(_iGridCellSize)-_iGridCellCenter), deleted:false};  
            }
        }            
        
        this._initProbability();       
        
        
        var oSprite = s_oSpriteLibrary.getSprite('silver');
        _oSilver = createBitmap(oSprite);
        _oSilver.x=SCRATCH_X-0.5;
        _oSilver.y=SCRATCH_Y;
        _oCoverContainer.addChild(_oSilver);
        
        _oFade = new createjs.Shape();
        _oFade.graphics.beginFill("rgba(0,0,0,0.6)").drawRect(SCRATCH_X, SCRATCH_Y, SCRATCHCANVAS_DIM,SCRATCHCANVAS_DIM);
        s_oStage.addChild(_oFade);
        
        _oInterface = new CInterface();
        
        //TOUCH EVENTS
        if (s_bMobile && window.navigator.msPointerEnabled) {
            //IE BROWSER
            _iContTouchMS = 0;
            s_oCanvasScratch.addEventListener("MSPointerDown", this.onTouchStartMS, false);
            s_oCanvasScratch.addEventListener("MSPointerMove", this.onTouchMoveMS, false);
            s_oCanvasScratch.addEventListener("MSPointerUp", this.onTouchEndMS, false);
        }else{

            s_oStageScratch.addEventListener( 'stagemousedown', this.onMouseStart, false );
            s_oStageScratch.addEventListener( 'stagemousemove', this.onMouseMove, false );
            s_oStageScratch.addEventListener( 'stagemouseup', this.onMouseEnd, false );
        }
               

        


        _oHelpPanel = CHelpPanel();
    };
    
    this._initProbability = function(){
    };
    
    this._initWinProbability = function(gameResult){
        this._initNumberWinLine(gameResult);
    };
    
    
    this._initNumberWinLine = function(gameResult){    
			
			_aWin = new Array();
			var iNumberOfWinLine;
			_aRandom = new Array();
			var iTotalWin = 0;
			_aRow = new Array();


				_aWin = gameResult[0];
				iNumberOfWinLine = gameResult[1];
				_aTypeWin = gameResult[2];
				_aRandom = gameResult[3];
				_iPosWin = gameResult[4];
				iTotalWin = gameResult[5];
				

                this._initFruits(iNumberOfWinLine);

            
    };
    
    this._initFruits = function(iNumberOfWinLine){
        switch(iNumberOfWinLine){
            case 0:{
                for (var i=0; i<3; i++){
                    _aRow[i] = new CRow(SCRATCH_X,SCRATCH_Y+i*114,9, _oRowContainer);
                }

            }break;
            case 1:{
                _aRow[0] = new CRow(SCRATCH_X,SCRATCH_Y+_aRandom[0]*114,_aTypeWin[_aRandom[0]], _oRowContainer);
                _aRow[1] = new CRow(SCRATCH_X,SCRATCH_Y+_aRandom[1]*114,9, _oRowContainer);
                _aRow[2] = new CRow(SCRATCH_X,SCRATCH_Y+_aRandom[2]*114,9, _oRowContainer);   

            }break;      
            case 2:{
                for(var i=0; i<iNumberOfWinLine; i++){
                    _aRow[i] = new CRow(SCRATCH_X,SCRATCH_Y+_aRandom[i]*114,_aTypeWin[_aRandom[i]], _oRowContainer);
                }
                _aRow[2] = new CRow(SCRATCH_X,SCRATCH_Y+_aRandom[2]*114,9, _oRowContainer);

            }break;
            case 3:{

                for(var i=0; i<iNumberOfWinLine; i++){
                    _aRow[i] = new CRow(SCRATCH_X,SCRATCH_Y+_aRandom[i]*114,_aTypeWin[_aRandom[i]], _oRowContainer);
                }
            }               
        }
    }
    
    this._checkAvailableBet = function(){
        var iIndex = -1;
        if(_iCurBet > s_iCurCredit){
            for(var i=0; i<BET.length; i++){
                if(BET[i] < _iCurBet && BET[i] <= s_iCurCredit){
                    iIndex = i;
                }
            }
            
            if(iIndex < 0){
                this.gameOver();
            } else {
                _iCurBet = BET[iIndex];
                _oInterface.refreshBet(_iCurBet);
                _oInterface.refreshPayout(_iCurBet);
                
            }
            
        }
    };
    
    this.selectBet = function(szType){
        
        var iIndex;
        for(var i=0; i<BET.length; i++){
            if(BET[i] === _iCurBet){
                iIndex = i;
            }
        }
        
        if(szType === "add"){
            if(iIndex !== BET.length-1 && BET[iIndex +1] <= s_iCurCredit){
                iIndex++;
            } else{
                iIndex = 0;
            }
        } else {
            if(iIndex !== 0){
                iIndex--;                
            } else if(iIndex === 0){                
                for(var i=0; i<BET.length; i++){
                    if(BET[i] <= s_iCurCredit){
                        iIndex = i;
                    }else {
                        break;
                    }
                }
            }
        }
        
        _iCurBet = BET[iIndex];
        _oInterface.refreshBet(_iCurBet);
        _oInterface.refreshPayout(_iCurBet);

    };
    
    this.onPlayAgainBut = function(){
        clearInterval(_oTimeOutEndScratch);
        _oParent._resetScratchCard();
    };
    
    this._resetScratchCard = function(){

        _iCurCash -= _iTotalWin;
        
        s_iCurCredit += _iTotalWin;
        _oInterface.refreshCredit(s_iCurCredit);
        
        _iTotalWin = 0;
        _oInterface.refreshTotWin(_iTotalWin);
        
        _oSilver.visible = true;
        _oFade.visible = true;
        
        for(var i=0; i<3; i++){
            _aResult[i].unload();
            _aRow[i].unload();
        };
        
        _aGridMatrix = new Array();
        for(var i=0; i<_iGridSize; i++){ 
            _aGridMatrix[i]= new Array(); 
            for(var j=0; j<_iGridSize; j++){
              _aGridMatrix[i][j]= {centerX:((i+1)*(_iGridCellSize)-_iGridCellCenter), centerY:((j+1)*(_iGridCellSize)-_iGridCellCenter), deleted:false};  
            }
        }
        
        _aCells = new Array();
        for(var i=0; i<9; i++){
            _aCells[i]=false;
        }
        
        _aCellsCheck = new Array();
        for(var i=0; i<3; i++){
            _aCellsCheck.push(false);
        }
        
        _oInterface.enablePlayAgain(false);
        
        _oInterface.enableBuyOptions(true);
        
        _oParent._checkAvailableBet();
        
        $(s_oMain).trigger("save_score",[s_iCurCredit]);
        
    };
    
    this.tryShowAd = function(){
        _iAdCounter++;
        if(_iAdCounter === AD_SHOW_COUNTER){
            _iAdCounter = 0;
            $(s_oMain).trigger("show_interlevel_ad");
        }
    };
    
    this.startPlay = function(){
		var data = {
			"amount": _iCurBet
		};
		request = $.ajax({
			type: "post",
			url: "/eng/casino/games/scratchPlay",
			dataType: "json",
			data: {data:JSON.stringify(data)},
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			alert('Server is not available right now. Please try again later!');
		});
		var self = this;
		request.done(function (response, textStatus, jqXHR){
			var gameResult = response.result;
			
			_bStartPlay = true;
        
			$(s_oMain).trigger("bet_placed",[_iCurBet]);
			
			_oParent.tryShowAd();
			
			_iCurCash += _iCurBet;
			
			_oParent._initWinProbability(gameResult);
			
			s_iCurCredit -= _iCurBet;
			_oInterface.refreshCredit(s_iCurCredit);
			
			_oInterface.enableBuyOptions(false);
			_oFade.visible = false;
			
			var c = document.getElementById("clear-image");
			_oScratchMask = c.getContext("2d");
			_oScratchMask.drawImage(s_oSpriteLibrary.getSprite("silver"),0,0);
			
			$("#clear-image").css("display","block");         
			 _oSilver.visible=false;
			
			_aResult = new Array();
		});     
    };
    
    this._scratch = function() { 
        
        var distance;        
        var temp;
        var shiftCenter;    
        var number;
        
        temp={x: _vLastVector.getX(), y: _vLastVector.getY()};

             
        //Distance to vector
        _vLastVector.subV(_vFirstVector);
        distance = _vLastVector.length();

        //Versor
        _vLastVector.normalize();
        
        //New vector to add
        _vLastVector.scalarProduct(PRECISION);
        
        //Number of points to interpolate
        number=distance/PRECISION;
        
        shiftCenter={x: _vFirstVector.getX()-SIZE, y: _vFirstVector.getY()-SIZE};

       
        for(var i=0; i<number; i++){            
            
            var iXPos = parseInt(shiftCenter.x+i*_vLastVector.getX());
            var iYPos = parseInt(shiftCenter.y+i*_vLastVector.getY());
            _oScratchMask.clearRect(iXPos, iYPos, _iSize, _iSize);
            
            _aRect[i]= new createjs.Rectangle(iXPos, iYPos, _iSize, _iSize);
        }
        
        
        var startX = new Array();
        var endX = new Array();
        var startY = new Array();
        var endY = new Array();
        
        for(var i=0; i<_aRect.length; i++){ 
            
            startX[i]= Math.floor((_aRect[i].x-SIZE)/_iGridCellSize);
            endX[i]= Math.floor((_aRect[i].x + SIZE)/_iGridCellSize);
            startY[i]= Math.floor((_aRect[i].y-SIZE)/_iGridCellSize);
            endY[i]= Math.floor((_aRect[i].y + SIZE)/_iGridCellSize);
            
            if(startX[i]<0){
                startX[i]=0;
            } 
            
            if(startY[i]<0){
                startY[i]=0;
            }
            
            if(endX[i]>_iPxlImage){
                endX[i]=_iPxlImage;
            } 
            
            if(endY[i]>_iPxlImage){
                endY[i]=_iPxlImage;
            }
            
            for(var j=startX[i]; j<=endX[i]; j++){
                for(var k=startY[i]; k<=endY[i]; k++){
                    if(_aRect[i].contains(_aGridMatrix[j][k].centerX, _aGridMatrix[j][k].centerY)){
                        _aGridMatrix[k][j].deleted=true; //index is inverted
                    };
                }
            }
            
            
        }
        
        _aRect=[];

        _vFirstVector.set(temp.x,temp.y);
        
        if(s_bDefaultAndroid){
            $('#clear-image').hide().show(0);
        }
    };

    this._checkGrid = function(){      
        //Indicization is inverted
               
        var first=0;
        
        for(var i=0; i<12; i++){
            for(var j=0; j<12; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    first++;
                }
            }
        }    
        
        if (first>_iTolerance){
            _aCells[0]=true;
        }
        
        var second=0;
        for(var i=0; i<12; i++){
            for(var j=12; j<24; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    second++;
                }
            }
        }    

        if (second>_iTolerance){
            _aCells[1]=true;
        }
        
        var third=0;
        for(var i=0; i<12; i++){
            for(var j=24; j<36; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    third++;
                }
            }
        }    

        if (third>_iTolerance){
            _aCells[2]=true;
        }
        
        var fourth=0;
        for(var i=12; i<24; i++){
            for(var j=0; j<12; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    fourth++;
                }
            }
        }    

        if (fourth>_iTolerance){
            _aCells[3]=true;
        }
        var fifth=0;
        for(var i=12; i<24; i++){
            for(var j=12; j<24; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    fifth++;
                }
            }
        }    

        if (fifth>_iTolerance){
            _aCells[4]=true;
        }
        
        var sixth=0;
        for(var i=12; i<24; i++){
            for(var j=24; j<36; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    sixth++;
                }
            }
        }    

        if (sixth>_iTolerance){
            _aCells[5]=true;
        }
        
        var seventh=0;
        for(var i=24; i<36; i++){
            for(var j=0; j<12; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    seventh++;
                }
            }
        }    

        if (seventh>_iTolerance){
            _aCells[6]=true;
        }
        
        var eighth=0;
        for(var i=24; i<36; i++){
            for(var j=12; j<24; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    eighth++;
                }
            }
        }    

        if (eighth>_iTolerance){
            _aCells[7]=true;
        }
        
        var ninth=0;
        for(var i=24; i<36; i++){
            for(var j=24; j<36; j++){
                if(_aGridMatrix[i][j].deleted===true){
                    ninth++;
                }
            }
        }    

        if (ninth>_iTolerance){
            _aCells[8]=true;
        }
        
    };
    
    this._checkWin = function(){
       
        if(_aCells[0] && _aCells[1] && _aCells[2] && !_aCellsCheck[0]){
            if(_aWin[0]){
                _aCellsCheck[0]=true;
                _aResult[0] = new CShowResult("win",_aTypeWin[0],0,_iCurBet);
                _iTotalWin += _aResult[0].getWin();
            }else {
                _aCellsCheck[0]=true;
                _aResult[0] = new CShowResult("nowin",_aTypeWin[0],0,_iCurBet);
            }
        }
        
        if(_aCells[3] && _aCells[4] && _aCells[5] && !_aCellsCheck[1]){
            if(_aWin[1]){
                _aCellsCheck[1]=true;
                _aResult[1] = new CShowResult("win",_aTypeWin[1],1,_iCurBet);
                _iTotalWin += _aResult[1].getWin();
                
            }else {
                _aCellsCheck[1]=true;
                _aResult[1] = new CShowResult("nowin",_aTypeWin[1],1,_iCurBet);
            }
        }
        
        if(_aCells[6] && _aCells[7] && _aCells[8] && !_aCellsCheck[2]){
            if(_aWin[2]){
                _aCellsCheck[2]=true;
                _aResult[2] = new CShowResult("win",_aTypeWin[2],2,_iCurBet);                
                _iTotalWin += _aResult[2].getWin();
            }else {
                _aCellsCheck[2]=true;
                _aResult[2] = new CShowResult("nowin",_aTypeWin[2],2,_iCurBet);
            }
        }
        
        _oInterface.refreshTotWin(_iTotalWin);
        
        
    };
    
    this._playScratch = function(){
        if(_bScratchAudio){
            _bScratchAudio=false;
            //stopSound(s_oScratchSfx)
            stopSound("scratch");
            
        }else {
            _bScratchAudio=true;
            s_oScratchSfx = playSound("scratch",1,true);
        }
        
    };
    
    this.onMouseStart = function(event) {
	event = event || window.event;
	      
        _vFirstVector = new CVector2(event.stageX,event.stageY);
        
        _oParent._playScratch();
        _bTouchActive=true; 
        
        
    };
    
  
    this.onMouseMove = function(event) {
       
        if(_bTouchActive === false){
			return;
		}
        _vLastVector = new CVector2(event.stageX,event.stageY);        
                
        _oParent._scratch();        
        _oParent._checkGrid();
        _oParent._checkWin();   
 
    };
    
    this.onMouseEnd = function() {
        _bTouchActive=false;
        _oParent._playScratch();
    };

    
    this.onTouchStartMS = function(event) {
	_iContTouchMS++;
        if(_iContTouchMS > 1){
                return;
        }
		
        _iCurTouchX = parseInt(((event.pageX || event.targetTouches[0].pageX) -s_oCanvasLeft )/ s_iScaleFactor)-SCRATCH_X;
        _iCurTouchY = parseInt(((event.pageY || event.targetTouches[0].pageY) -s_oCanvasTop)/ s_iScaleFactor)-SCRATCH_Y;
		
        _vFirstVector = new CVector2(_iCurTouchX,_iCurTouchY);        
                
        _bTouchActive=true;
        _oParent._playScratch();
        
    };
    
    this.onTouchMoveMS = function(event) {
        if (window.navigator.msPointerEnabled && !event.isPrimary){
                return;
        }
        
        event.preventDefault(); 

        _iCurTouchX = parseInt(((event.pageX || event.targetTouches[0].pageX)-s_oCanvasLeft) / s_iScaleFactor)-SCRATCH_X;
        _iCurTouchY = parseInt(((event.pageY || event.targetTouches[0].pageY)-s_oCanvasTop) / s_iScaleFactor)-SCRATCH_Y;

        _vLastVector = new CVector2(_iCurTouchX,_iCurTouchY);


        _oParent._scratch();
        _oParent._checkGrid();
        _oParent._checkWin();
        
    };
    
    this.onTouchEndMS = function(event) {
        _iContTouchMS--;
        if(_iContTouchMS === 0){
                _bTouchActive=false;
        }
        _oParent._playScratch();
    };
    
    this.unload = function(){
        
        this.removeListeners();
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();
        
        s_oStageScratch.removeAllChildren();
        s_oStageScratch.clear();
        $("#clear-image").css("display","none");
        
       
        _oInterface.unload();
        if(_oEndPanel !== null){
            _oEndPanel.unload();
        }

           
    };
    
    this.removeListeners = function(){
        //IE BROWSER
        if (s_bMobile && window.navigator.msPointerEnabled) {
            _iContTouchMS = 0;
            s_oCanvasScratch.removeEventListener("MSPointerDown", this.onTouchStartMS, false);
            s_oCanvasScratch.removeEventListener("MSPointerMove", this.onTouchMoveMS, false);
            s_oCanvasScratch.removeEventListener("MSPointerUp", this.onTouchEndMS, false);
            
        }else{
                       
            s_oStageScratch.removeEventListener( 'stagemousedown', this.onMouseStart, false );
            s_oStageScratch.removeEventListener( 'stagemousemove', this.onMouseMove, false );
            s_oStageScratch.removeEventListener( 'stagemouseup', this.onMouseEnd, false );
        }
    };
    
    
    this.onExit = function(){
        this.unload();
        s_oMain.gotoMenu();

    };
    
    this._onExitHelp = function () {
         
    };
    
    this.checkEndScratch = function(){
        _iContOver++;
        if(_iContOver===3){
            _iContOver = 0;
            //this.gameOver();
            this._endScratch();
        }
    };
    
    this.cover = function(){
        if(!_bStartPlay){
            return;
        }
        
        _oSilver.visible=true;
        s_oStage.update();
        $("#clear-image").css("display","none");
        
    };
    
    this.uncover = function(){
        if(!_bStartPlay){
            return;
        }
        
        _oSilver.visible=false;
        s_oStage.update();
        $("#clear-image").css("display","block");
        
    };
    
    this._endScratch = function(){
        _bStartPlay = false;
        
        s_oStageScratch.removeAllChildren();
        s_oStageScratch.clear();
        $("#clear-image").css("display","none");
        
        _oInterface.enablePlayAgain(true);
        
        _oInterface.refreshTotWin(_iTotalWin);
        
        _oTimeOutEndScratch = setTimeout(function(){_oParent._resetScratchCard();},5000); 
        
    };
    
    this.gameOver = function(){  
        this.removeListeners();

        s_oStageScratch.removeAllChildren();
        s_oStageScratch.clear();
        $("#clear-image").css("display","none");
        
        var bWin;
        if(_aWin[0] || _aWin[1] || _aWin[2]){
            bWin=true;
        }
        
        _oEndPanel = CEndPanel(s_oSpriteLibrary.getSprite('msg_box')); 
        _oEndPanel.show(_iTotalWin,bWin);
    };

    
    this.update = function(){

    };

    s_oGame=this;
    
    PRIZE = oData.prize;
    PRIZE_PROB = oData.prizeprob;    
    
    WIN_OCCURRENCE= oData.win_occurrence;
    MULTIPLE_WIN_PERCENTAGE= oData.multiple_win_percentage;

    SCRATCH_RATIO = oData.scratch_tolerance_per_cell;
    
    BET = oData.bet_to_play;
    CREDIT = oData.player_credit;
    CASH_CREDIT = oData.cash_credit;
    
    AD_SHOW_COUNTER = oData.ad_show_counter;
    
    _oParent = this;
    this._init();
}

var s_oGame;
var s_oScratchSfx;
