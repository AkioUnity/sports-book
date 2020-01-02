function CPreloader(){
    var _iMaskWidth;
    var _oLoadingText;
    var _oPreloaderBar;
    var _oProgressBar;
    var _oMaskPreloader;
    var _oFade;
    var _oContainer;
    
    this._init = function(){
       s_oSpriteLibrary.init( this._onImagesLoaded,this._onAllImagesLoaded, this );
       s_oSpriteLibrary.addSprite("bg_preloader","./sprites/bg_preloader.jpg");
       s_oSpriteLibrary.addSprite("preloader_bar","./sprites/preloader_bar.png");
       s_oSpriteLibrary.addSprite("progress_bar","./sprites/progress_bar.png");
       s_oSpriteLibrary.loadSprites();
       
       _oContainer = new createjs.Container();
       s_oStage.addChild(_oContainer); 
    };
    
    this.unload = function(){
	_oContainer.removeAllChildren();
    };
    
    this.hide = function(){
        var oParent = this;
        setTimeout(function(){createjs.Tween.get(_oFade).to({alpha:1}, 500).call(function(){oParent.unload();s_oMain.gotoMenu();}); }, 1000);
    };
    
    this._onImagesLoaded = function(){
        
    };
    
    this._onAllImagesLoaded = function(){
        this.attachSprites();
        
        s_oMain.preloaderReady();
    };
    
    this.attachSprites = function(){
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_preloader'));
        _oContainer.addChild(oBg);

        var oSprite = s_oSpriteLibrary.getSprite('preloader_bar');
       _oPreloaderBar = createBitmap(oSprite);
       _oPreloaderBar.x = 510;
       _oPreloaderBar.y = CANVAS_HEIGHT - 131;
       _oContainer.addChild(_oPreloaderBar);
       
       _oProgressBar  = createBitmap(s_oSpriteLibrary.getSprite('progress_bar'));
       _oProgressBar.x = 511;
       _oProgressBar.y = CANVAS_HEIGHT - 130;
       _oContainer.addChild(_oProgressBar);
       
       _iMaskWidth = oSprite.width;
       _oMaskPreloader = new createjs.Shape();
       _oMaskPreloader.graphics.beginFill("rgba(255,0,0,0.01)").drawRect(511, CANVAS_HEIGHT - 130, 1,30);
       _oContainer.addChild(_oMaskPreloader);
       
       _oProgressBar.mask = _oMaskPreloader;
       
       _oLoadingText = new createjs.Text("","24px "+FONT_GAME, "#fff");
       _oLoadingText.x = CANVAS_WIDTH/2;
       _oLoadingText.y = CANVAS_HEIGHT - 108;
       _oLoadingText.shadow = new createjs.Shadow("#000", 2, 2, 2);
       _oLoadingText.textBaseline = "alphabetic";
       _oLoadingText.textAlign = "center";
       _oContainer.addChild(_oLoadingText);
       
       _oFade = new createjs.Shape();
       _oFade.graphics.beginFill("black").drawRect(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
       _oFade.alpha = 0;
        
        _oContainer.addChild(_oFade);
    };
    
    this.refreshLoader = function(iPerc){
        _oLoadingText.text = iPerc+"%";
        
        
        _oMaskPreloader.graphics.clear();
        var iNewMaskWidth = Math.floor((iPerc*_iMaskWidth)/100);
        _oMaskPreloader.graphics.beginFill("rgba(255,0,0,0.01)").drawRect(511, CANVAS_HEIGHT - 130, iNewMaskWidth,30);
    };
    
    this._init();   
}