function CTableController(){
    var _oContainer;
    
    var _aCbCompleted;
    var _aCbOwner;
    
    this._init = function(){
        _oContainer = new createjs.Container();
        _oContainer.x = 285;
        _oContainer.y = 102;
        s_oStage.addChild(_oContainer);
        
        //INIT ALL BUTTONS
        var oBut;
        /*******************TWELVE BET***************/
        oBut = new CBetTableButton(62,221,s_oSpriteLibrary.getSprite('hit_area_twelve_bet'),"first12",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(161,296,s_oSpriteLibrary.getSprite('hit_area_twelve_bet'),"second12",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(263,373,s_oSpriteLibrary.getSprite('hit_area_twelve_bet'),"third12",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        /*************************SIMPLE BETS******************/
        
        oBut = new CBetTableButton(54,118,s_oSpriteLibrary.getSprite('hit_area_bet0'),"bet_0",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        var aPos =new Array({x:56,y:162},{x:81,y:137},{x:104,y:115},{x:78,y:181},{x:104,y:156},{x:129,y:131},{x:103,y:197},{x:128,y:172},
                            {x:152,y:148},{x:128,y:215},{x:153,y:190},{x:176,y:166},{x:153,y:233},{x:176,y:208},{x:201,y:183},{x:177,y:253},
                            {x:201,y:226},{x:226,y:202},{x:202,y:271},{x:227,y:244},{x:251,y:220},{x:228,y:289},{x:250,y:265},{x:275,y:238},
                            {x:254,y:310},{x:279,y:282},{x:302,y:257},{x:280,y:330},{x:305,y:301},{x:328,y:275},{x:308,y:348},{x:331,y:322},
                            {x:354,y:294},{x:335,y:370},{x:359,y:341},{x:383,y:314});
        
        var oSprite = s_oSpriteLibrary.getSprite('hit_area_simple_bet');
        for(var k=1;k<37;k++){
            oBut = new CBetTableButton(aPos[k-1].x,aPos[k-1].y,oSprite,"bet_"+k,_oContainer,false);
            oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
            if(s_bMobile === false){
                oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
                oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
            }
        }

        /**********************COUPLE BET***********************/
        oBut = new CBetTableButton(43,153,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_0_1",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(68,129,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_0_2",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }

        oBut = new CBetTableButton(95,105,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_0_3",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(67,172,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_1_4",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(93,145,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_2_5",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(117,121,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_3_6",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(92,187,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_4_7",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(116,163,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_5_8",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(141,138,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_6_9",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(117,205,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_7_10",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(140,181,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_8_11",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(165,155,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_9_12",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(140,223,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_10_13",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(165,198,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_11_14",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(190,172,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_12_15",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(164,242,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_13_16",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(189,216,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_14_17",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(213,192,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_15_18",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(188,262,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_16_19",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(213,236,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_17_20",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(238,211,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_18_21",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(213,282,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_19_22",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(239,254,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_20_23",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(264,228,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_21_24",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(240,300,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_22_25",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(267,272,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_23_26",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(291,245,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_24_27",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(266,320,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_25_28",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(290,293,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_26_29",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(314,267,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_27_30",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(294,339,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_28_31",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(318,311,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_29_32",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(341,285,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_30_33",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(320,360,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_31_34",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(346,329,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_32_35",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(368,305,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_33_36",_oContainer,false);
        oBut.rotate(-45);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		/********************COUPLE BET HORIZONTAL***********************/
		
		oBut = new CBetTableButton(70,150,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_1_2",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(94,126,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_2_3",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(92,167,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_4_5",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(116,143,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_5_6",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(116,185,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_7_8",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(141,162,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_8_9",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(140,202,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_10_11",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(165,180,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_11_12",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(165,220,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_13_14",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(189,197,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_14_15",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(189,238,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_16_17",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(214,212,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_17_18",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(215,258,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_19_20",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(240,230,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_20_21",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(240,276,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_22_23",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(266,250,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_23_24",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(266,296,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_25_26",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(292,269,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_26_27",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(292,316,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_28_29",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(318,288,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_29_30",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(319,336,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_31_32",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(346,308,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_32_33",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		oBut = new CBetTableButton(346,354,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_34_35",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		
		oBut = new CBetTableButton(371,328,s_oSpriteLibrary.getSprite('hit_area_couple_bet'),"bet_35_36",_oContainer,false);
		oBut.rotate(38);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
		
		
		
		
        /*********************TRIPLE BET*******************/
        oBut = new CBetTableButton(57,142,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_0_1_2",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(82,118,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_0_2_3",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(44,173,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_1_2_3",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(67,191,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_4_5_6",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(91,208,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_7_8_9",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(116,228,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_10_11_12",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(140,247,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_13_14_15",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(165,265,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_16_17_18",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(188,283,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_19_20_21",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(214,302,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_22_23_24",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(241,322,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_25_26_27",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(268,342,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_28_29_30",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(296,362,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_31_32_33",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(323,382,s_oSpriteLibrary.getSprite('hit_area_triple_bet'),"bet_34_35_36",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        /******************QUADRUPLE BET******************/
        oBut = new CBetTableButton(31,164,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_0_1_2_3",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(80,158,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_1_2_4_5",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(105,134,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_2_3_5_6",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(104,176,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_4_5_7_8",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(128,151,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_5_6_8_9",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(128,193,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_7_8_10_11",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(152,169,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_8_9_11_12",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(152,211,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_10_11_13_14",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(176,187,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_11_12_14_15",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(176,230,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_13_14_16_17",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(201,205,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_14_15_17_18",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(202,248,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_16_17_19_20",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(227,222,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_17_18_20_21",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(228,267,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_19_20_22_23",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(252,241,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_20_21_23_24",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(254,285,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_22_23_25_26",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(277,260,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_23_24_26_27",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(280,305,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_25_26_28_29",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(304,279,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_26_27_29_30",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(306,324,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_28_29_31_32",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(331,298,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_29_30_32_33",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(333,344,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_31_32_34_35",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(357,317,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_32_33_35_36",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        /****************SESTUPLE BET**********************/
        oBut = new CBetTableButton(54,182,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_1_2_3_4_5_6",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(78,200,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_4_5_6_7_8_9",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(103,218,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_7_8_9_10_11_12",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(128,236,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_10_11_12_13_14_15",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(153,255,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_13_14_15_16_17_18",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(178,274,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_16_17_18_19_20_21",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(204,293,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_19_20_21_22_23_24",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(230,312,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_22_23_24_25_26_27",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(255,332,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_25_26_27_28_29_30",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(282,352,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_28_29_30_31_32_33",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(309,372,s_oSpriteLibrary.getSprite('hit_area_small_circle'),"bet_31_32_33_34_35_36",_oContainer,false);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        /****************COL BET*****************/
        oBut = new CBetTableButton(361,388,s_oSpriteLibrary.getSprite('hit_area_col_bet'),"col1",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(386,361,s_oSpriteLibrary.getSprite('hit_area_col_bet'),"col2",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(410,332,s_oSpriteLibrary.getSprite('hit_area_col_bet'),"col3",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        /****************OTHER BETS******************/
        oBut = new CBetTableButton(-2,240,s_oSpriteLibrary.getSprite('hit_area_other_bet'),"first18",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(47,277,s_oSpriteLibrary.getSprite('hit_area_other_bet'),"even",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(96,316,s_oSpriteLibrary.getSprite('hit_area_other_bet'),"black",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(147,356,s_oSpriteLibrary.getSprite('hit_area_other_bet'),"red",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(197,395,s_oSpriteLibrary.getSprite('hit_area_other_bet'),"odd",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        oBut = new CBetTableButton(253,438,s_oSpriteLibrary.getSprite('hit_area_other_bet'),"second18",_oContainer,true);
        oBut.addEventListener(ON_MOUSE_DOWN, this._onBetPress, this);
        if(s_bMobile === false){
            oBut.addEventListener(ON_MOUSE_OVER, this._onBetNumberOver, this);
            oBut.addEventListener(ON_MOUSE_OUT,this._onBetNumberOut,this);
        }
        
        _aCbCompleted=new Array();
        _aCbOwner =new Array();
    };
	
	this.unload = function(){
		for(var i=0;i<_oContainer.getNumChildren();i++){
			var oBut = _oContainer.getChildAt(i);
			if(oBut instanceof CBetTableButton){
				oBut.unload();
			}
		}
	};
    
    this.addEventListener = function( iEvent,cbCompleted, cbOwner ){
        _aCbCompleted[iEvent]=cbCompleted;
        _aCbOwner[iEvent] = cbOwner; 
    };
    
    this._onBetPress = function(oParams){
        
        var aBets=oParams.numbers;
        if (aBets !== null) {
            if(_aCbCompleted[ON_SHOW_BET_ON_TABLE]){
                _aCbCompleted[ON_SHOW_BET_ON_TABLE].call(_aCbOwner[ON_SHOW_BET_ON_TABLE],oParams,false);
            }
        }
    };
    
    this._onBetNumberOver = function(oParams){
        var aBets=oParams.numbers;
        if(aBets !== null){
            if(_aCbCompleted[ON_SHOW_ENLIGHT]){
                _aCbCompleted[ON_SHOW_ENLIGHT].call(_aCbOwner[ON_SHOW_ENLIGHT],oParams);
            }
        }
    };
    
    this._onBetNumberOut = function(oParams){
        var aBets=oParams.numbers;
        if(aBets !== null){
            if(_aCbCompleted[ON_HIDE_ENLIGHT]){
                _aCbCompleted[ON_HIDE_ENLIGHT].call(_aCbOwner[ON_HIDE_ENLIGHT],oParams);
            }
        }
    };
    
    this._init();
}