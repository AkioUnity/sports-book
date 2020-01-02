function CAreYouSurePanel(oConfirmFunction, oNegateFunction) {

    var _oButYes;
    var _oButNo;
    var _oFade;
    var _oPanelContainer;
    var _oParent;
    var _oListener;
    
    var _pStartPanelPos;

    this._init = function (oConfirmFunction, oNegateFunction) {
        _oFade = new createjs.Shape();
        _oFade.graphics.beginFill("black").drawRect(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
        _oFade.alpha = 1;
        _oListener = _oFade.on("mousedown",function(){});
        s_oStage.addChild(_oFade);

        _oPanelContainer = new createjs.Container();        
        s_oStage.addChild(_oPanelContainer);
        
        var oSprite = s_oSpriteLibrary.getSprite('msg_box');
        var oPanel = createBitmap(oSprite);        
        oPanel.regX = oSprite.width/2;
        oPanel.regY = oSprite.height/2;
        _oPanelContainer.addChild(oPanel);
        
        _oPanelContainer.x = CANVAS_WIDTH/2;
        _oPanelContainer.y = CANVAS_HEIGHT + oSprite.height/2;  
        _pStartPanelPos = {x: _oPanelContainer.x, y: _oPanelContainer.y};
        createjs.Tween.get(_oPanelContainer).to({y:CANVAS_HEIGHT/2},500, createjs.Ease.quartIn);

        var oTitle = new createjs.Text(TEXT_ARE_SURE," 60px "+PRIMARY_FONT, "#ffffff");
        oTitle.y = -100;
        oTitle.textAlign = "center";
        oTitle.textBaseline = "middle";
        oTitle.lineWidth = 400;
        _oPanelContainer.addChild(oTitle);

        _oButYes = new CGfxButton(110, 80, s_oSpriteLibrary.getSprite('but_yes'), _oPanelContainer);
        _oButYes.addEventListener(ON_MOUSE_UP, this._onButYes, this);

        _oButNo = new CGfxButton(-110, 80, s_oSpriteLibrary.getSprite('but_no'), _oPanelContainer);
        _oButNo.addEventListener(ON_MOUSE_UP, this._onButNo, this);
        _oButNo.pulseAnimation();
    };

    this._onButYes = function () {
        _oButNo.setClickable(false);
        _oButYes.setClickable(false);

        _oParent.unload();
        if(oConfirmFunction){
            oConfirmFunction();
        }
		window.location.href = '/eng/casino/content';
    };

    this._onButNo = function () {
        _oButNo.setClickable(false);
        _oButYes.setClickable(false);

        _oParent.unload();
        if(oNegateFunction){
            oNegateFunction();
        }

    };

    this.unload = function () {
        _oButNo.unload();
        _oButYes.unload();

        s_oStage.removeChild(_oFade);
        s_oStage.removeChild(_oPanelContainer);

        _oFade.off("mousedown",_oListener);
    };

    _oParent = this;
    this._init(oConfirmFunction, oNegateFunction);
}

