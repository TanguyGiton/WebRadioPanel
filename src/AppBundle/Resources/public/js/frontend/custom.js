$(function () {

    /* Init JS */
    $('body').removeClass('no-js').addClass('js');

    /* SoundMeter */
    initSoundMeter(50);

    /* Player */
    var $streamurl = "http://streaming.radionomy.com/webradiopanel";
    initPlayer($streamurl);

    /* SongInfo */
    updateSongInfo();

    /* Ajax Send Dedicace */
    ajaxSendDedicace();
});