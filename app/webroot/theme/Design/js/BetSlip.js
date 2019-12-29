var BetSlip = {

    BetSlipUrl          :   null,
    BetSlipContainer    :   '#bet-slip-container-html',
    ErrorContainer      :   '#bet-slip-container-html',

    refreshBetSlip : function() {
        $(BetSlip.BetSlipContainer).html('');
        $('.roll.betslip').show();
        BetSlip.loadBetSlip('');
    },

    flushBetSlip : function () {
        $(BetSlip.BetSlipContainer).html('');
        $('.roll.betslip').show();
        $.get(BetSlip.BetSlipUrl, { "action" : "flush" }, function(response,status) {
            $(BetSlip.BetSlipContainer).html(response);
        }).done(function(data) {
            $('.roll.betslip').hide();
        });
    },

    loadBetSlip : function(url) {
        if(BetSlip.BetSlipUrl == null) { BetSlip.BetSlipUrl = url; }

        $.get(BetSlip.BetSlipUrl, {}, function(response,status) {
            $(BetSlip.BetSlipContainer).html(response);
        }).done(function(data) {
            $('.roll.betslip').hide();
            $('#bestlip_errors').filter(function() {
                return $.trim($(this).text()) === ''
            }).hide();
        });
    }
};

