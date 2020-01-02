function CNeighborsPanel(){
    var _bFichesOnTable;
    var _iNumberClicked;
    var _iIndexFicheSelected;
    var _iTotMoney;
    var _iCurBet;
    var _aNeighborsNumbers;
    var _aValueFichesInPos;
    var _aFichesAttached;
    var _aEnlights;
    var _aMcFichesAttached;
    var _aNumClicked;
    var _aTotNumClicked;
    var _aAttachOffset;
    var _oBackBut;
    var _oAttachFiche;
    var _oMoneyText;
    var _oFade;
    var _oContainerWheel;
    var _oContainer;
    
    this._init = function(){
        _aTotNumClicked = new Array();
        
        _oContainer = new createjs.Container();
        s_oStage.addChild(_oContainer);
        
        _oFade = new createjs.Shape();
        _oFade.graphics.beginFill("rgba(0,0,0,0.7)").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        _oFade.on("click",function(){});
        _oContainer.addChild(_oFade);
        
        _oContainerWheel = new createjs.Container();
        _oContainerWheel.x = 265;
        _oContainerWheel.y = 85;
        _oContainer.addChild(_oContainerWheel);
        
        var oBg = createBitmap(s_oSpriteLibrary.getSprite('neighbor_bg'));
        _oContainerWheel.addChild(oBg);
        
        _oMoneyText = new createjs.Text(_iTotMoney+TEXT_CURRENCY,"20px "+FONT1, "#fff");
        _oMoneyText.textAlign = "center";
        _oMoneyText.x = 690;
        _oMoneyText.y = 564;
        _oContainerWheel.addChild(_oMoneyText);
        
        //ADD ENLIGHTS
        _aEnlights = new Array();

        var oBmp = new CEnlight(354,41,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        _aEnlights["oEnlight_0"] = oBmp;
        
        oBmp = new CEnlight(212,505,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-136.8);
        _aEnlights["oEnlight_1"] = oBmp;
        
        oBmp = new CEnlight(586,145,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(58.1);
        _aEnlights["oEnlight_2"] = oBmp;
        
        oBmp = new CEnlight(268,62,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-19.2);
        _aEnlights["oEnlight_3"] = oBmp;
        
        oBmp = new CEnlight(523,84,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(39);
        _aEnlights["oEnlight_4"] = oBmp;
        
        oBmp = new CEnlight(377,563,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-175);
        _aEnlights["oEnlight_5"] = oBmp;
        
        oBmp = new CEnlight(637,311,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(96.2);
        _aEnlights["oEnlight_6"] = oBmp;
        
        oBmp = new CEnlight(142,184,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-58.8);
        _aEnlights["oEnlight_7"] = oBmp;
        
        oBmp = new CEnlight(504,530,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(156.5);
        _aEnlights["oEnlight_8"] = oBmp;
        
        oBmp = new CEnlight(121,357,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-97);
        _aEnlights["oEnlight_9"] = oBmp;
        
        oBmp = new CEnlight(421,560,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(175.6);
        _aEnlights["oEnlight_10"] = oBmp;
        
        oBmp = new CEnlight(574,473,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(135.8);
        _aEnlights["oEnlight_11"] = oBmp;
        
        oBmp = new CEnlight(195,113,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-39.1);
        _aEnlights["oEnlight_12"] = oBmp;
        
        oBmp = new CEnlight(619,399,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(117.4);
        _aEnlights["oEnlight_13"] = oBmp;
        
        oBmp = new CEnlight(155,440,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-118.2);
        _aEnlights["oEnlight_14"] = oBmp;
        
        oBmp = new CEnlight(442,47,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(19.7);
        _aEnlights["oEnlight_15"] = oBmp;
        
        oBmp = new CEnlight(290,548,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-156.8);
        _aEnlights["oEnlight_16"] = oBmp;
        
        oBmp = new CEnlight(628,226,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(80.2);
        _aEnlights["oEnlight_17"] = oBmp;
        
        oBmp = new CEnlight(117,269,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-79.2);
        _aEnlights["oEnlight_18"] = oBmp;
        
        oBmp = new CEnlight(484,62,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(29.6);
        _aEnlights["oEnlight_19"] = oBmp;
        
        oBmp = new CEnlight(181,475,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-127.5);
        _aEnlights["oEnlight_20"] = oBmp;
        
        oBmp = new CEnlight(557,112,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(49.1);
        _aEnlights["oEnlight_21"] = oBmp;
        
        oBmp = new CEnlight(115,314,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-88.9);
        _aEnlights["oEnlight_22"] = oBmp;
        
        oBmp = new CEnlight(463,549,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(166.4);
        _aEnlights["oEnlight_23"] = oBmp;
        
        oBmp = new CEnlight(333,559,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-166.6);
        _aEnlights["oEnlight_24"] = oBmp;
        
        oBmp = new CEnlight(610,183,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(69);
        _aEnlights["oEnlight_25"] = oBmp;
        
        oBmp = new CEnlight(311,47,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-7.9);
        _aEnlights["oEnlight_26"] = oBmp;
        
        oBmp = new CEnlight(633,355,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(106.4);
        _aEnlights["oEnlight_27"] = oBmp;
        
        oBmp = new CEnlight(166,146,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-48.1);
        _aEnlights["oEnlight_28"] = oBmp;
        
        oBmp = new CEnlight(126,225,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-68.3);
        _aEnlights["oEnlight_29"] = oBmp;
        
        oBmp = new CEnlight(541,505,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(146);
        _aEnlights["oEnlight_30"] = oBmp;
        
        oBmp = new CEnlight(134,400,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-108.2);
        _aEnlights["oEnlight_31"] = oBmp;
        
        oBmp = new CEnlight(397,40,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(8.7);
        _aEnlights["oEnlight_32"] = oBmp;
        
        oBmp = new CEnlight(249,530,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-146.3);
        _aEnlights["oEnlight_33"] = oBmp;
        
        oBmp = new CEnlight(636,268,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(87.9);
        _aEnlights["oEnlight_34"] = oBmp;
        
        oBmp = new CEnlight(230,85,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(-29.1);
        _aEnlights["oEnlight_35"] = oBmp;
        
        oBmp = new CEnlight(600,439,s_oSpriteLibrary.getSprite('neighbor_enlight'),_oContainerWheel);
        oBmp.rotate(127.1);
        _aEnlights["oEnlight_36"] = oBmp;
        
        _oAttachFiche = new createjs.Container();
        _oContainerWheel.addChild(_oAttachFiche);
        
        //ADD BUTTON HIT AREAS
        var oSprite = s_oSpriteLibrary.getSprite('hitarea_neighbor');
        var oBut = new CGfxButton(376,72,oSprite,_oContainerWheel);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:0});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:0});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:0});
        
        oBut = new CGfxButton(415,76,oSprite,_oContainerWheel);
        oBut.rotate(9.2);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:32});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:32});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:32});
        
        oBut = new CGfxButton(453,86,oSprite,_oContainerWheel);
        oBut.rotate(20);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:15});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:15});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:15});
        
        oBut = new CGfxButton(490,102,oSprite,_oContainerWheel);
        oBut.rotate(29.4);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:19});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:19});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:19});
        
        oBut = new CGfxButton(520,124,oSprite,_oContainerWheel);
        oBut.rotate(39.4);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:4});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:4});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:4});
        
        oBut = new CGfxButton(549,150,oSprite,_oContainerWheel);
        oBut.rotate(48.8);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:21});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:21});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:21});
        
        oBut = new CGfxButton(571,181,oSprite,_oContainerWheel);
        oBut.rotate(58.5);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:2});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:2});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:2});
        
        oBut = new CGfxButton(588,216,oSprite,_oContainerWheel);
        oBut.rotate(68.7);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:25});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:25});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:25});
        
        oBut = new CGfxButton(599,253,oSprite,_oContainerWheel);
        oBut.rotate(78.9);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:17});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:17});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:17});
        
        oBut = new CGfxButton(604,291,oSprite,_oContainerWheel);
        oBut.rotate(90.4);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:34});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:34});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:34});
        
        oBut = new CGfxButton(603,330,oSprite,_oContainerWheel);
        oBut.rotate(96.5);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:6});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:6});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:6});
        
        oBut = new CGfxButton(596,368,oSprite,_oContainerWheel);
        oBut.rotate(107.5);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:27});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:27});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:27});
        
        oBut = new CGfxButton(580,404,oSprite,_oContainerWheel);
        oBut.rotate(116);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:13});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:13});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:13});
        
        oBut = new CGfxButton(560,438,oSprite,_oContainerWheel);
        oBut.rotate(126.2);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:36});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:36});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:36});
        
        oBut = new CGfxButton(534,466,oSprite,_oContainerWheel);
        oBut.rotate(135.7);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:11});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:11});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:11});
        
        oBut = new CGfxButton(504,490,oSprite,_oContainerWheel);
        oBut.rotate(145.2);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:30});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:30});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:30});
        
        oBut = new CGfxButton(471,510,oSprite,_oContainerWheel);
        oBut.rotate(154.9);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:8});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:8});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:8});
        
        oBut = new CGfxButton(434,522,oSprite,_oContainerWheel);
        oBut.rotate(165.2);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:23});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:23});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:23});
        
        oBut = new CGfxButton(395,529,oSprite,_oContainerWheel);
        oBut.rotate(174.9);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:10});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:10});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:10});
        
        oBut = new CGfxButton(357,529,oSprite,_oContainerWheel);
        oBut.rotate(-176.5);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:5});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:5});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:5});
        
        oBut = new CGfxButton(319,522,oSprite,_oContainerWheel);
        oBut.rotate(-166);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:24});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:24});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:24});
        
        oBut = new CGfxButton(282,509,oSprite,_oContainerWheel);
        oBut.rotate(-156);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:16});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:16});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:16});
        
        oBut = new CGfxButton(247,491,oSprite,_oContainerWheel);
        oBut.rotate(-146);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:33});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:33});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:33});
        
        oBut = new CGfxButton(217,466,oSprite,_oContainerWheel);
        oBut.rotate(-137);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:1});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:1});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:1});
        
        oBut = new CGfxButton(193,437,oSprite,_oContainerWheel);
        oBut.rotate(-128);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:20});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:20});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:20});
        
        oBut = new CGfxButton(172,404,oSprite,_oContainerWheel);
        oBut.rotate(-118.4);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:14});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:14});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:14});
        
        oBut = new CGfxButton(158,367,oSprite,_oContainerWheel);
        oBut.rotate(-105.7);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:31});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:31});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:31});
        
        oBut = new CGfxButton(149,330,oSprite,_oContainerWheel);
        oBut.rotate(-95.5);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:9});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:9});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:9});
        
        oBut = new CGfxButton(148,291,oSprite,_oContainerWheel);
        oBut.rotate(-88.2);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:22});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:22});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:22});
        
        oBut = new CGfxButton(154,252,oSprite,_oContainerWheel);
        oBut.rotate(-78);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:18});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:18});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:18});
        
        oBut = new CGfxButton(164,216,oSprite,_oContainerWheel);
        oBut.rotate(-67.8);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:29});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:29});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:29});
        
        oBut = new CGfxButton(181,181,oSprite,_oContainerWheel);
        oBut.rotate(-57.6);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:7});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:7});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:7});
        
        oBut = new CGfxButton(205,150,oSprite,_oContainerWheel);
        oBut.rotate(-48.8);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:28});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:28});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:28});
        
        oBut = new CGfxButton(233,124,oSprite,_oContainerWheel);
        oBut.rotate(-39.1);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:12});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:12});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:12});
        
        oBut = new CGfxButton(265,102,oSprite,_oContainerWheel);
        oBut.rotate(-29.9);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:35});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:35});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:35});
        
        oBut = new CGfxButton(300,86,oSprite,_oContainerWheel);
        oBut.rotate(-19.2);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:3});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:3});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:3});
        
        oBut = new CGfxButton(338,76,oSprite,_oContainerWheel);
        oBut.rotate(-8.5);
        oBut.addEventListenerWithParams(ON_MOUSE_UP, this._onNeighborRelease, this,{index:26});
        oBut.addEventListenerWithParams(ON_MOUSE_OVER, this._onNeighborOver, this,{index:26});
        oBut.addEventListenerWithParams(ON_MOUSE_OUT, this._onNeighborOut, this,{index:26});
        
        this._initNeighbors();
	
        _oBackBut = new CGfxButton(717 , 38,s_oSpriteLibrary.getSprite('but_exit'),_oContainerWheel);
        _oBackBut.addEventListener(ON_MOUSE_UP, this.onExit, this);

        this.reset();
        
        this.hide();
    };
	
    this.unload = function(){
        _oFade.off("click",function(){});
        for(var i=0;i<_oContainer.getNumChildren();i++){
                if(oBut instanceof CGfxButton){
                        var oBut = _oContainer.getChildAt(i);
                        oBut.unload();
                }
        }
    };
    
    this.showPanel = function(iIndexFicheSelected,iMoney,iCurBet){
        _iIndexFicheSelected = iIndexFicheSelected;

        _iTotMoney = iMoney;
        _iCurBet = iCurBet;
        _aNumClicked = new Array();
        
        _oMoneyText.text = iMoney + TEXT_CURRENCY;
        _oContainer.visible = true;
    };
    
    this.hide = function(){
        _bFichesOnTable = false;
        _oContainer.visible = false;
    };
    
    this._initNeighbors = function(){
        _aNeighborsNumbers=new Array();
        _aNeighborsNumbers[0]  = new Array(3,26,0,32,15);
        _aNeighborsNumbers[1]  = new Array(16,33,1,20,14);
        _aNeighborsNumbers[2]  = new Array(4,21,2,25,17);
        _aNeighborsNumbers[3]  = new Array(12,35,3,26,0);
        _aNeighborsNumbers[4]  = new Array(15,19,4,21,2);
        _aNeighborsNumbers[5]  = new Array(23,10,5,24,16);
        _aNeighborsNumbers[6]  = new Array(17,34,6,27,13);
        _aNeighborsNumbers[7]  = new Array(18,29,7,28,12);
        _aNeighborsNumbers[8]  = new Array(11,30,8,23,10);
        _aNeighborsNumbers[9]  = new Array(14,31,9,22,18);
        _aNeighborsNumbers[10] = new Array(8,23,10,5,24); 
        _aNeighborsNumbers[11] = new Array(13,36,11,30,8);
        _aNeighborsNumbers[12] = new Array(7,28,12,35,3);
        _aNeighborsNumbers[13] = new Array(6,27,13,36,11);
        _aNeighborsNumbers[14] = new Array(1,20,14,31,9);
        _aNeighborsNumbers[15] = new Array(0,32,15,19,4);
        _aNeighborsNumbers[16] = new Array(5,24,16,33,1);
        _aNeighborsNumbers[17] = new Array(2,25,17,34,6);
        _aNeighborsNumbers[18] = new Array(9,22,18,29,7);
        _aNeighborsNumbers[19] = new Array(32,15,19,4,21);
        _aNeighborsNumbers[20] = new Array(33,1,20,14,31);
        _aNeighborsNumbers[21] = new Array(19,4,21,2,25);
        _aNeighborsNumbers[22] = new Array(31,9,22,18,29);
        _aNeighborsNumbers[23] = new Array(30,8,23,10,5);
        _aNeighborsNumbers[24] = new Array(10,5,24,16,33);
        _aNeighborsNumbers[25] = new Array(21,2,25,17,34);
        _aNeighborsNumbers[26] = new Array(35,3,26,0,32);
        _aNeighborsNumbers[27] = new Array(34,6,27,13,36);
        _aNeighborsNumbers[28] = new Array(29,7,28,12,35);
        _aNeighborsNumbers[29] = new Array(22,18,29,7,28);
        _aNeighborsNumbers[30] = new Array(36,11,30,8,23);
        _aNeighborsNumbers[31] = new Array(20,14,31,9,22);
        _aNeighborsNumbers[32] = new Array(26,0,32,15,19);
        _aNeighborsNumbers[33] = new Array(24,16,33,1,20);
        _aNeighborsNumbers[34] = new Array(25,17,34,6,27);
        _aNeighborsNumbers[35] = new Array(28,12,35,3,26);
        _aNeighborsNumbers[36] = new Array(27,13,36,11,30);

        //INIT ATTACH OFFSET
        _aAttachOffset = new Array();
        _aAttachOffset["oAttach_0"] = new createjs.Point(377,70);
        _aAttachOffset["oAttach_32"] = new createjs.Point(416,74);
        _aAttachOffset["oAttach_15"] = new createjs.Point(454,85);
        _aAttachOffset["oAttach_19"] = new createjs.Point(492,101);
        _aAttachOffset["oAttach_4"] = new createjs.Point(523,122);
        _aAttachOffset["oAttach_21"] = new createjs.Point(550,150);
        _aAttachOffset["oAttach_2"] = new createjs.Point(572,180);
        _aAttachOffset["oAttach_25"] = new createjs.Point(588,216);
        _aAttachOffset["oAttach_17"] = new createjs.Point(603,255);
        _aAttachOffset["oAttach_34"] = new createjs.Point(607,294);
        _aAttachOffset["oAttach_6"] = new createjs.Point(605,330);
        _aAttachOffset["oAttach_27"] = new createjs.Point(600,370);
        _aAttachOffset["oAttach_13"] = new createjs.Point(585,408);
        _aAttachOffset["oAttach_36"] = new createjs.Point(565,442);
        _aAttachOffset["oAttach_11"] = new createjs.Point(538,472);
        _aAttachOffset["oAttach_30"] = new createjs.Point(506,494);
        _aAttachOffset["oAttach_8"] = new createjs.Point(475,515);
        _aAttachOffset["oAttach_23"] = new createjs.Point(435,526);
        _aAttachOffset["oAttach_10"] = new createjs.Point(398,536);
        _aAttachOffset["oAttach_5"] = new createjs.Point(357,534);
        _aAttachOffset["oAttach_24"] = new createjs.Point(318,526);
        _aAttachOffset["oAttach_16"] = new createjs.Point(282,513);
        _aAttachOffset["oAttach_33"] = new createjs.Point(245,494);
        _aAttachOffset["oAttach_1"] = new createjs.Point(218,468);
        _aAttachOffset["oAttach_20"] = new createjs.Point(190,440);
        _aAttachOffset["oAttach_14"] = new createjs.Point(173,406);
        _aAttachOffset["oAttach_31"] = new createjs.Point(156,368);
        _aAttachOffset["oAttach_9"] = new createjs.Point(150,330);
        _aAttachOffset["oAttach_22"] = new createjs.Point(148,292);
        _aAttachOffset["oAttach_18"] = new createjs.Point(153,252);
        _aAttachOffset["oAttach_29"] = new createjs.Point(165,215);
        _aAttachOffset["oAttach_7"] = new createjs.Point(182,183);
        _aAttachOffset["oAttach_28"] = new createjs.Point(208,150);
        _aAttachOffset["oAttach_12"] = new createjs.Point(233,123);
        _aAttachOffset["oAttach_35"] = new createjs.Point(266,100);
        _aAttachOffset["oAttach_3"] = new createjs.Point(302,86);
        _aAttachOffset["oAttach_26"] = new createjs.Point(339,76);
    };

    

    this.reset = function(){
        _aValueFichesInPos=new Array();
        for(var i=0;i<NUMBERS_TO_BET;i++){
                _aValueFichesInPos[i]=0;
        }

        if(_aFichesAttached){
            for(var j=0;j<_aFichesAttached.length;j++){
                _oAttachFiche.removeChild(_aFichesAttached[j].getSprite());
            }
        }

        _aFichesAttached=new Array();
        _aMcFichesAttached = new Array();
        _iCurBet = 0;
        _bFichesOnTable = false;
    };
    
    this.clearLastBet = function(){
        if(_aTotNumClicked.length === 0){
            return;
        }
        
        var iNumberSelected = _aTotNumClicked.pop();
        
        //RESET BET VALUE FOR THE LAST SELECTED NUMBER
        var iFicheValue = s_oGameSettings.getFicheValues(_iIndexFicheSelected);
        _aValueFichesInPos[iNumberSelected] -= iFicheValue;
        _aValueFichesInPos[iNumberSelected] = roundDecimal(_aValueFichesInPos[iNumberSelected],1);

        //REMOVE FICHE OF THE LAST BET ON NEIGHTBOR PANEL
        var aFiches = _aMcFichesAttached[iNumberSelected];

        if(aFiches.length > 0){
            _oAttachFiche.removeChild(aFiches[aFiches.length-1].getSprite());
        }else{
            _aTotNumClicked = new Array();
            _aValueFichesInPos[iNumberSelected]=0;
        }
        
        
        
        _aFichesAttached.pop();
        _aMcFichesAttached[iNumberSelected].pop();
        
        if(_aTotNumClicked.length === 0){
            _aFichesAttached=new Array();
            _aMcFichesAttached = new Array();
            _bFichesOnTable = false;
            for(var i=0;i<NUMBERS_TO_BET;i++){
                _aValueFichesInPos[i]=0;
            }
        }
        
        _iCurBet = 0;
    };

    this.onExit = function(){
        this.hide();
    };

    this.addFicheOnNeighborTable = function(){
        var iFicheValue = s_oGameSettings.getFicheValues(_iIndexFicheSelected);
        
        if( (_iCurBet + (iFicheValue*5) ) >_iTotMoney){
            s_oGame.showMsgBox(TEXT_ERROR_NO_MONEY_MSG);
            return;
        }else if((_iCurBet + (iFicheValue*5) ) > MAX_BET){
            s_oGame.showMsgBox(TEXT_ERROR_MAX_BET_REACHED);
            return;
        }else{
            _iCurBet += (iFicheValue*5);
            _iCurBet = roundDecimal(_iCurBet,1);
            var iAmount = (_iTotMoney - _iCurBet);
            iAmount = roundDecimal(iAmount,1);
            _oMoneyText.text =  iAmount + TEXT_CURRENCY;
        }

        
        playSound("chip",1,false);
        
        
        _aValueFichesInPos[_iNumberClicked] += iFicheValue;
        _aValueFichesInPos[_iNumberClicked] = roundDecimal(_aValueFichesInPos[_iNumberClicked],1);

        var aFiches = s_oGameSettings.generateFichesPileByIndex(_aValueFichesInPos[_iNumberClicked]);
        aFiches.sort();

        this._removeFichesPile(_aMcFichesAttached[_iNumberClicked]);
        _aMcFichesAttached[_iNumberClicked] = new Array();

        var iXPos = _aAttachOffset["oAttach_"+_iNumberClicked].x;
        var iYPos=_aAttachOffset["oAttach_"+_iNumberClicked].y;
        for(var k=0;k<aFiches.length;k++){
            this._attachFichesPile(aFiches[k],_iNumberClicked,iXPos,iYPos);
            iYPos -= 5;
        }
	
        _aNumClicked.push(_iNumberClicked);
        var aBets=_aNeighborsNumbers[_iNumberClicked];
         s_oGame._onShowBetOnTableFromNeighbors({button:"oBetNeighbors",numbers:aBets,bet_mult:5,bet_win:7.2,
                                                                     value:_iIndexFicheSelected,num_fiches:5,num_clicked:_iNumberClicked},false);

        
        _aTotNumClicked.push(_iNumberClicked);
        _bFichesOnTable = true;
    };

    this._attachFichesPile = function(iIndexFicheSelected,iNumber,iXPos,iYPos){
        var oFicheMc = new CFiche(iXPos,iYPos,iIndexFicheSelected,_oAttachFiche,0.7);

        _aMcFichesAttached[iNumber].push(oFicheMc);
        _aFichesAttached.push(oFicheMc);
    };

    this._removeFichesPile = function(aFiches){
        for(var i in aFiches){
            _oAttachFiche.removeChild(aFiches[i].getSprite());
        }
    };
    
    this.searchForNumClicked = function(){
        for(var i=0;i<_aNumClicked.length;i++){
            if(_aNumClicked[i] === _iNumberClicked){
                return true;
            }
        }
        
        return false;
    }
    
    this._onNeighborRelease = function(oParams){
        _iNumberClicked = oParams.index;

        this.addFicheOnNeighborTable();
   };
   
   this.rebet = function(iNumClicked){
       _iNumberClicked = iNumClicked;

        this.addFicheOnNeighborTable();
   };
   
   this._onNeighborOver = function(oParams){
        var aBets = _aNeighborsNumbers[oParams.index];

        for(var i=0;i<aBets.length;i++){
            _aEnlights["oEnlight_"+aBets[i]].show();
        }
    };

    this._onNeighborOut = function(oParams){
        var aBets = _aNeighborsNumbers[oParams.index];

        for(var i=0;i<aBets.length;i++){
            _aEnlights["oEnlight_"+aBets[i]].hide();
        }
    };
	
    this.isVisible = function(){
            return _oContainer.visible;
    };
   
    this._init();
}