var WEBRADIOPANEL = WEBRADIOPANEL || {};

WEBRADIOPANEL.soundMeter = new (function () {
    this.barres = [];
    this.loop = null;
    this.$DOMElement = null;

    this.defaultHeight = 50;

    this.getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    };

    this.init = function ($DOMElement, nbBarres) {

        this.$DOMElement = $DOMElement;

        if (this.barres.length === 0) {
            var i;
            for (i = 0; i < nbBarres; i++) {
                this.$DOMElement.append('<div id="barre-' + i + '" class="barre"></div>');
                this.barres.push($('#barre-' + i));
                this.barres[i].css('position', 'absolute').css('left', parseInt(100 / nbBarres) * i + '%');
                this.barres[i].css('bottom', '0').css('background', 'rgba(255,255,255,0.1)').css('z-index', '5').css('width', parseInt(100 / nbBarres) + '%');
                this.barres[i].css('height', this.defaultHeight + 'px').css('border', '1px solid #000');
            }
        }
    };

    this.run = function () {
        var length = this.barres.length;

        if (length !== 0) {
            var i;
            var height;
            var teinte;
            for (i = 0; i < length; i++) {
                height = this.getRandomInt(50, 250);
                teinte = 90 - parseInt((height - 50) / 240 * 90);
                this.barres[i].css('height', height + 'px');
                this.barres[i].css('background', 'hsla(' + teinte + ',76%,55%,0.3)');
            }
        }

        var obj = this;

        this.loop = setTimeout(function () {
            obj.run();
        }, 1000);
    };

    this.start = function () {
        this.run();
    };

    this.stop = function () {

        clearTimeout(this.loop);

        var length = this.barres.length;

        if (length !== 0) {
            var i;
            for (i = 0; i < length; i++) {
                this.barres[i].css('height', this.defaultHeight + 'px');
                this.barres[i].css('background', 'hsla(90,76%,55%,0.3)');
            }
        }
    }

});