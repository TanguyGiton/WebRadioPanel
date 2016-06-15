$(function () {

    /* Init JS */
    $('body').removeClass('no-js').addClass('js');

    /* SoundMeter */
    WEBRADIOPANEL.soundMeter.init($('#bg-countdown'), 50);

    /* Player */
    var streamUrl = "http://streaming.radionomy.com/webradiopanel";
    WEBRADIOPANEL.player.init(streamUrl, $('#jquery-jplayer'));

    /* SongInfo */
    WEBRADIOPANEL.songinfo.init();

    /* Vote */
    WEBRADIOPANEL.vote.init($('#infos-musique').find('.vote'));

    /* Ajax Send Dedicace */
    WEBRADIOPANEL.dedicaces.init();
});