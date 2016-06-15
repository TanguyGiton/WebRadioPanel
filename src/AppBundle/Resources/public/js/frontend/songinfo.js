var WEBRADIOPANEL = WEBRADIOPANEL || {};

WEBRADIOPANEL.songinfo = new (function () {
    this.nbMaxChar = 35;
    this.url = Routing.generate('currentsong');

    this.$albumcover = $("#titrage");
    this.$artist = $("#artist");
    this.$title = $("#title");

    this.init = function () {
        this.update();
    };

    this.shortString = function (string, nbChar) {
        if (string.length > nbChar) {
            var indexCut = string.lastIndexOf(" ", nbChar + 1);
            string = string.substr(0, indexCut);
            string += '...';
        }
        return string;
    };

    this.update = function () {

        var obj = this;

        $.getJSON(obj.url, function (data) {

            if (obj.$artist.attr('title') != data.artist && obj.$title.attr('title') != data.title) {

                WEBRADIOPANEL.vote.reinit();

                obj.$artist.text(obj.shortString(data.artist, obj.nbMaxChar)).attr('title', data.artist);
                obj.$title.text(obj.shortString(data.title, obj.nbMaxChar)).attr('title', data.title);

                if (data.albumcover) {
                    obj.$albumcover.css("background", "url(" + data.albumcover + ") no-repeat center center");
                    obj.$albumcover.css("background-size", "contain");
                } else {
                    obj.$albumcover.css("background", "");
                }
            }

            obj.loop(data.callback * 1000);
        }).fail(function () {
            obj.loop(5000);
        });
    };

    this.loop = function (time) {
        var obj = this;
        setTimeout(function () {
            obj.update();
        }, time);
    }
});