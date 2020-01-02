function CEndPanel(oSpriteBg){
    
    var _oBg;
    var _oGroup;
    var _oMsgTextBack;
    var _oScoreTextBack;
    var _oMsgText;
    var _oScoreText;
    var _oListener;
    
    this._init = function(oSpriteBg){
        
        _oBg = createBitmap(oSpriteBg);
        
        var iTopPos={x:CANVAS_WIDTH/2 +1, y:(CANVAS_HEIGHT/2)-160};
        var iBotPos={x:CANVAS_WIDTH/2 +1, y:(CANVAS_HEIGHT/2)- 20};
        
        _oMsgTextBack = new createjs.Text(""," 60px "+PRIMARY_FONT, "#000");
        _oMsgTextBack.x = iBotPos.x+2;
        _oMsgTextBack.y = iBotPos.y+2;
        _oMsgTextBack.textAlign = "center";
        _oMsgTextBack.lineWidth = 400;

        _oMsgText = new createjs.Text(""," 60px "+PRIMARY_FONT, "#ffffff");
        _oMsgText.x = iBotPos.x;
        _oMsgText.y = iBotPos.y;
        _oMsgText.textAlign = "center";
        _oMsgText.lineWidth = 400;
        
        _oScoreTextBack = new createjs.Text(""," 50px "+PRIMARY_FONT, "#000");
        _oScoreTextBack.x = iTopPos.x+2;
        _oScoreTextBack.y = iTopPos.y+2;
        _oScoreTextBack.textAlign = "center";
        
        _oScoreText = new createjs.Text(""," 50px "+PRIMARY_FONT, "#ffffff");
        _oScoreText.x = iTopPos.x;
        _oScoreText.y = iTopPos.y;
        _oScoreText.textAlign = "center";
        
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        
        _oGroup.addChild(_oBg, _oScoreTextBack,_oScoreText,_oMsgTextBack,_oMsgText);

        s_oStage.addChild(_oGroup);
    };
    
    this.unload = function(){
        _oGroup.off("mousedown",_oListener);
    };
    
    this._initListener = function(){
        _oListener = _oGroup.on("mousedown",this._onExit);
    };
    
    this.show = function(iScore, bool){
        if(bool){//Win Panel
            _oMsgTextBack.text = TEXT_WIN + " \n" +iScore + " " + TEXT_CURRENCY;
            _oMsgText.text = TEXT_WIN + " \n" +iScore + " " + TEXT_CURRENCY;

            _oScoreTextBack.text = TEXT_CONGRATS;
            _oScoreText.text = TEXT_CONGRATS;
        } else {//Lose Panel
            playSound("loose",1,false);
            
            _oMsgTextBack.text = TEXT_NO_WIN;
            _oMsgTextBack.y = 202;
            _oMsgText.text = TEXT_NO_WIN;
            _oMsgText.y = 200;
        }
        
        
        _oGroup.visible = true;
        
        var oParent = this;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500).call(function() {
            oParent._initListener();
            $(s_oMain).trigger("end_session");
            $(s_oMain).trigger("show_interlevel_ad");
        });

    };
    
    this._onExit = function(){
		window.location.href = '/eng/casino/content';
        _oGroup.off("mousedown",_oListener);
        
        s_oStage.removeChild(_oGroup);
        
        s_oGame.onExit();   
        
    };
    
    this._init(oSpriteBg);
    
    return this;
}