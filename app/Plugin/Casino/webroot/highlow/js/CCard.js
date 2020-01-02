function CCard(iX,iY,oParentContainer,szFotogram,iRank){
      
    var _bTurned;
    
    var _szFotogram;
    var _iRank;

    var _oCardSprite;
    var _oContainer;
    var _oParentContainer;   
    var _oParent;
    

    
    this._init = function(iX,iY,oParentContainer,szFotogram,iRank){
        _oParentContainer = oParentContainer;
        _szFotogram = szFotogram;
        _iRank = iRank;
        
        _oContainer = new createjs.Container();
        _oContainer.x = iX;
        _oContainer.y = iY;
        _oParentContainer.addChild(_oContainer);
        
        var oSprite = s_oSpriteLibrary.getSprite('card_spritesheet');
        var oData = {   // image to use
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: CARD_WIDTH, height: CARD_HEIGHT,regX:CARD_WIDTH/2,regY:CARD_HEIGHT/2}, 
                        animations: {  card_1_1: [0],card_1_2:[1],card_1_3:[2],card_1_4:[3],card_1_5:[4],card_1_6:[5],card_1_7:[6],card_1_8:[7],
                                       card_1_9:[8],card_1_10:[9],card_1_J:[10],card_1_Q:[11],card_1_K:[12],
                                       card_2_1: [13],card_2_2:[14],card_2_3:[15],card_2_4:[16],card_2_5:[17],card_2_6:[18],card_2_7:[19],
                                       card_2_8:[20], card_2_9:[21],card_2_10:[22],card_2_J:[23],card_2_Q:[24],card_2_K:[25],
                                       card_3_1: [26],card_3_2:[27],card_3_3:[28],card_3_4:[29],card_3_5:[30],card_3_6:[31],card_3_7:[32],
                                       card_3_8:[33], card_3_9:[34],card_3_10:[35],card_3_J:[36],card_3_Q:[37],card_3_K:[38],
                                       card_4_1: [39],card_4_2:[40],card_4_3:[41],card_4_4:[42],card_4_5:[43],card_4_6:[44],card_4_7:[45],
                                       card_4_8:[46], card_4_9:[47],card_4_10:[48],card_4_J:[49],card_4_Q:[50],card_4_K:[51],back:[52]}
                        
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        _oCardSprite = createSprite(oSpriteSheet,"back",0,0,CARD_WIDTH,CARD_HEIGHT);
        _oCardSprite.stop();
        _oContainer.addChild(_oCardSprite);
        
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oContainer);
        
    };
        
    this.showCard = function(){
        var oParent = this;
        createjs.Tween.get(_oContainer ).to({scaleX:0.1}, TURN_CARD_SPEED/2, createjs.Ease.cubicIn).call(function(){oParent.setValue()}).call(function(){_bTurned=true});
    };

    this.setValue = function(){
        _oCardSprite.gotoAndStop(_szFotogram);

        playSound("card",1,false);
        
        createjs.Tween.get(_oContainer).to({scaleX:1}, TURN_CARD_SPEED/2, createjs.Ease.cubicOut).call(function(){s_oGame.checkWin(_oParent)});
    };
    
    this.hideCard = function(){
        var oParent = this;
        createjs.Tween.get(_oContainer).to({scaleX:0.1}, TURN_CARD_SPEED/2, createjs.Ease.linear).call(function(){oParent.setBack()});
    };
    
    this.setBack = function(){
        _bTurned=false;
        _oCardSprite.gotoAndStop("back");
        var oParent = this;
        createjs.Tween.get(_oContainer).to({scaleX:1}, TURN_CARD_SPEED/2, createjs.Ease.linear).call(function(){oParent.cardHidden()});
    };
    
    this.getRank = function(){
        return _iRank;
    };
    
    _oParent=this;
    this._init(iX,iY,oParentContainer,szFotogram,iRank);
                
}