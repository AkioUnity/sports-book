function CHelpPanel(){
    var _oText1;
    var _oText1Back;
    var _oMessage1;
    var _oHelpBg;
    var _oGroup;
    var _oListener;

    this._init = function(){
        _oHelpBg = createBitmap(s_oSpriteLibrary.getSprite('bg_help'));
        s_oStage.addChild(_oHelpBg);
        
        _oMessage1=TEXT_HELP1;

        
        _oText1Back = new createjs.Text(_oMessage1," 25px "+PRIMARY_FONT, "#000000");
        _oText1Back.textAlign = "center";
        _oText1Back.x = 682;
        _oText1Back.y = 252;
        _oText1Back.lineWidth = 350;
		
	_oText1 = new createjs.Text(_oMessage1," 25px "+PRIMARY_FONT, "#ffffff");
        _oText1.textAlign = "center";
        _oText1.x = 684;
        _oText1.y = 250;
        _oText1.lineWidth = 350;

        _oGroup = new createjs.Container();
        _oGroup.addChild(_oHelpBg,_oText1Back,_oText1);
        s_oStage.addChild(_oGroup);

        var oParent = this;
        _oListener = _oGroup.on("pressup",function(){oParent._onExitHelp()});
        
        
    };

    this.unload = function(){
        s_oStage.removeChild(_oGroup);

        var oParent = this;
        _oGroup.off("pressup",_oListener);
    };

    this._onExitHelp = function(){
        this.unload();
        s_oGame._onExitHelp();
    };

    this._init();

}
