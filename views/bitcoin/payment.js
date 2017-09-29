/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

/**
 * set BTC address back again
 */
function setAddress() {
    var addr = $("#btc-address");
    addr.html(addr.data('addr'));
}

/**
 * check payment status
 */
function checkPayment() {
    var url = $("#check-payment-url").data("check-payment-url");
    $.getJSON(url)
        .done(function (response) {
            /** @namespace response.payment_received */
            if(response.payment_received===true){
                $("#payment-waiting").addClass("hidden");
                $("#btc-info").addClass("hidden");
                $("#payment-received").removeClass("hidden");
            }
        });
}
checkPayment();

/**
 * copy BTC address on click
 */
$("#copy-tab").on("click", "#btc-address", function () {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(this).data('addr')).select();
    document.execCommand("copy");
    $temp.remove();
    $(this).html($(this).data('copied'));
    setTimeout(setAddress, 2000);
});

/**
 * check payment every 10s
 */
setInterval(checkPayment, 10000);

/* count down */
var $clock = $("#count-down"),
    addrTime = $("#btc-address").data('date'), // seconds
    currentTime = moment().unix(), // seconds
    diffTime = currentTime - addrTime, // seconds
    timeout = $("#btc-info").data('timeout'), // seconds
    duration = moment.duration((timeout - diffTime) * 1000, 'milliseconds'),
    interval = 1000;

var $m = $('<span class="minutes">--</span>').appendTo($clock);
$('<span class="">:</span>').appendTo($clock);
var $s = $('<span class="seconds">--</span>').appendTo($clock);

setInterval(function () {
    duration = moment.duration(duration.asMilliseconds() - interval, 'milliseconds');
    var m = moment.duration(duration).minutes(),
        s = moment.duration(duration).seconds();

    m = $.trim(m).length === 1 ? '0' + m : m;
    s = $.trim(s).length === 1 ? '0' + s : s;

    // show how many hours, minutes and seconds are left
    if (moment.duration(duration).asSeconds() >= 0) {
        if (moment.duration(duration).asSeconds() < 120) {
            $("#count-down").addClass("text-danger");
        }
        $m.text(m);
        $s.text(s);
    }
    else {
        $("#btc-info").hide();
        $("#btc-expired").removeClass('hidden');
    }

}, interval);




