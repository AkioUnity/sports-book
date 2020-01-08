var Ticket = {

    TicketUrl :     null,
    isAJax     :    '/',

    setAjax : function() {
        Ticket.isAJax = '/ajax';
    },

    setUrl : function(url) {
        Ticket.TicketUrl = url;
    },

    setStake : function(obj) {
        $('.roll.betslip').show();
        $('#bet-slip-container').slideUp();
        $.get(Ticket.TicketUrl + '/setStake/', { 'stake': obj.val() }, function( response, status ) {
            if(response.data.status == "ok") {
                $(BetSlip.ErrorContainer).find('.error-log').hide('slow');
                $(BetSlip.BetSlipContainer).find('input[name="possible-winning"]').val(response.data.possible_wining);
            }else{
                $(BetSlip.ErrorContainer).find('.error-log').text(response.data.message);
                $(BetSlip.ErrorContainer).find('.error-log').show('slow');
            }
        }, "json").done(function(data) {
            $('.roll.betslip').hide();
            $('#bet-slip-container').slideDown();
        });
    },

    delayCall   :   function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    }, // delayCall

    addBet : function(betId) {
        let item=$("#"+betId);
        if (item.hasClass('current')){
            console.log("remove bet"+betId);
            item.removeClass('current');
            Ticket.removeBet(betId);
            return;
        }
        let parent=item.parent();
        parent.children().removeClass('current');
        item.addClass('current');
        $('.roll.betslip').show();
        $('#bet-slip-container').slideUp();
        jQuery('#bet-slip-container-html').load(Ticket.TicketUrl + '/addBet/' + betId + Ticket.isAJax, function() {
            $('.roll.betslip').hide();
            $('#bet-slip-container').slideDown();
        });
    },

    removeBet:function( betId, obj )
    {
        $('.roll.betslip').show();
        $('#bet-slip-container').slideUp();
        jQuery('#bet-slip-container-html').load(Ticket.TicketUrl + '/removeBet/' + betId + Ticket.isAJax, function() {
            $('.roll.betslip').hide();
            $('#bet-slip-container').slideDown();
        });
    },

    searchTicket: function( url )
    {
        $('.searchTicket').click(function()
        {
            var Form = $('form#TicketSearchForm');
            var ResponseContainer = $('#TicketSearchForm-Response');

            if(Form.serialize() != 'TicketId=') {
                $.get(url, Form.serialize(), function( response, status ) {

                    ResponseContainer.addClass('visible');

                    if(response.data.Ticket.response != 404) {
                        ResponseContainer.find('#ticket-id').text(Form.find('input[name="TicketId"]').val());
                        ResponseContainer.find('#ticket-state').text(response.data.Ticket.status);
                        ResponseContainer.find('#ticket-paid').text(response.data.Ticket.paid);

                        if(response.data.Ticket.status != 'Won' && response.data.Ticket.status != 'Cancelled') {
                            ResponseContainer.find('#ticket-paid').parent().css({'display' : 'none'});
                        }else{
                            ResponseContainer.find('#ticket-paid').parent().css({'display' : 'block'});
                        }

                        $('a.viewTicket').attr('href', $('a.viewTicket').attr('data-url') + '/' + response.data.Ticket.id).css({'display' : 'block'});

                        if(!ResponseContainer.find('.result').is(':visible')) {
                            ResponseContainer.find('.result').slideDown();
                        }
                        ResponseContainer.find('.error-log').text('');
                    }
                    else{
                        if(ResponseContainer.find('.result').is(':visible')) {
                            ResponseContainer.find('.result').slideUp();
                        }
                        ResponseContainer.find('.error-log').text(response.data.Ticket.message);
                        $('a.viewTicket').css({'display' : 'none'});
                    }

                    if((response.data.Ticket.status == 'Won' || response.data.Ticket.status == 'Cancelled') && response.data.Ticket.paid == 'Unpaid') {
                        ResponseContainer.find('a.payTicket').css({'display' : 'block'});
                    }else{
                        ResponseContainer.find('a.payTicket').css({'display' : 'none'});
                    }
                });
            }
        });
    },

    placeTicket : function ( url ) {
        $(document).on('click', '#bet-slip-container a.ajax.submit-bet', function()
        {
                $.get(url, {  }, function( response, status ) {
                    if(response.data.response == 'ok') {
                        $(BetSlip.ErrorContainer).find('.error-log').hide('slow');
                        BetSlip.refreshBetSlip();
                        Ticket.ticketsHistory();
                    }else{
                        $(BetSlip.ErrorContainer).find('.error-log').text(response.data.message);
                        $(BetSlip.ErrorContainer).find('.error-log').show('slow');

                    }

                }, "json");

            return false;
        });
    },

    payForTicket: function( url, ticketId ) {
        $('.payTicket').click(function()
        {
            var Form = $('form#TicketSearchForm');
            var ResponseContainer = $('#TicketSearchForm-Response');

            $.get(url, {'TicketId' : ResponseContainer.find('.ticket #ticket-id').text()}, function(response, status) {
                if(response.data.response == 'ok') {
                    ResponseContainer.find('.status #ticket-paid').text(response.data.status);
                    ResponseContainer.find('a.payTicket').css({'display' : 'none'});

                    $('.operator-box .lhold1 span.current-balance span.value').text(response.data.balance);

                }else{
                    ResponseContainer.find('.error-log').text(response.data.message);
                }
            }, "json");
        });
    },

    ticketsHistory : function() {
        var HistoryContainer = $('#ticketsHistory-block');
        HistoryContainer.html('').addClass('loading');
        HistoryContainer.load(Ticket.TicketUrl + '/getHistory/', function() {
            HistoryContainer.removeClass('loading');
        });
    }
};
$(document).ready(function(){
    $(document.body).on('click', '.addBet', function(){
        Ticket.addBet($(this).attr('id'));
    });

    var typingTimer;                //timer identifier
    var doneTypingInterval = 1000;  //time in ms, 5 second for example

    $(document.body).on('keyup', '#total-stake', function(){
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $(document.body).on('keydown', '#total-stake', function(){
        clearTimeout(typingTimer);
    });

    function doneTyping () {
        Ticket.setStake($('input[id="total-stake"]'));
    }

    $(document.body).on('click', "a#betslip-place", function()
    {
        var href = $(this).attr('href');
        var types = $(document).find('form[name="betslip"] input[name="type[]"]').map(function(idx, elem) {
            return $(elem).val();
        }).get();

        if (types.indexOf("2") != -1)
        {
            if (this.clicked) return false;

            console.log("qq");
            $('#bet-slip-container .loading').show();

            event.preventDefault();

            this.clicked = true;

            setTimeout(function() {
                window.location.href = href;
            }, 5000);
        }
    });
});

