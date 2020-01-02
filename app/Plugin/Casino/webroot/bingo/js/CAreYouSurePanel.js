function CAreYouSurePanel(oParentContainer){
    var _oMsg;
    var _oButYes;
    var _oButNo;
    var _oContainer;
    var _oParentContainer;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.visible = false;
        _oParentContainer.addChild(_oContainer);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('msg_box'));
        _oContainer.addChild(oBg); 
        
        _oMsg = new createjs.Text(TEXT_ARE_SURE,"80px " +PRIMARY_FONT, "#fff");
        _oMsg.x = CANVAS_WIDTH/2;
        _oMsg.y = 450 ;
        _oMsg.textAlign = "center";
        _oMsg.textBaseline = "middle";
        _oContainer.addChild(_oMsg);
        
        _oButYes = new CTextButton(CANVAS_WIDTH/2 + 170,770,s_oSpriteLibrary.getSprite('but_gui'),TEXT_YES,PRIMARY_FONT,"#ffffff",50,BUTTON_Y_OFFSET,_oContainer);
        _oButYes.addEventListener(ON_MOUSE_UP, this._onButYes, this);
        
        _oButNo = new CTextButton(CANVAS_WIDTH/2 - 170,770,s_oSpriteLibrary.getSprite('but_gui'),TEXT_NO,PRIMARY_FONT,"#ffffff",50,BUTTON_Y_OFFSET,_oContainer);
        _oButNo.addEventListener(ON_MOUSE_UP, this._onButNo, this);
        
    };
    
    this.show = function(){
        createjs.Ticker.paused = true;
        _oContainer.visible = true;
    };
    
    this._onButYes = function(){
        createjs.Ticker.paused = false;
		window.location.href = '/eng/casino/content';
        s_oGame.onExit();
    };
    
    this._onButNo = function(){
        createjs.Ticker.paused = false;
        _oContainer.visible = false;
    };
    
    _oParentContainer = oParentContainer;
    
    this._init();
}