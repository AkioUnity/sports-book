function CRow(iPosX, iPosY, index, oParentContainer){

    var _aIcon;
    
    var oObjSpriteSheet;
    
    this._init = function(iPosX,iPosY, index, oParentContainer){
        
        var oSprite = s_oSpriteLibrary.getSprite('fruits');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: 114, height: 114}, 
                        animations: {  symbol0: [0], symbol1:[1], symbol2: [2], symbol3: [3], symbol4: [4],
                            symbol5: [5], symbol6: [6], symbol7: [7], symbol8: [8]}
                   };
        
        oObjSpriteSheet = new createjs.SpriteSheet(oData);
       
        _aIcon = new Array();
       
        this._select(iPosX,iPosY,index);
            
    };
    
    this.unload = function(){
        for (var i=0; i<3; i++){
            oParentContainer.removeChild(_aIcon[i]);
        }       
    };
    
    this._select = function(iPosX,iPosY,index){
        
        if (index<9){ //Winner case
            var tag="symbol"+index;
            for (var i=0; i<3; i++){
                _aIcon[i] = createSprite(oObjSpriteSheet, tag,0,0,114,114);
                _aIcon[i].x += iPosX + i*114;
                _aIcon[i].y = iPosY;
                _aIcon[i].visible=true;
                oParentContainer.addChild(_aIcon[i]);
            }                        
        } else { // No Winner case
            
            var mixedArray = new Array();
            var iTwoSame = Math.random();
            
            if(iTwoSame<0.7){
                
                var iSameType= Math.floor(Math.random()*9);
                var iDifferentType = Math.floor(Math.random()*9);
                
                while(iSameType===iDifferentType){
                    iDifferentType = Math.floor(Math.random()*9);
                }
                
                mixedArray.push(iSameType);
                mixedArray.push(iSameType);
                mixedArray.push(iDifferentType);                               
                
                
            } else {
                for (var i=0; i<9; i++){
                    mixedArray.push(i);
                }
            }
            
            
            
            shuffle(mixedArray);
            
            for (var i=0; i<3; i++){
                var tag = "symbol"+mixedArray[i];
                _aIcon[i] = createSprite(oObjSpriteSheet, tag,0,0,114,114);
                _aIcon[i].x += iPosX + i*114;
                _aIcon[i].y = iPosY;
                _aIcon[i].visible=true;
                oParentContainer.addChild(_aIcon[i]);
            }                        
            
        }

    };

    this._init(iPosX,iPosY,index, oParentContainer);
}
