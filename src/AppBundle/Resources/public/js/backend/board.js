function currentAudience() {

    $.getJSON('/ajax/currentaudience.json', function (data) {

        console.log(data);

        var listeners = data.currentaudience;
        var maxListeners = 100;

        $("#nb-auditeurs").text(listeners);
        $("#max-auditeurs").text(maxListeners);
        $("#max-auditeurs-progress").css("width", parseInt(listeners / maxListeners * 100) + '%');

        setTimeout(currentAudience, data.callback * 1000);
    });
}

$(function () {
    currentAudience();
});