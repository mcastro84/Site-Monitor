$( document ).ready(function() {

    $(document).on('click', '.add-url-button', function () {

        var url_input = $('.url-input');

        if ($.trim(url_input.val()).length === 0) {
            return false;
        }

        $.ajax({
            url: APP_URL + '/api/add-url',
            dataType: 'json',
            method: 'post',
            data: 'url='+url_input.val(),
            success: function (result) {
                if (result.success) {
                    var template_clone = $(document).find('#template').clone();
                    template_clone.find('.url').html(url_input.val());
                    template_clone.removeClass('hidden').removeAttr('id');
                    // Updates button to have the Url ID
                    template_clone.find('.remove-url-button').attr('data-id', result.url_id);
                    // Appends new Url to the list
                    $('.panel-body').append(template_clone);
                    // Clears the input text
                    $(url_input).val('');
                    $('.alert-warning').addClass('hidden');
                } else {
                    $('.alert-warning').removeClass('hidden').html(result.message);
                }
            }
        });

    });

    $(document).on('click', '.remove-url-button', function () {

        var _this = $(this);

        if ($(this).attr('data-id')) {
            $.ajax({
                url: APP_URL + '/api/remove-url',
                dataType: 'json',
                method: 'post',
                data: 'url_id='+$(this).data('id'),
                success: function (result) {
                    if (result.success) {
                        $('.alert-warning').addClass('hidden');
                        _this.closest('.row').remove();
                    } else {
                        $('.alert-warning').removeClass('hidden').html(result.message);
                    }
                }
            });
        } else {
            _this.closest('.row').remove();
        }

    });

    $(document).on('click', '.check-urls', function () {

        var urls_count = $('.row').length;
        if (urls_count <= 1) {
            return false;
        }

        $.ajax({
            url: APP_URL + '/api/check-urls',
            dataType: 'json',
            method: 'get',
            success: function (result) {
                if (result.success) {
                    $('.alert-success').removeClass('hidden').html(result.message);
                }
            }
        });
    })

});