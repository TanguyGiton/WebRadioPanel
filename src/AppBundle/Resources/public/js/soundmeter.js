var flagSoundMeter = false;
var barres = [];

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

function initSoundMeter(nbBarres) {
    if (barres.length === 0) {
        var i;
        for (i = 0; i < nbBarres; i++) {
            $('#bg-countdown').append('<div id="barre-' + i + '" class="barre"></div>');
            barres.push($('#barre-' + i));
            barres[i].css('position', 'absolute').css('left', parseInt(100 / nbBarres) * i + '%');
            barres[i].css('bottom', '0').css('background', 'rgba(255,255,255,0.1)').css('z-index', '5').css('width', parseInt(100 / nbBarres) + '%');
            barres[i].css('height', '50px').css('border', '1px solid #000');
        }
    }
}

function animSoundMeter() {
    if (flagSoundMeter) {
        var length = barres.length;

        if (length !== 0) {
            var i;
            for (i = 0; i < length; i++) {
                barres[i].css('height', getRandomInt(10, 250) + 'px');
            }
        }

        setTimeout(animSoundMeter, 1000);
    } else {
        stopSoundMeter();
    }
}

function stopSoundMeter() {
    var length = barres.length;

    if (length !== 0) {
        var i;
        for (i = 0; i < length; i++) {
            barres[i].css('height', '50px');
        }
    }
}

function startSoundMeter() {
    flagSoundMeter = true;
    animSoundMeter();
}