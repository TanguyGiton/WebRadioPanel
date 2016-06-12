function initVote() {

    var $vote = $('#infos-musique').find('.vote');
    var $voteLinks = $vote.find('a');
    var url = Routing.generate('ajax_vote');

    $voteLinks.click(function (e) {
        e.preventDefault();

        var $this = $(this);

        if ($this.hasClass('load')) {
            return false;
        }

        $voteLinks.addClass('load');

        var positive = $this.hasClass('like');

        $.getJSON(url, {
            positive: positive
        }, function (data) {
            if (data.status === 'success') {
                var isActive = $this.hasClass('active');
                $voteLinks.removeClass("active");
                if (!isActive) {
                    $this.addClass("active");
                }
                $voteLinks.removeClass("load");
            } else {
                $voteLinks.removeClass("load");
            }
        }).fail(function () {
            $voteLinks.removeClass("load");
        });

        return false;
    });
}

function reinitVote() {
    var $vote = $('#infos-musique').find('.vote');
    var $voteLinks = $vote.find('a');

    $voteLinks.removeClass("active");
    $voteLinks.removeClass("load");
}