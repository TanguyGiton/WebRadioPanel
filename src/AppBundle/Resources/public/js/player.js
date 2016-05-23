function initPlayer($streamurl) {

    $('#jquery-jplayer').jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", {
                mp3: $streamurl,
                oga: $streamurl
            }).jPlayer("play");
        },
        swfPath: "assets/swf/jquery.jplayer.swf",
        supplied: "mp3, oga",
        wmode: "window",
        volume: 1.0,
        preload: "none",
        //errorAlerts: true,
        //warningAlerts: true,
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
    }).bind($.jPlayer.event.play, function () {
        startSoundMeter();
    }).bind($.jPlayer.event.pause, function () {
        flagSoundMeter = false;
        $(this).jPlayer("clearMedia").jPlayer("setMedia", {
            mp3: $streamurl,
            oga: $streamurl
        });
    }).jPlayer("play");
}