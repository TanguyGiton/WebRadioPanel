function ajaxSendDedicace() {

    var $form = $("#form-dedicaces");

    var $submit = $form.find('button[type=submit]');

    var $loading = $('<i class="fa fa-circle-o-notch fa-spin"></i>');
    var $ok = $('<i class="fa fa-check"></i>');
    var $error = $('<i class="fa fa-times"></i>');

    $submit.append(' ').append($loading.hide()).append($ok.hide()).append($error.hide());

    $form.submit(function (e) {
        e.preventDefault();

        $loading.show();
        $submit.attr('disabled', 'disabled');

        var formSerialize = $(this).serialize();
        $.post(window.location, formSerialize, function (response) {

            if (response.status == 'success') {
                $form.find('textarea').val('');
                $ok.show().delay(3000).fadeOut();
            } else {
                $error.show().delay(3000).fadeOut();
            }
            $submit.removeAttr('disabled');
            $loading.hide();
        }, 'JSON');
    });
}