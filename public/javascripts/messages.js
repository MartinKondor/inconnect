'use strict';

(function() {

    $('.message-link').on('click', function(e) {
        e.preventDefault();

        let msgId = $(this).attr('id').replace(/\D/ig, '');

        $('.message-box').css('display', 'none');
        $('#messages-of-' + msgId).css('display', 'block');

        // Adding messages from database with AJAX
        $('#messages-of-' + msgId + ' ul').html('');
        for (let i of [0, 1, 2, 3, 4]) {
            $('#messages-of-' + msgId + ' ul').append('message');
        }
    });

    $('.send-message').on('click', function(e) {
        e.preventDefault();

        let msgId = $(this).attr('href').replace(/\D/ig, '')

        $.ajax({
            method: 'POST',
            url: $(this).attr('href'),
            data: { message: $('#message-field-' + msgId).val() }
        }).done((data) => {
            if (data === 'success') {
                // Adding message to the DOM

            }
        });
    });

})(jQuery);