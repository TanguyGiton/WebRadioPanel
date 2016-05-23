function updateSongInfo() {

    var nbMaxChar = 10;

    $.getJSON('ajax/currentsong.json', function (data) {
        console.log(data);

        var $artist = $("#artist");
        var $title = $("#title");
        if ($artist.attr('title') != data.artist && $title.attr('title') != data.title) {
            $artist.text(data.artist.substr(0, nbMaxChar)).attr('title', data.artist);
            $title.text(data.title.substr(0, nbMaxChar)).attr('title', data.title);
        }

        setTimeout(updateSongInfo, data.callback * 1000)
    })
}