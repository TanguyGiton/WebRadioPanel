var WEBRADIOPANEL = WEBRADIOPANEL || {};

WEBRADIOPANEL.player = new (function () {

    this.streamUrl = null;
    this.$DOMElement = null;
    this.debug = false;
    this.media = {};
    this.param = {
        swfPath: "/bundles/app/swf/jquery.jplayer.swf",
        supplied: "mp3, oga",
        wmode: "window",
        volume: 1.0,
        preload: "none",
        noVolume: {
            ipad: null,
            iphone: null,
            ipod: null,
            android_pad: null,
            android_phone: null,
            blackberry: null,
            windows_ce: null,
            iemobile: null,
            webos: null,
            playbook: null
        }
    };

    this.init = function (streamUrl, $DOMElement, debug) {
        this.build(streamUrl, $DOMElement, debug);
        this.JPlayer();
    };

    this.build = function (streamUrl, $DOMElement, debug) {
        this.streamUrl = streamUrl;
        this.$DOMElement = $DOMElement;

        if (typeof(debug) !== 'undefined') this.debug = debug;

        this.media = {
            mp3: this.streamUrl,
            oga: this.streamUrl
        };

        if (this.debug) {
            this.param.errorAlerts = true;
            this.param.warningAlerts = true;
        }
    };

    this.JPlayer = function () {

        var obj = this;

        this.$DOMElement.jPlayer(this.param).bind($.jPlayer.event.play, function () {
            WEBRADIOPANEL.soundMeter.start();
        }).bind($.jPlayer.event.pause, function () {
            WEBRADIOPANEL.soundMeter.stop();
            $(this).jPlayer("clearMedia").jPlayer("setMedia", obj.media);
        }).jPlayer("setMedia", obj.media).jPlayer("play");
    }
});