function CRouletteSettings(){
    
    var _aFicheValues;
    var _aRedNumbers;
    var _aBlackNumbers;
    var _aFrameForNumbersInAnim;
    var _aFrameForBallSpin;
    var _aAttachFiches;
    
    this._init = function(){
        this._initAttachFiches();
        
        _aFicheValues=new Array(0.1,1,5,10,25,100);
        _aBlackNumbers=new Array(2,4,6,8,10,11,13,15,17,20,22,24,26,28,29,31,33,35);
        _aRedNumbers=new Array(1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36);

        _aFrameForNumbersInAnim = new Array();

        _aFrameForNumbersInAnim[0] = 1;
        _aFrameForNumbersInAnim[1] = 77;
        _aFrameForNumbersInAnim[2] = 169;
        _aFrameForNumbersInAnim[3] = 12;
        _aFrameForNumbersInAnim[4] = 180;
        _aFrameForNumbersInAnim[5] = 99;
        _aFrameForNumbersInAnim[6] = 148;
        _aFrameForNumbersInAnim[7] = 34;
        _aFrameForNumbersInAnim[8] = 115;
        _aFrameForNumbersInAnim[9] = 55;
        _aFrameForNumbersInAnim[10] =104;
        _aFrameForNumbersInAnim[11] =126;
        _aFrameForNumbersInAnim[12] =23;
        _aFrameForNumbersInAnim[13] =137;
        _aFrameForNumbersInAnim[14] =66;
        _aFrameForNumbersInAnim[15] =191;
        _aFrameForNumbersInAnim[16] =88;
        _aFrameForNumbersInAnim[17] =158;
        _aFrameForNumbersInAnim[18] =45;
        _aFrameForNumbersInAnim[19] =185;
        _aFrameForNumbersInAnim[20] =72;
        _aFrameForNumbersInAnim[21] =174;
        _aFrameForNumbersInAnim[22] =50;
        _aFrameForNumbersInAnim[23] =110;
        _aFrameForNumbersInAnim[24] =93;
        _aFrameForNumbersInAnim[25] =164;
        _aFrameForNumbersInAnim[26] =7;
        _aFrameForNumbersInAnim[27] =142;
        _aFrameForNumbersInAnim[28] =28;
        _aFrameForNumbersInAnim[29] =39;
        _aFrameForNumbersInAnim[30] =120;
        _aFrameForNumbersInAnim[31] =61;
        _aFrameForNumbersInAnim[32] =196;
        _aFrameForNumbersInAnim[33] =83;
        _aFrameForNumbersInAnim[34] =153;
        _aFrameForNumbersInAnim[35] =17;
        _aFrameForNumbersInAnim[36] =131;


        _aFrameForBallSpin=new Array();

        _aFrameForBallSpin[0]=new Array();

        _aFrameForBallSpin[0][0]=32;
        _aFrameForBallSpin[0][1]=107;
        _aFrameForBallSpin[0][2]=1;
        _aFrameForBallSpin[0][3]=42;
        _aFrameForBallSpin[0][4]=10;
        _aFrameForBallSpin[0][5]=129;
        _aFrameForBallSpin[0][6]=177;
        _aFrameForBallSpin[0][7]=63;
        _aFrameForBallSpin[0][8]=145;
        _aFrameForBallSpin[0][9]=86;
        _aFrameForBallSpin[0][10]=134;
        _aFrameForBallSpin[0][11]=156;
        _aFrameForBallSpin[0][12]=53;
        _aFrameForBallSpin[0][13]=167;
        _aFrameForBallSpin[0][14]=97;
        _aFrameForBallSpin[0][15]=21;
        _aFrameForBallSpin[0][16]=118;
        _aFrameForBallSpin[0][17]=188;
        _aFrameForBallSpin[0][18]=75;
        _aFrameForBallSpin[0][19]=15;
        _aFrameForBallSpin[0][20]=102;
        _aFrameForBallSpin[0][21]=4;
        _aFrameForBallSpin[0][22]=80;
        _aFrameForBallSpin[0][23]=140;
        _aFrameForBallSpin[0][24]=124;
        _aFrameForBallSpin[0][25]=192;
        _aFrameForBallSpin[0][26]=36;
        _aFrameForBallSpin[0][27]=172;
        _aFrameForBallSpin[0][28]=58;
        _aFrameForBallSpin[0][29]=69;
        _aFrameForBallSpin[0][30]=151;
        _aFrameForBallSpin[0][31]=91;
        _aFrameForBallSpin[0][32]=26;
        _aFrameForBallSpin[0][33]=113;
        _aFrameForBallSpin[0][34]=183;
        _aFrameForBallSpin[0][35]=47;
        _aFrameForBallSpin[0][36]=161;

        _aFrameForBallSpin[1]=new Array();

        _aFrameForBallSpin[1][0]=172;
        _aFrameForBallSpin[1][1]=47;
        _aFrameForBallSpin[1][2]=140;
        _aFrameForBallSpin[1][3]=181;
        _aFrameForBallSpin[1][4]=151;
        _aFrameForBallSpin[1][5]=69;
        _aFrameForBallSpin[1][6]=118;
        _aFrameForBallSpin[1][7]=4;
        _aFrameForBallSpin[1][8]=86;
        _aFrameForBallSpin[1][9]=26;
        _aFrameForBallSpin[1][10]=75;
        _aFrameForBallSpin[1][11]=97;
        _aFrameForBallSpin[1][12]=192;
        _aFrameForBallSpin[1][13]=107;
        _aFrameForBallSpin[1][14]=36;
        _aFrameForBallSpin[1][15]=161;
        _aFrameForBallSpin[1][16]=58;
        _aFrameForBallSpin[1][17]=129;
        _aFrameForBallSpin[1][18]=15;
        _aFrameForBallSpin[1][19]=156;
        _aFrameForBallSpin[1][20]=42;
        _aFrameForBallSpin[1][21]=144;
        _aFrameForBallSpin[1][22]=20;
        _aFrameForBallSpin[1][23]=80;
        _aFrameForBallSpin[1][24]=63;
        _aFrameForBallSpin[1][25]=134;
        _aFrameForBallSpin[1][26]=177;
        _aFrameForBallSpin[1][27]=113;
        _aFrameForBallSpin[1][28]=0;
        _aFrameForBallSpin[1][29]=9;
        _aFrameForBallSpin[1][30]=91;
        _aFrameForBallSpin[1][31]=31;
        _aFrameForBallSpin[1][32]=167;
        _aFrameForBallSpin[1][33]=53;
        _aFrameForBallSpin[1][34]=124;
        _aFrameForBallSpin[1][35]=188;
        _aFrameForBallSpin[1][36]=102;

        _aFrameForBallSpin[2]=new Array();

        _aFrameForBallSpin[2][0]=86;
        _aFrameForBallSpin[2][1]=161;
        _aFrameForBallSpin[2][2]=53;
        _aFrameForBallSpin[2][3]=97;
        _aFrameForBallSpin[2][4]=63;
        _aFrameForBallSpin[2][5]=183;
        _aFrameForBallSpin[2][6]=31;
        _aFrameForBallSpin[2][7]=118;
        _aFrameForBallSpin[2][8]=0;
        _aFrameForBallSpin[2][9]=140;
        _aFrameForBallSpin[2][10]=188;
        _aFrameForBallSpin[2][11]=9;
        _aFrameForBallSpin[2][12]=107;
        _aFrameForBallSpin[2][13]=20;
        _aFrameForBallSpin[2][14]=149;
        _aFrameForBallSpin[2][15]=75;
        _aFrameForBallSpin[2][16]=172;
        _aFrameForBallSpin[2][17]=42;
        _aFrameForBallSpin[2][18]=129;
        _aFrameForBallSpin[2][19]=69;
        _aFrameForBallSpin[2][20]=156;
        _aFrameForBallSpin[2][21]=58;
        _aFrameForBallSpin[2][22]=134;
        _aFrameForBallSpin[2][23]=194;
        _aFrameForBallSpin[2][24]=177;
        _aFrameForBallSpin[2][25]=47;
        _aFrameForBallSpin[2][26]=91;
        _aFrameForBallSpin[2][27]=26;
        _aFrameForBallSpin[2][28]=113;
        _aFrameForBallSpin[2][29]=124;
        _aFrameForBallSpin[2][30]=4;
        _aFrameForBallSpin[2][31]=144;
        _aFrameForBallSpin[2][32]=80;
        _aFrameForBallSpin[2][33]=167;
        _aFrameForBallSpin[2][34]=36;
        _aFrameForBallSpin[2][35]=102;
        _aFrameForBallSpin[2][36]=15;
    };
    
    this._initAttachFiches = function(){
        _aAttachFiches = new Array();
        
        _aAttachFiches['bet_0'] = {x:67,y:-54};
        _aAttachFiches["bet_1"] = {x:68,y:-10};
        _aAttachFiches["bet_2"] = {x:94,y:-36};
        _aAttachFiches["bet_3"] = {x:118,y:-59};
        _aAttachFiches["bet_4"] = {x:92,y:7};
        _aAttachFiches["bet_5"] = {x:118,y:-17};
        _aAttachFiches["bet_6"] = {x:140,y:-42};
        _aAttachFiches["bet_7"] = {x:116,y:25};
        _aAttachFiches["bet_8"] = {x:142,y:-2};
        _aAttachFiches["bet_9"] = {x:165,y:-25};
        _aAttachFiches["bet_10"] = {x:140,y:40};
        _aAttachFiches["bet_11"] = {x:164,y:15};
        _aAttachFiches["bet_12"] = {x:190,y:-8};
        _aAttachFiches["bet_13"] = {x:165,y:59};
        _aAttachFiches["bet_14"] = {x:190,y:34};
        _aAttachFiches["bet_15"] = {x:215,y:11};
        _aAttachFiches["bet_16"] = {x:190,y:80};
        _aAttachFiches["bet_17"] = {x:216,y:53};
        _aAttachFiches["bet_18"] = {x:240,y:28};
        _aAttachFiches["bet_19"] = {x:217,y:98};
        _aAttachFiches["bet_20"] = {x:241,y:72};
        _aAttachFiches["bet_21"] = {x:266,y:46};
        _aAttachFiches["bet_22"] = {x:242,y:118};
        _aAttachFiches["bet_23"] = {x:265,y:92};
        _aAttachFiches["bet_24"] = {x:291,y:64};
        _aAttachFiches["bet_25"] = {x:268,y:137};
        _aAttachFiches["bet_26"] = {x:292,y:110};
        _aAttachFiches["bet_27"] = {x:316,y:84};
        _aAttachFiches["bet_28"] = {x:294,y:156};
        _aAttachFiches["bet_29"] = {x:318,y:129};
        _aAttachFiches["bet_30"] = {x:342,y:102};
        _aAttachFiches["bet_31"] = {x:319,y:175};
        _aAttachFiches["bet_32"] = {x:345,y:149};
        _aAttachFiches["bet_33"] = {x:369,y:121};
        _aAttachFiches["bet_34"] = {x:348,y:197};
        _aAttachFiches["bet_35"] = {x:373,y:169};
        _aAttachFiches["bet_36"] = {x:396,y:141};
        
        _aAttachFiches["bet_0_1"] = {x:59,y:-20};
        _aAttachFiches["bet_0_2"] = {x:84,y:-43};
        _aAttachFiches["bet_0_3"] = {x:109,y:-66};
        _aAttachFiches["bet_1_4"] = {x:82,y:-1};
        _aAttachFiches["bet_2_5"] = {x:106,y:-24};
        _aAttachFiches["bet_3_6"] = {x:129,y:-49};
        _aAttachFiches["bet_4_7"] = {x:106,y:16};
        _aAttachFiches["bet_5_8"] = {x:130,y:-6};
        _aAttachFiches["bet_6_9"] = {x:154,y:-33};
        _aAttachFiches["bet_7_10"] = {x:128,y:35};
        _aAttachFiches["bet_8_11"] = {x:155,y:11};
        _aAttachFiches["bet_9_12"] = {x:179,y:-16};
        _aAttachFiches["bet_10_13"] = {x:153,y:53};
        _aAttachFiches["bet_11_14"] = {x:179,y:29};
        _aAttachFiches["bet_12_15"] = {x:203,y:4};
        _aAttachFiches["bet_13_16"] = {x:179,y:71};
        _aAttachFiches["bet_14_17"] = {x:201,y:45};
        _aAttachFiches["bet_15_18"] = {x:225,y:21};
        _aAttachFiches["bet_16_19"] = {x:203,y:90};
        _aAttachFiches["bet_17_20"] = {x:228,y:64};
        _aAttachFiches["bet_18_21"] = {x:252,y:40};
        _aAttachFiches["bet_19_22"] = {x:230,y:109};
        _aAttachFiches["bet_20_23"] = {x:252,y:83};
        _aAttachFiches["bet_21_24"] = {x:277,y:57};
        _aAttachFiches["bet_22_25"] = {x:255,y:128};
        _aAttachFiches["bet_23_26"] = {x:278,y:102};
        _aAttachFiches["bet_24_27"] = {x:302,y:76};
        _aAttachFiches["bet_25_28"] = {x:282,y:148};
        _aAttachFiches["bet_26_29"] = {x:306,y:120};
        _aAttachFiches["bet_27_30"] = {x:328,y:94};
        _aAttachFiches["bet_28_31"] = {x:309,y:167};
        _aAttachFiches["bet_29_32"] = {x:332,y:140};
        _aAttachFiches["bet_30_33"] = {x:354,y:112};
        _aAttachFiches["bet_31_34"] = {x:334,y:188};
        _aAttachFiches["bet_32_35"] = {x:358,y:160};
        _aAttachFiches["bet_33_36"] = {x:382,y:132};
        
        _aAttachFiches["bet_1_2"] = {x:81,y:-22};
        _aAttachFiches["bet_2_3"] = {x:107,y:-46};
        _aAttachFiches["bet_4_5"] = {x:105,y:-4};
        _aAttachFiches["bet_5_6"] = {x:129,y:-30};
        _aAttachFiches["bet_7_8"] = {x:127,y:12};
        _aAttachFiches["bet_8_9"] = {x:154,y:-12};
        _aAttachFiches["bet_10_11"] = {x:153,y:30};
        _aAttachFiches["bet_11_12"] = {x:178,y:5};
        _aAttachFiches["bet_13_14"] = {x:178,y:47};
        _aAttachFiches["bet_14_15"] = {x:202,y:22};
        _aAttachFiches["bet_16_17"] = {x:203,y:65};
        _aAttachFiches["bet_17_18"] = {x:227,y:40};
        _aAttachFiches["bet_19_20"] = {x:230,y:84};
        _aAttachFiches["bet_20_21"] = {x:252,y:59};
        _aAttachFiches["bet_22_23"] = {x:256,y:103};
        _aAttachFiches["bet_23_24"] = {x:278,y:77};
        _aAttachFiches["bet_25_26"] = {x:281,y:122};
        _aAttachFiches["bet_26_27"] = {x:303,y:96};
        _aAttachFiches["bet_28_29"] = {x:307,y:141};
        _aAttachFiches["bet_29_30"] = {x:330,y:115};
        _aAttachFiches["bet_31_32"] = {x:333,y:161};
        _aAttachFiches["bet_32_33"] = {x:356,y:135};
        _aAttachFiches["bet_34_35"] = {x:359,y:181};
        _aAttachFiches["bet_35_36"] = {x:383,y:154};
        
        _aAttachFiches["bet_0_1_2"] = {x:69,y:-33};
        _aAttachFiches["bet_0_2_3"] = {x:97,y:-58};
        _aAttachFiches["bet_1_2_3"] = {x:57,y:1};
        _aAttachFiches["bet_4_5_6"] = {x:79,y:19};
        _aAttachFiches["bet_7_8_9"] = {x:105,y:36};
        _aAttachFiches["bet_10_11_12"] = {x:128,y:55};
        _aAttachFiches["bet_13_14_15"] = {x:153,y:73};
        _aAttachFiches["bet_16_17_18"] = {x:179,y:93};
        _aAttachFiches["bet_19_20_21"] = {x:205,y:110};
        _aAttachFiches["bet_22_23_24"] = {x:230,y:129};
        _aAttachFiches["bet_25_26_27"] = {x:257,y:149};
        _aAttachFiches["bet_28_29_30"] = {x:282,y:169};
        _aAttachFiches["bet_31_32_33"] = {x:307,y:191};
        _aAttachFiches["bet_34_35_36"] = {x:337,y:210};
        
        _aAttachFiches["bet_0_1_2_3"] = {x:43,y:-7};
        _aAttachFiches["bet_1_2_4_5"] = {x:93,y:-15};
        _aAttachFiches["bet_2_3_5_6"] = {x:119,y:-38};
        _aAttachFiches["bet_4_5_7_8"] = {x:119,y:3};
        _aAttachFiches["bet_5_6_8_9"] = {x:143,y:-21};
        _aAttachFiches["bet_7_8_10_11"] = {x:142,y:20};
        _aAttachFiches["bet_8_9_11_12"] = {x:167,y:-3};
        _aAttachFiches["bet_10_11_13_14"] = {x:167,y:38};
        _aAttachFiches["bet_11_12_14_15"] = {x:191,y:14};
        _aAttachFiches["bet_13_14_16_17"] = {x:192,y:57};
        _aAttachFiches["bet_14_15_17_18"] = {x:216,y:32};
        _aAttachFiches["bet_16_17_19_20"] = {x:216,y:76};
        _aAttachFiches["bet_17_18_20_21"] = {x:240,y:49};
        _aAttachFiches["bet_19_20_22_23"] = {x:242,y:95};
        _aAttachFiches["bet_20_21_23_24"] = {x:266,y:68};
        _aAttachFiches["bet_22_23_25_26"] = {x:266,y:114};
        _aAttachFiches["bet_23_24_26_27"] = {x:292,y:86};
        _aAttachFiches["bet_25_26_28_29"] = {x:292,y:133};
        _aAttachFiches["bet_26_27_29_30"] = {x:318,y:105};
        _aAttachFiches["bet_28_29_31_32"] = {x:318,y:153};
        _aAttachFiches["bet_29_30_32_33"] = {x:345,y:125};
        _aAttachFiches["bet_31_32_34_35"] = {x:347,y:172};
        _aAttachFiches["bet_32_33_35_36"] = {x:372,y:144};
        
        _aAttachFiches["bet_1_2_3_4_5_6"] = {x:68,y:8};
        _aAttachFiches["bet_4_5_6_7_8_9"] = {x:93,y:25};
        _aAttachFiches["bet_7_8_9_10_11_12"] = {x:118,y:43};
        _aAttachFiches["bet_10_11_12_13_14_15"] = {x:142,y:63};
        _aAttachFiches["bet_13_14_15_16_17_18"] = {x:166,y:80};
        _aAttachFiches["bet_16_17_18_19_20_21"] = {x:192,y:99};
        _aAttachFiches["bet_19_20_21_22_23_24"] = {x:217,y:118};
        _aAttachFiches["bet_22_23_24_25_26_27"] = {x:244,y:138};
        _aAttachFiches["bet_25_26_27_28_29_30"] = {x:270,y:158};
        _aAttachFiches["bet_28_29_30_31_32_33"] = {x:297,y:179};
        _aAttachFiches["bet_31_32_33_34_35_36"] = {x:324,y:200};
        
        _aAttachFiches["col1"] = {x:375,y:216};
        _aAttachFiches["col2"] = {x:399,y:187};
        _aAttachFiches["col3"] = {x:423,y:161};
        
        _aAttachFiches["first12"] = {x:70,y:45};
        _aAttachFiches["second12"] = {x:170,y:123};
        _aAttachFiches["third12"] = {x:280,y:203};
        _aAttachFiches["first18"] = {x:8,y:68};
        _aAttachFiches["even"] = {x:55,y:104};
        _aAttachFiches["black"] = {x:107,y:142};
        _aAttachFiches["red"] = {x:160,y:185};
        _aAttachFiches["odd"] = {x:212,y:225};
        _aAttachFiches["second18"] = {x:263,y:267};
        
        _aAttachFiches["oDealerWin"] = {x:105,y:-232};
        _aAttachFiches["oReceiveWin"] = {x:215,y:428};
    };
    
    this.generateFichesPileByIndex = function(iFichesValue){
        
            var aFichesPile=new Array();
            var iValueRest;
            var iCont=_aFicheValues.length-1;
            var iCurMaxFicheStake=_aFicheValues[iCont];
            
            do{     
                    iValueRest=iFichesValue%iCurMaxFicheStake;
                    iValueRest=roundDecimal(iValueRest,1);
                    //var iDivisionWithPrecision=roundDecimal((iFichesValue/iCurMaxFicheStake),1);
                    var iDivisionWithPrecision=iFichesValue/iCurMaxFicheStake;
                    var iDivision=Math.floor(iDivisionWithPrecision);
                    for(var i=0;i<iDivision;i++){
                            aFichesPile.push(this.getFicheIndexByValue(iCurMaxFicheStake));
                    }

                    iCont--;
                    iCurMaxFicheStake=_aFicheValues[iCont];
                    iFichesValue=iValueRest;
            }while(iValueRest>0 && iCont>-1);

            return aFichesPile;
    };
		
    this.getNumbersForButton = function(szName){
        var aNumbers;
        switch(szName){
                case "col1":{
                        aNumbers=new Array(1,4,7,10,13,16,19,22,25,28,31,34);
                        break;
                }

                case "col2":{
                        aNumbers=new Array(2,5,8,11,14,17,20,23,26,29,32,35);
                        break;
                }

                case "col3":{
                        aNumbers=new Array(3,6,9,12,15,18,21,24,27,30,33,36);
                        break;
                }

                case "first12":{
                        aNumbers=new Array(1,2,3,4,5,6,7,8,9,10,11,12);
                        break;
                }

                case "second12":{
                        aNumbers=new Array(13,14,15,16,17,18,19,20,21,22,23,24);
                        break;
                }

                case "third12":{
                        aNumbers=new Array(25,26,27,28,29,30,31,32,33,34,35,36);
                        break;
                }

                case "first18":{
                        aNumbers=new Array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18);
                        break;
                }

                case "second18":{
                        aNumbers=new Array(19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36);
                        break;
                }		

                case "even":{
                        aNumbers=new Array(2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36);
                        break;
                }

                case "black":{
                        aNumbers=new Array(2,4,6,8,10,11,13,15,17,20,22,24,26,28,29,31,33,35);
                        break;
                }

                case "red":{
                        aNumbers=new Array(1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36);
                        break;
                }

                case "odd":{

                        aNumbers=new Array(1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,35);
                        break;
                }

                case "oBetVoisinsZero":{
                        aNumbers=new Array(22,18,29,7,28,12,35,3,26,0,32,15,19,4,21,2,25);
                        break;
                }

                case "oBetTier":{
                        aNumbers=new Array(27,13,36,11,30,8,23,10,5,24,16,33);
                        break;
                }

                case "oBetOrphelins":{
                        aNumbers=new Array(1,6,9,14,17,20,31,34);
                        break;
                }

                case "oBetFinalsBet":{

                        break;
                }
        }
        return aNumbers;
    };
		
    this.getBetMultiplierForButton = function(szName){
        var iBetMultiplier;
        switch(szName){
            case "oBetFirstRow":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetSecondRow":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetThirdRow":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetFirst12":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetSecond12":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetThird12":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetFirst18":{
                    iBetMultiplier=18;
                    break;
            }

            case "oBetSecond18":{
                    iBetMultiplier=18;
                    break;
            }		

            case "oBetEven":{
                    iBetMultiplier=18;
                    break;
            }

            case "oBetBlack":{
                    iBetMultiplier=18;
                    break;
            }

            case "oBetRed":{
                    iBetMultiplier=18;
                    break;
            }

            case "oBetOdd":{
                    iBetMultiplier=18;
                    break;
            }

            case "oBetVoisinsZero":{
                    iBetMultiplier=17;
                    break;
            }

            case "oBetTier":{
                    iBetMultiplier=12;
                    break;
            }

            case "oBetOrphelins":{
                    iBetMultiplier=8;
                    break;
            }

            case "oBetFinalsBet":{
                    iBetMultiplier=4;
                    break;
            }
        }

        return iBetMultiplier;
    };
		
    this.getBetWinForButton = function(szName){
            var iBetWin;
            switch(szName){
                    case "oBetFirstRow":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetSecondRow":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetThirdRow":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetFirst12":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetSecond12":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetThird12":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetFirst18":{
                            iBetWin=2;
                            break;
                    }

                    case "oBetSecond18":{
                            iBetWin=2;
                            break;
                    }		

                    case "oBetEven":{
                            iBetWin=2;
                            break;
                    }

                    case "oBetBlack":{
                            iBetWin=2;
                            break;
                    }

                    case "oBetRed":{
                            iBetWin=2;
                            break;
                    }

                    case "oBetOdd":{
                            iBetWin=2;
                            break;
                    }

                    case "oBetVoisinsZero":{
                            iBetWin=2;
                            break;
                    }

                    case "oBetTier":{
                            iBetWin=3;
                            break;
                    }

                    case "oBetOrphelins":{
                            iBetWin=4;
                            break;
                    }

                    case "oBetFinalsBet":{
                            iBetWin=4;
                            break;
                    }
            }

            return iBetWin;
    };
    
    this.getNumFichesPerBet = function(szName){
        var iNumFiches;
        switch(szName){
            case "oBetVoisinsZero":{
                    iNumFiches=9;
                    break;
            }

            case "oBetTier":{
                    iNumFiches=6;
                    break;
            }

            case "oBetOrphelins":{
                    iNumFiches=5;
                    break;
            }
        }

        return iNumFiches;
    };
		
    this.getFicheValues = function(iIndex){
            return _aFicheValues[iIndex];
    };
		
    this.getFicheIndexByValue = function(iValue){
            var iIndex=0;
            for(var i=0;i<_aFicheValues.length;i++){
                    if(iValue === _aFicheValues[i]){
                            iIndex=i;
                            break;
                    }
            }
            return iIndex;
    };
		
    this.getColorNumber = function(iNumber){
            var i=0;
            for(i=0;i<_aBlackNumbers.length;i++){
                if(_aBlackNumbers[i] === iNumber){
                        return COLOR_BLACK;
                }
            }

            for(i=0;i<_aRedNumbers.length;i++){
                if(_aRedNumbers[i] === iNumber){
                        return COLOR_RED;
                }
            }

            return COLOR_ZERO;
    };
		
    this.getFrameForNumber = function(iNumber) {
        return _aFrameForNumbersInAnim[iNumber];
    };
		
    this.getFrameForBallSpin = function(iType,iNum){
            return _aFrameForBallSpin[iType][iNum];
    };
    
    this.getAttachOffset = function(szAttach){
        return _aAttachFiches[szAttach];
    };
    
    this._init();
}