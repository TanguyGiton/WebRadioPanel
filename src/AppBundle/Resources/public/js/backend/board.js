function currentAudience() {
    var $url = Routing.generate('currentaudience');
    $.getJSON($url, function (data) {
        var listeners = data.currentaudience;
        var maxListeners = 100;

        $("#nb-auditeurs").text(listeners);
        $("#max-auditeurs").text(maxListeners);
        $("#max-auditeurs-progress").css("width", parseInt(listeners / maxListeners * 100) + '%');

        setTimeout(currentAudience, data.callback * 1000);
    });
}

var lastUpdate = null;
function listenersMessages() {
    var $url = Routing.generate('listenersmessages');

    $.getJSON($url, {
        'last': lastUpdate,
        'type': 'html'
    }, function (data) {
        console.log(data);
        $("#list-dedicaces").prepend($(data.data).fadeIn('slow'));
        $("#update-time").text(data.datetime);
        lastUpdate = data.datetime;
        setTimeout(filtreDedicaces, 100, null, null, etatFiltres);
        setTimeout(evenements, 100);
        setTimeout(listenersMessages, $("#taux-rafraichissement").val() * 1000);
    });

}

var etatFiltres = {
    ip: false,
    name: false
};
function filtreDedicaces(element, critere, etatFiltres) {

    if (element != null && critere != null && etatFiltres != null) {
        if (etatFiltres[critere] === false) {
            etatFiltres[critere] = $(element).text();
        } else {
            etatFiltres[critere] = false;
        }
    }

    // Réinitialisation
    $(".dedicace:hidden").show();
    $.each(etatFiltres, function (index) {
        $(".dedicace ." + index).removeClass('filtre');
    });

    // Filtrage
    $.each(etatFiltres, function (index, value) {
        if (etatFiltres[index] !== false) {
            $(".dedicace ." + index).each(function () {
                if ($(this).text() !== value) {
                    $(this).parents(".dedicace").hide();
                } else {
                    $(this).addClass('filtre');
                }
            });
        }
    });

    return etatFiltres;
}

function evenements() {

    $(".dedicace .ip").off('touchend, click').on('touchend, click', function () {
        etatFiltres = filtreDedicaces(this, "ip", etatFiltres);
    });
    $(".dedicace .name").off('touchend, click').on('touchend, click', function () {
        etatFiltres = filtreDedicaces(this, "name", etatFiltres);
    });

    $('.dedicace .pin').off('touchend, click').on('touchend, click', function (e) {
        e.preventDefault();

        if ($(this).hasClass("active")) {

            id = $(this).parents(".dedicace").attr("id");
            arr = id.split('-');
            id = arr[arr.length - 1];

            $("#dedicace-" + id + " .pin").removeClass("active");

            var $dediEpingles = $("#dedicaces-epingles");
            $dediEpingles.find("#epingle-" + id).remove();

            if (!($dediEpingles.find(".dedicace").length)) {
                $dediEpingles.find(".nothing").show();
            }

        } else {
            $(this).addClass("active");

            var id = $(this).parents(".dedicace").attr("id");
            var arr = id.split('-');
            id = arr[arr.length - 1];

            $(this).parents(".dedicace").clone().prependTo("#dedicaces-epingles").attr("id", "epingle-" + id);
            $("#dedicaces-epingles").find(".nothing").hide();

            setTimeout(evenements, 50);
        }

        return false;
    });
}

$(function () {

    setTimeout(listenersMessages, 0);
    setTimeout(currentAudience, 0);

    $("#dedicaces-epingles").sortable({
        axis: "y",
        cancel: ".nothing",
        containment: "parent",
        cursor: "move",
        opacity: 0.8,
        tolerance: "pointer"
    });
});