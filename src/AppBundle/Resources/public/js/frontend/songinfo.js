function shortString(string, nbChar) {
    if (string.length > nbChar) {
        var indexCut = string.lastIndexOf(" ", nbChar + 1);
        string = string.substr(0, indexCut);
        string += '...';
    }
    return string
}

function updateSongInfo() {

    var nbMaxChar = 35;

    $.getJSON('ajax/currentsong.json', function (data) {

        console.log(data);

        var $albumcover = $("#titrage");
        var $artist = $("#artist");
        var $title = $("#title");

        if ($artist.attr('title') != data.artist && $title.attr('title') != data.title) {
            $artist.text(shortString(data.artist, nbMaxChar)).attr('title', data.artist);
            $title.text(shortString(data.title, nbMaxChar)).attr('title', data.title);

            if (data.albumcover != "") {
                $albumcover.css("background", "url(" + data.albumcover + ") no-repeat center center");
                $albumcover.css("background-size", "contain");
            } else {
                $albumcover.css("background", "");
            }
        }

        setTimeout(updateSongInfo, data.callback * 1000);
    }).fail(function () {
        setTimeout(updateSongInfo, 5000);
    });
}