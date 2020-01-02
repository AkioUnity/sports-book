function CHandEvaluator(){
    var _aOrigSortedHand;
    var _aSortedHand;
    var _aCardIndexInCombo;
    
    this.evaluate = function(aHand){
        _aSortedHand = new Array();
        _aOrigSortedHand = new Array();
        for(var i=0;i<aHand.length;i++){
            _aSortedHand[i] = {rank:aHand[i].rank,suit:aHand[i].suit};
            _aOrigSortedHand[i] = {rank:aHand[i].rank,suit:aHand[i].suit};
        }
        
        _aSortedHand.sort(this.compareRank);
        _aOrigSortedHand.sort(this.compareRank);
        
        _aCardIndexInCombo = new Array(0,1,2,3,4);

        return {ret:this.rankHand(),sort_hand:_aOrigSortedHand};
    };
    
    this.rankHand = function(){
        if(this._checkForRoyalFlush()){
            return ROYAL_FLUSH;
        }else if(this._checkForStraightFlush()){
            return STRAIGHT_FLUSH;
        }else if(this._checkForFourOfAKind()){
            return FOUR_OF_A_KIND;
        }else if(this._checkForFullHouse()){
            return FULL_HOUSE;
        }else if(this._checkForFlush()){
            return FLUSH;
        }else if(this._checkForStraight()){
            return STRAIGHT;
        }else if(this._checkForThreeOfAKind()){
            return THREE_OF_A_KIND;
        }else if(this._checkForTwoPair()){
            return TWO_PAIR;
        }else if(this._checkForOnePair()){
            return ONE_PAIR;
        }else if(this._checkHighCard()){
            return HIGH_CARD;
        }else{
            return NO_HAND;
        }
    };
    
    this._checkForRoyalFlush = function(){
        if(this._isRoyalStraight() && this._isFlush()){
            
            return true;
        }else{
            return false;
        }
     };

    this._checkForStraightFlush = function(){
        if(this._isStraight() && this._isFlush()){
            return true;
        }else {
            return false;
        }
    };

    this._checkForFourOfAKind = function(){
        if(_aSortedHand[0].rank === _aSortedHand[3].rank){
            _aSortedHand.splice(4,1);
            _aCardIndexInCombo.splice(4,1);
            return true;
        }else if(_aSortedHand[1].rank === _aSortedHand[4].rank){
            _aSortedHand.splice(0,1);
            _aCardIndexInCombo.splice(0,1);
            return true;
        }else{
            return false;
        }
    };

    this._checkForFullHouse = function(){
        if((_aSortedHand[0].rank === _aSortedHand[1].rank && _aSortedHand[2].rank === _aSortedHand[4].rank) || 
                                                                                            (_aSortedHand[0].rank === _aSortedHand[2].rank
                                                                                                        && _aSortedHand[3].rank === _aSortedHand[4].rank)){
            return true;
        }else{
            return false;
        }
    };

    this._checkForFlush = function(){
        if(this._isFlush()){
            return true;
        } else{
            return false;
        }
    };

    this._checkForStraight = function(){
        if(this._isStraight()){
            return true;
        } else{
            return false;
        }
     };

    this._checkForThreeOfAKind = function() {
        if(_aSortedHand[0].rank === _aSortedHand[1].rank && _aSortedHand[0].rank === _aSortedHand[2].rank){
            _aSortedHand.splice(3,1);
            _aSortedHand.splice(3,1);
            //_aSortedHand.splice(4,1);
            _aCardIndexInCombo.splice(3,1);
            _aCardIndexInCombo.splice(3,1);
            return true;
        } else if(_aSortedHand[1].rank === _aSortedHand[2].rank && _aSortedHand[1].rank === _aSortedHand[3].rank){
            _aSortedHand.splice(0,1);
            _aSortedHand.splice(3,1);
            //_aSortedHand.splice(4,1);
            _aCardIndexInCombo.splice(0,1);
            _aCardIndexInCombo.splice(3,1);

            return true;
        }else if(_aSortedHand[2].rank === _aSortedHand[3].rank && _aSortedHand[2].rank === _aSortedHand[4].rank){
            _aSortedHand.splice(0,1);
            _aSortedHand.splice(0,1);
            //_aSortedHand.splice(1,1);
            _aCardIndexInCombo.splice(0,1);
            _aCardIndexInCombo.splice(0,1);
            return true;
        }else{
            return false;
        }
    };

    this._checkForTwoPair = function(){
        if(_aSortedHand[0].rank === _aSortedHand[1].rank && _aSortedHand[2].rank === _aSortedHand[3].rank){
            _aSortedHand.splice(4,1);
            _aCardIndexInCombo.splice(4,1);
            return true;
        }else if(_aSortedHand[1].rank === _aSortedHand[2].rank && _aSortedHand[3].rank === _aSortedHand[4].rank){
            _aSortedHand.splice(0,1);
            _aCardIndexInCombo.splice(0,1);
            return true;
        }else if(_aSortedHand[0].rank === _aSortedHand[1].rank && _aSortedHand[3].rank === _aSortedHand[4].rank){
            _aSortedHand.splice(2,1);
            _aCardIndexInCombo.splice(2,1);
            return true;
        } else{
            return false;
        }
    };

    this._checkForOnePair = function(){
        for(var i = 0; i < 4; i++){
            if(_aSortedHand[i].rank === _aSortedHand[i + 1].rank){
                var p1 = _aSortedHand[i];
                var p2 = _aSortedHand[i + 1];
                _aSortedHand = new Array();
                _aSortedHand.push(p1);
                _aSortedHand.push(p2);
                
                _aCardIndexInCombo = new Array(i,i+1);
                return true;
            }
        }

        return false;
    };

    this._checkHighCard = function(){
        var bAceFound = false;
        var bKingFound = false;
        for(var i = 0; i < 5; i++){
            if(_aSortedHand[i].rank === CARD_ACE){
                bAceFound = true;
            }
            
            if(_aSortedHand[i].rank === CARD_KING){
                bKingFound = true;
            }
        }

        if(bAceFound || bKingFound){
            return true;
        }else{
            return false;
        }
    };
    
    this._isFlush = function(){
        if(_aSortedHand[0].suit === _aSortedHand[1].suit
            && _aSortedHand[0].suit === _aSortedHand[2].suit
            && _aSortedHand[0].suit === _aSortedHand[3].suit
            && _aSortedHand[0].suit === _aSortedHand[4].suit){
            return true;
        }else{
            return false;
        }
    };

    this._isRoyalStraight = function(){
        if(_aSortedHand[0].rank === CARD_TEN
            && _aSortedHand[1].rank === CARD_JACK
            && _aSortedHand[2].rank === CARD_QUEEN
            && _aSortedHand[3].rank === CARD_KING
            && _aSortedHand[4].rank === CARD_ACE){
            return true;
        }else{
            return false;
        }
    };

    this._isStraight = function(){
        var bFirstFourStraight = _aSortedHand[0].rank + 1 === _aSortedHand[1].rank && _aSortedHand[1].rank + 1 === _aSortedHand[2].rank
                                                    && _aSortedHand[2].rank + 1 === _aSortedHand[3].rank;

        if(bFirstFourStraight && _aSortedHand[0].rank === CARD_TWO && _aSortedHand[4].rank === CARD_ACE){
            return true;
        }else if(bFirstFourStraight && _aSortedHand[3].rank + 1 === _aSortedHand[4].rank){
            return true;
        } else{
            return false;
        }
    };
    
    this.compareRank = function(a,b) {
        if (a.rank < b.rank)
           return -1;
        if (a.rank > b.rank)
          return 1;
        return 0;
    };

    this.getWinnerComparingHands = function(aHandPlayer,aHandDealer,iHandPlayerValue,iHandDealerValue){
        if(iHandPlayerValue === iHandDealerValue){
            switch(iHandPlayerValue){
                case STRAIGHT_FLUSH:{
                        if(aHandPlayer[0].suit > aHandDealer[0].suit){
                            return "dealer";
                        }else if(aHandPlayer[0].suit < aHandDealer[0].suit){
                            return "player";
                        }else{
                            return "standoff";
                        }
                }
                case FOUR_OF_A_KIND:{
                        if(aHandPlayer[1].rank > aHandDealer[1].rank){
                            return "player"
                        }else if(aHandPlayer[1].rank < aHandDealer[1].rank){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case FULL_HOUSE:{
                        if(aHandPlayer[4].rank > aHandDealer[4].rank){
                            return "player";
                        }else if(aHandPlayer[4].rank < aHandDealer[4].rank){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case FLUSH:{
                        if(aHandPlayer[0].suit > aHandDealer[0].suit){
                            return "dealer";
                        }else if(aHandPlayer[0].suit < aHandDealer[0].suit){
                            return "player";
                        }else{
                            return "standoff";
                        }
                }
                case STRAIGHT:{
                        if(aHandPlayer[4].rank > aHandDealer[4].rank){
                            return "player";
                        }else if(aHandPlayer[4].rank < aHandDealer[4].rank){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case THREE_OF_A_KIND:{
                        if(aHandPlayer[2].rank > aHandDealer[2].rank){
                            return "player";
                        }else if(aHandPlayer[2].rank < aHandDealer[2].rank){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case TWO_PAIR:{
                       var iValue1 = 0;
                        for(var i=aHandPlayer.length-1;i>0;i--){
                            if(aHandPlayer[i].rank === aHandPlayer[i-1].rank){
                                iValue1 = aHandPlayer[i].rank;
                                break;
                            }
                        }
                        
                        var iValue2 = 0;
                        for(var i=aHandDealer.length-1;i>0;i--){
                            if(aHandDealer[i].rank === aHandDealer[i-1].rank){
                                iValue2 = aHandDealer[i].rank;
                                break;
                            }
                        } 

                        if(iValue1 > iValue2){
                            return "player";
                        }else if (iValue1 < iValue2){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case ONE_PAIR:{
                        var iValue1 = 0;
                        for(var i=0;i<aHandPlayer.length-1;i++){
                            if(aHandPlayer[i].rank === aHandPlayer[i+1].rank){
                                iValue1 = aHandPlayer[i].rank;
                                break;
                            }
                        }
                        
                        var iValue2 = 0;
                        for(var i=0;i<aHandDealer.length-1;i++){
                            if(aHandDealer[i].rank === aHandDealer[i+1].rank){
                                iValue2 = aHandDealer[i].rank;
                                break;
                            }
                        }

                        if(iValue1 > iValue2){
                            return "player";
                        }else if (iValue1 < iValue2){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case NO_HAND:{
                        
                        break;
                }
                default:{
                        return "standoff";
                }
            }
        }else{
            if(iHandDealerValue === NO_HAND){
                return "dealer_no_hand";
            }
            
            return iHandPlayerValue>iHandDealerValue?"dealer":"player";
        }
    };

}