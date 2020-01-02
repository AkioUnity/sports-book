function CMsgBox(oSpriteBg){
    
    var _oBg;
    var _oGroup;
    
    var _oMsgTextBack;
    var _oMsgText;
    var _oButExit;
    
    this._init = function(oSpriteBg){
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        
        
        _oBg = createBitmap(oSpriteBg);
        _oGroup.addChild(_oBg);
        
	_oMsgTextBack = new createjs.Text("","60px "+PRIMARY_FONT, "#000");
        _oMsgTextBack.x = CANVAS_WIDTH/2 +2 ;
        _oMsgTextBack.y = (CANVAS_HEIGHT/2) -98;
        _oMsgTextBack.textAlign = "center";
        _oMsgTextBack.textBaseline = "middle";
        _oMsgTextBack.lineWidth = 650;
        _oGroup.addChild(_oMsgTextBack);

        _oMsgText = new createjs.Text("","60px "+PRIMARY_FONT, "#ffffff");
        _oMsgText.x = CANVAS_WIDTH/2;
        _oMsgText.y = (CANVAS_HEIGHT/2) - 100;
        _oMsgText.textAlign = "center";
        _oMsgText.textBaseline = "middle";
        _oMsgText.lineWidth = 650;    
        _oGroup.addChild(_oMsgText);
        
        _oButExit = new CTextButton(CANVAS_WIDTH/2,CANVAS_HEIGHT/2 + 150,s_oSpriteLibrary.getSprite('but_gui'),TEXT_EXIT,PRIMARY_FONT,"#ffffff",50,BUTTON_Y_OFFSET,_oGroup);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);

        s_oStage.addChild(_oGroup);
    };

    
    this.show = function(szText){
	if(DISABLE_SOUND_MOBILE === false || s_bMobile === false ){
	        createjs.Sound.play("game_over");
	}
        
        
        _oMsgTextBack.text = szText;
        _oMsgText.text = szText;
        
        _oGroup.visible = true;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500);
    };
    
    this._onExit = function(){
        s_oStage.removeChild(_oGroup);
        
        $(s_oMain).trigger("end_session");
    };
    
    this._init(oSpriteBg);
    
    return this;
}
