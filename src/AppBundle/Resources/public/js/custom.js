$(function () {

    /* SoundMeter */
    initSoundMeter(50);

    /* Player */
    var $streamurl = "http://streaming.radionomy.com/webradiopanel";
    initPlayer($streamurl);

    /* SongInfo */
    updateSongInfo();
});