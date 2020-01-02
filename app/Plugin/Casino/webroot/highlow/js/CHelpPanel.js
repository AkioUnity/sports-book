function CHelpPanel(){
    var _oText1;
    var _oText1Back;
    var _oText2;
    var _oText2Back;   
    var _oText3;
    var _oText3Back;
    var _oText4;
    var _oText4Back;

    var _oHelpBg;
    var _oGroup;
    var _oParent;

    this._init = function(){
        var oParent = this;
        _oHelpBg = createBitmap(s_oSpriteLibrary.getSprite('bg_help'));
  
        var oText1Pos = {x: CANVAS_WIDTH/2, y: (CANVAS_HEIGHT/2)-185};
  
        _oText1Back = new createjs.Text(TEXT_HELP1,"22px "+PRIMARY_FONT, "#000000");
        _oText1Back.x = oText1Pos.x+2;
        _oText1Back.y = oText1Pos.y+2;
        _oText1Back.textAlign = "center";
        _oText1Back.textBaseline = "alphabetic";
        _oText1Back.lineWidth = 600;
  
        _oText1 = new createjs.Text(TEXT_HELP1,"22px "+PRIMARY_FONT, "#ffffff");
        _oText1.x = oText1Pos.x;
        _oText1.y = oText1Pos.y;
        _oText1.textAlign = "center";
        _oText1.textBaseline = "alphabetic";
        _oText1.lineWidth = 600;                
  
        var oText2Pos = {x: CANVAS_WIDTH/2 -340, y: (CANVAS_HEIGHT/2)-110}
  
        _oText2Back = new createjs.Text(TEXT_HELP2," 18px "+PRIMARY_FONT, "#000000");
        _oText2Back.x = oText2Pos.x +2;
        _oText2Back.y = oText2Pos.y +2;
        _oText2Back.textAlign = "left";
        _oText2Back.textBaseline = "alphabetic";
        _oText2Back.lineWidth = 320;
  
        _oText2 = new createjs.Text(TEXT_HELP2," 18px "+PRIMARY_FONT, "#ffffff");
        _oText2.x = oText2Pos.x;
        _oText2.y = oText2Pos.y;
        _oText2.textAlign = "left";
        _oText2.textBaseline = "alphabetic";
        _oText2.lineWidth = 320;
     
        var oText3Pos = {x: CANVAS_WIDTH/2 -50, y: (CANVAS_HEIGHT/2) + 50};
  
        _oText3Back = new createjs.Text(TEXT_HELP3," 18px "+PRIMARY_FONT, "#000000");
        _oText3Back.x = oText3Pos.x +2;
        _oText3Back.y = oText3Pos.y +2;
        _oText3Back.textAlign = "left";
        _oText3Back.textBaseline = "alphabetic";
        _oText3Back.lineWidth = 380;
  
        _oText3 = new createjs.Text(TEXT_HELP3," 18px "+PRIMARY_FONT, "#ffffff");
        _oText3.x = oText3Pos.x;
        _oText3.y = oText3Pos.y;
        _oText3.textAlign = "left";
        _oText3.textBaseline = "alphabetic";
        _oText3.lineWidth = 380;
     
        var oText4Pos = {x: CANVAS_WIDTH/2 -340, y: (CANVAS_HEIGHT/2)+170};
  
        _oText4Back = new createjs.Text(TEXT_HELP4," 18px "+PRIMARY_FONT, "#000000");
        _oText4Back.x = oText4Pos.x +2;
        _oText4Back.y = oText4Pos.y +2;
        _oText4Back.textAlign = "left";
        _oText4Back.textBaseline = "alphabetic";
        _oText4Back.lineWidth = 400;
  
        _oText4 = new createjs.Text(TEXT_HELP4," 18px "+PRIMARY_FONT, "#ffffff");
        _oText4.x = oText4Pos.x;
        _oText4.y = oText4Pos.y;
        _oText4.textAlign = "left";
        _oText4.textBaseline = "alphabetic";
        _oText4.lineWidth = 400;
        
        _oGroup = new createjs.Container();
        _oGroup.addChild(_oHelpBg, _oText1Back,  _oText1, _oText2Back, _oText2, _oText3Back, _oText3, _oText4Back, _oText4);
        _oGroup.alpha=0;
        s_oStage.addChild(_oGroup);

        createjs.Tween.get(_oGroup).to({alpha:1}, 700);        
        
        _oGroup.on("pressup",function(){oParent._onExitHelp();});
        
        
    };

    this.unload = function(){
        s_oStage.removeChild(_oGroup);

        var oParent = this;
        _oGroup.off("pressup",function(){oParent._onExitHelp()});
    };

    this._onExitHelp = function(){
        _oParent.unload();
        s_oGame._onExitHelp();
    };

    _oParent=this;
    this._init();

}
