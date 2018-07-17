'use strict';

(function() {

    let lastMessageUrl = '';
    let lastMessageId = '';

    $('.message-link').on('click', function(e) {
        e.preventDefault();

        let msgId = $(this).attr('id').replace(/\D/gi, '');

        $('.message-box').css('display', 'none');
        $('#messages-of-' + msgId).css('display', 'block');

        $('#opened-contact-name').html( $('#contact-name-' + msgId).clone() );

        lastMessageUrl = $(this).attr('href');
        lastMessageId = msgId;
        getMessages();
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
                $('#message-field-' + msgId).val('');

                getMessages();
            }
        });
    });

    function getMessages() {
        $.ajax({
            method: 'POST',
            url: lastMessageUrl,
            data: { fromUserId: lastMessageId }
        }).done((data) => {

            // Adding messages from database with AJAX
            $('#messages-of-' + lastMessageId + ' .message-list').html('');

            let messagesList = ``;
            for (let msg of data['messages']) {
                let fromWho = 'got-message'; // css class of the message

                if (msg['user_id'] !== lastMessageId) fromWho = 'sent-message';

                messagesList += `<li title="${msg['action_date']}" class="${fromWho}">${msg['content']}</li>`;
            }
            $('#messages-of-' + lastMessageId + ' .message-list').append(messagesList);
        });
    }

})(jQuery);