function CBonusPanel(){
    var _bItemClicked;
    var _iBonusMoney;
    var _iAnimInterval;
    var _iCurFrame;
    var _iNumCycles;
    var _iTotAnimCycle;
    var _iIndexClicked;
    var _aItems;
    var _aItemAnim;
    var _aBonusValue;
    var _aBonusItemSprites;
    var _aBonusItemPrizes;
    var _oBonusItem;
    var _oWinText;
    var _oContainerItemAnim;
    var _oContainer;
    var _oSoundtrackBonus;
    
    this._init = function(){        
        _oContainer = new createjs.Container();
        s_oStage.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bonus_bg'));
        _oContainer.alpha = 0;
        _oContainer.visible= false;
        _oContainer.addChild(oBg);

        _aItems = new Array();
        
        var oSprite = s_oSpriteLibrary.getSprite('bonus_item');
        var aPos = [{x:530,y:496},{x:684,y:496},{x:838,y:496},{x:376,y:496},{x:992,y:496}]
        for(var i=0;i<5;i++){
            var oItem = createBitmap(oSprite);
            oItem.on("click", this._onItemReleased, this,false,i);
            oItem.x = aPos[i].x;
            oItem.y = aPos[i].y;
            oItem.regY = oSprite.height;
            oItem.visible = false;
            _oContainer.addChild(oItem);
            
            _aItems[i] = oItem;
        }
        
        //ATTACH BONUS ANIM SPRITES
        _oContainerItemAnim = new createjs.Container();
        _oContainerItemAnim.visible = false;
        _oContainer.addChild(_oContainerItemAnim);
        
        _aItemAnim = new Array();
        for(var k=0;k<25;k++){
            var oSprite = s_oSpriteLibrary.getSprite('bonus_item_'+k);
            var oAnim = createBitmap(oSprite);
            _oContainerItemAnim.addChild(oAnim);
            if(k > 0){
                oAnim.visible = false;
            }
    
            _aItemAnim.push(oAnim);
        }
        
        _oContainerItemAnim.regY = oSprite.height;
        
        var oSprite = s_oSpriteLibrary.getSprite('star_bonus');
        var oData = {   // image to use
                        framerate: 10,
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: Math.floor(oSprite.width/NUM_PRIZES), height: oSprite.height,regX:Math.floor(oSprite.width/NUM_PRIZES)/2,regY:oSprite.height/2}, 
                        animations: {  star_0: [0],star_1:[1],star_2:[2]}
        };

        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        _oBonusItem = createSprite(oSpriteSheet, "star_0",Math.floor(oSprite.width/NUM_PRIZES)/2,oSprite.height/2,Math.floor(oSprite.width/NUM_PRIZES),oSprite.height);
        _oContainer.addChild(_oBonusItem);

        _aBonusItemSprites = new Array();
        _aBonusItemPrizes = new Array();
        
        _aBonusItemSprites[0] = createSprite(oSpriteSheet, "star_0",Math.floor(oSprite.width/NUM_PRIZES)/2,oSprite.height/2,Math.floor(oSprite.width/NUM_PRIZES),oSprite.height);
        _aBonusItemSprites[0].x = 340;
        _aBonusItemSprites[0].y = CANVAS_HEIGHT - 70;
        _oContainer.addChild(_aBonusItemSprites[0]);
        
        var oText = new createjs.Text("100","34px "+FONT_GAME, "#ffff00");
        oText.textAlign = "left";
        oText.x = _aBonusItemSprites[0].x + (oSprite.width/NUM_PRIZES)/2 + 6;
        oText.y = _aBonusItemSprites[0].y + 12;
        oText.textBaseline = "alphabetic";
        _oContainer.addChild(oText);
        _aBonusItemPrizes.push(oText);
        
        _aBonusItemSprites[1] = createSprite(oSpriteSheet, "star_1",Math.floor(oSprite.width/NUM_PRIZES)/2,oSprite.height/2,Math.floor(oSprite.width/NUM_PRIZES),oSprite.height);
        _aBonusItemSprites[1].x = 640;
        _aBonusItemSprites[1].y = CANVAS_HEIGHT - 70;
        _oContainer.addChild(_aBonusItemSprites[1]);
        
        oText = new createjs.Text("200","34px "+FONT_GAME, "#ffff00");
        oText.textAlign = "left";
        oText.x = _aBonusItemSprites[1].x + + (oSprite.width/NUM_PRIZES)/2 + 6;
        oText.y = _aBonusItemSprites[1].y + 12;
        oText.textBaseline = "alphabetic";
        _oContainer.addChild(oText);
        _aBonusItemPrizes.push(oText);
        
        _aBonusItemSprites[2] = createSprite(oSpriteSheet, "star_2",Math.floor(oSprite.width/NUM_PRIZES)/2,oSprite.height/2,Math.floor(oSprite.width/NUM_PRIZES),oSprite.height);
        _aBonusItemSprites[2].x = 940;
        _aBonusItemSprites[2].y = CANVAS_HEIGHT - 70;
        _oContainer.addChild(_aBonusItemSprites[2]);
        
        oText = new createjs.Text("300","34px "+FONT_GAME, "#ffff00");
        oText.textAlign = "left";
        oText.x = _aBonusItemSprites[2].x + + (oSprite.width/NUM_PRIZES)/2+ 6;
        oText.y = _aBonusItemSprites[2].y + 12;
        oText.textBaseline = "alphabetic";
        _oContainer.addChild(oText);
        _aBonusItemPrizes.push(oText);
        
        _oWinText = new createjs.Text("+ 300$","80px "+FONT_GAME, "#ffff00");
        _oWinText.alpha = 0;
        _oWinText.textAlign = "center";
        _oWinText.shadow = new createjs.Shadow("#000", 2, 2, 2);
        _oWinText.x = CANVAS_WIDTH/2;
        _oWinText.y = 150;
        _oWinText.textBaseline = "alphabetic";
        _oContainer.addChild(_oWinText);
    };
    
    this.unload = function(){
        for(var i=0;i<5;i++){
            _aItems[i].off("click", this._onItemReleased);
        }   
    };
    
    this.show = function(iNumItems,iCurBet){
        _iCurBet = iCurBet;
        _bItemClicked = false;
        _oWinText.alpha = 0;
        _iCurFrame = 0;
        _iNumCycles = 0;
        _iTotAnimCycle = 4;
        
        switch(iNumItems){
            case 3:{
                    _aBonusValue = BONUS_PRIZE[0];
                    break;
            }
            case 4:{
                    _aBonusValue = BONUS_PRIZE[1];
                    break;
            }
            case 5:{
                    _aBonusValue = BONUS_PRIZE[2];
                    break;
            }
            default:{
                    _aBonusValue = BONUS_PRIZE[0];
            }
        }
        
        _aBonusItemPrizes[0].text = "X" + _aBonusValue[0];
        _aBonusItemPrizes[1].text = "X" + _aBonusValue[1];
        _aBonusItemPrizes[2].text = "X" + _aBonusValue[2];
        
        _oBonusItem.x = 118;
        _oBonusItem.y = 308;
        _oBonusItem.rotation = 0;
        _oBonusItem.gotoAndStop("star_0");
        
        for(var i=0;i<iNumItems;i++){
            _aItems[i].visible = true;
        }
        
        _oContainer.visible = true;
        createjs.Tween.get(_oContainer).to({alpha:1}, 1000);  
		
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                s_oSoundTrack.setVolume(0);
                _oSoundtrackBonus = createjs.Sound.play("soundtrack_bonus",{loop:-1});
        }
    };
    
    this._onItemReleased = function(evt,oData){
        if(_bItemClicked){
            return;
        }
        
        _bItemClicked = true;
        _iIndexClicked = oData;

        _aItems[_iIndexClicked].visible = false;
        this.playItemAnim(_iIndexClicked, localStorage.getItem("bonus"));
		
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            createjs.Sound.play("bonus_item_choosen");
        }
    };
    
    this.playItemAnim = function(iIndex,iRandItem){
        _iBonusMoney = _aBonusValue[iRandItem];

        _oBonusItem.gotoAndStop("star_"+iRandItem);
                
        _oContainerItemAnim.x = _aItems[iIndex].x;
        _oContainerItemAnim.y = _aItems[iIndex].y;
        _oContainerItemAnim.visible = true;
        //PLAY BONUS ITEM ANIM
        var oParent = this;
        _iAnimInterval = setInterval(function(){oParent.update();},FPS_TIME);
    };
    
    this.endBonus = function(){
        //SHOW PRIZE WON
        _oWinText.text = "X "+_iBonusMoney;
        createjs.Tween.get(_oWinText).to({alpha:1}, 500);  
        
        setTimeout(function(){_oContainer.alpha = 0;
                                _oContainer.visible= false;
								for(var i=0;i<_aItems.length;i++){
                                    _aItems[i].visible = false;
                                }
                                if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                                    s_oSoundTrack.setVolume(SOUNDTRACK_VOLUME);
                                    _oSoundtrackBonus.stop();
                                }
                                s_oGame.endBonus(_iBonusMoney)},4000);
    };
    
    this.setFrame = function(iPrevFrame,iFrame){
        _aItemAnim[iPrevFrame].visible = false;
        _iCurFrame = iFrame;
        _aItemAnim[_iCurFrame].visible = true;
    };
    
    this.nextFrame = function(){
        _aItemAnim[_iCurFrame].visible = false;
        _iCurFrame++;
        _aItemAnim[_iCurFrame].visible = true;
    };
    
    this.update = function(){
        if(_iCurFrame === 19 && _iNumCycles < _iTotAnimCycle){
            this.setFrame(19,7);
            _iNumCycles++;
        }else{
            this.nextFrame();
            if(_iNumCycles === _iTotAnimCycle){
                if(_iCurFrame === 24){
                   //END WHOLE SYMBOL ANIMATION
                   clearInterval(_iAnimInterval);
                   _aItemAnim[_aItemAnim.length-1].visible = false;
                   this.endBonus();

                   if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                       createjs.Sound.play("reveal_prize");
                   }   
                }
                
            }
            
        }
    };
    
    this._init();
}