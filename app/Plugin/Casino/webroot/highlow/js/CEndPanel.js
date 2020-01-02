function CEndPanel(oSpriteBg){
    
    var _oBg;
    var _oGroup;
    
    var _oMsgTextBack;
    var _oMsgText;
    
    this._init = function(oSpriteBg){
        
        _oBg = createBitmap(oSpriteBg);
        _oBg.x = 0;
        _oBg.y = 0;
        
	_oMsgTextBack = new createjs.Text("","60px "+PRIMARY_FONT, "#000");
        _oMsgTextBack.x = CANVAS_WIDTH/2 +3;
        _oMsgTextBack.y = (CANVAS_HEIGHT/2) +2;
        _oMsgTextBack.textAlign = "center";
        _oMsgTextBack.textBaseline = "middle";
        _oMsgTextBack.lineWidth = 500;

        _oMsgText = new createjs.Text("","60px "+PRIMARY_FONT, "#ffffff");
        _oMsgText.x = CANVAS_WIDTH/2;
        _oMsgText.y = (CANVAS_HEIGHT/2);
        _oMsgText.textAlign = "center";
        _oMsgText.textBaseline = "middle";
        _oMsgText.lineWidth = 500;

        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        
        _oGroup.addChild(_oBg,_oMsgTextBack,_oMsgText);

        s_oStage.addChild(_oGroup);
    };
    
    this.unload = function(){
        _oGroup.off("mousedown",this._onExit);
    };
    
    this._initListener = function(){
        _oGroup.on("mousedown",this._onExit);
    };
    
    this.show = function(){
	playSound("game_over",1,false);
        
        
        _oMsgTextBack.text = TEXT_GAMEOVER;
        _oMsgText.text = TEXT_GAMEOVER;
        
        _oGroup.visible = true;
        
        $(s_oMain).trigger("end_level",1);
        $(s_oMain).trigger("show_interlevel_ad");
        
        var oParent = this;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500).call(function() {oParent._initListener();});
       
    };
    
    this._onExit = function(){
        _oGroup.off("mousedown",this._onExit);
        s_oStage.removeChild(_oGroup);
        
        window.location.href = '/eng/casino/content';
        $(s_oMain).trigger("end_session");
        
        s_oGame.onExit();
    };
    
    this._init(oSpriteBg);
    
    return this;
}
