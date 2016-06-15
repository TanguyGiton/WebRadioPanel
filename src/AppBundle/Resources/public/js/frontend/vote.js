var WEBRADIOPANEL = WEBRADIOPANEL || {};

WEBRADIOPANEL.vote = new (function () {
    this.$vote = null;
    this.$voteLinks = null;
    this.url = Routing.generate('ajax_vote');

    this.init = function ($vote) {
        this.$vote = $vote;
        this.$voteLinks = this.$vote.find('a');
        this.event();
    };

    this.event = function () {

        var obj = this;

        this.$voteLinks.click(function (e) {
            e.preventDefault();

            var $this = $(this);

            if ($this.hasClass('load')) {
                return false;
            }

            obj.$voteLinks.addClass('load');

            var positive = $this.hasClass('like');

            $.getJSON(obj.url, {
                positive: positive
            }, function (data) {
                if (data.status === 'success') {
                    var isActive = $this.hasClass('active');
                    obj.$voteLinks.removeClass("active");
                    if (!isActive) {
                        $this.addClass("active");
                    }
                    obj.$voteLinks.removeClass("load");
                } else {
                    obj.$voteLinks.removeClass("load");
                }
            }).fail(function () {
                obj.$voteLinks.removeClass("load");
            });

            return false;
        });
    };

    this.reinit = function () {
        this.$voteLinks.removeClass("active").removeClass("load");
    };

});