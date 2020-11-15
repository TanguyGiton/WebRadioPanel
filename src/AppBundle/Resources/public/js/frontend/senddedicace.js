var WEBRADIOPANEL = WEBRADIOPANEL || {};

WEBRADIOPANEL.dedicaces = new (function () {
    this.$form = $("#form-dedicaces");
    this.$submit = this.$form.find('button[type=submit]');
    this.$loading = $('<i class="fa fa-circle-o-notch fa-spin"></i>');
    this.$ok = $('<i class="fa fa-check"></i>');
    this.$error = $('<i class="fa fa-times"></i>');

    this.init = function () {
        this.$submit.append(' ').append(this.$loading.hide()).append(this.$ok.hide()).append(this.$error.hide());
        this.event();
    };

    this.event = function () {
        var obj = this;

        this.$form.submit(function (e) {
            e.preventDefault();

            obj.$loading.show();
            obj.$submit.attr('disabled', 'disabled');

            var formSerialize = $(this).serialize();
            $.post(window.location, formSerialize, function (response) {

                if (response.status == 'success') {
                    obj.$form.find('textarea').val('');
                    obj.$ok.show().delay(3000).fadeOut();
                } else {
                    obj.$error.show().delay(3000).fadeOut();
                }
                obj.$submit.removeAttr('disabled');
                obj.$loading.hide();
            }, 'JSON');
        });
    };
});