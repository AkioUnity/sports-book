function CGiveupPanel(oSpriteBg, iScore){
    
    var _oBg;
    var _oGroup;
    
    var _oMsgTextBack;
    var _oMsgText;
    var _oBlockGroup;
    var _oButYes;
    var _oButNo;
    
    this._init = function(oSpriteBg, iScore){
        
        _oBg = createBitmap(oSpriteBg);
        _oBg.x = 0;
        _oBg.y = 0;
        
	_oMsgTextBack = new createjs.Text("","60px "+PRIMARY_FONT, "#000");
        _oMsgTextBack.x = CANVAS_WIDTH/2 +2;
        _oMsgTextBack.y = (CANVAS_HEIGHT/2)-140;
        _oMsgTextBack.textAlign = "center";
        _oMsgTextBack.textBaseline = "alphabetic";
        _oMsgTextBack.lineWidth = 500;

        _oMsgText = new createjs.Text("","60px "+PRIMARY_FONT, "#ffffff");
        _oMsgText.x = CANVAS_WIDTH/2;
        _oMsgText.y = (CANVAS_HEIGHT/2)-142;
        _oMsgText.textAlign = "center";
        _oMsgText.textBaseline = "alphabetic";
        _oMsgText.lineWidth = 500;
        
        _oBlockGroup = new createjs.Shape();
        _oBlockGroup.graphics.beginFill("rgba(255,255,255,0.01)").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        _oBlockGroup.on("mousedown",function(){});
        
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        
        _oGroup.addChild(_oBg,_oMsgTextBack,_oMsgText, _oBlockGroup);

        var oSprite = s_oSpriteLibrary.getSprite('but_bet');
        _oButYes = new CTextButton(CANVAS_WIDTH/2 - 200,CANVAS_HEIGHT/2 +170,oSprite,TEXT_YES,PRIMARY_FONT,"#ffffff",40, false, _oGroup);
        _oButYes.addEventListener(ON_MOUSE_UP, this._onYesRelease, this);
        _oButYes.enable();
        
        _oButNo = new CTextButton(CANVAS_WIDTH/2 + 200,CANVAS_HEIGHT/2 + 170,oSprite,TEXT_NO,PRIMARY_FONT,"#ffffff",40, false, _oGroup);
        _oButNo.addEventListener(ON_MOUSE_UP, this._onNoRelease, this);
        _oButNo.enable();

        s_oStage.addChild(_oGroup);
        
        playSound("click",1,false);
        
        
        _oMsgTextBack.text = TEXT_ENDGIVEUP +TEXT_CURRENCY+iScore +" ?";
        _oMsgText.text = TEXT_ENDGIVEUP +TEXT_CURRENCY+iScore+" ?";
        
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500);
        
    };
    
    this.unload = function(){
        s_oStage.removeChild(_oGroup);
    };
    
    
    this._onYesRelease = function(){
        
        $(s_oMain).trigger("save_score",[iScore,"standard"]);
        $(s_oMain).trigger("end_level",1);
        $(s_oMain).trigger("end_session");

        
        $(s_oMain).trigger("share_event",[iScore]);
        
        s_oStage.removeChild(_oGroup);
        s_oGame.onExit();
        
    };
    
    this._onNoRelease = function(){
        this.unload();
    };
    
    this._init(oSpriteBg, iScore);
    
    return this;
}
