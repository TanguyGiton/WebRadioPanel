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

    /* Vote */
    initVote();

    /* Ajax Send Dedicace */
    ajaxSendDedicace();
});