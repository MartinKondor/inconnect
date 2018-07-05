'use strict';

(function($) {

    function friendNotificator() {
        $.post($('#friend-notification-list-link').attr('href'), function(data) {

            let preparedData = '<div class="text-danger">';

            for (let notification of data) {

                let date = '';

                // Format date
                let days = notification['action_date'].d;
                let minutes = notification['action_date'].i;

                date += days === 0 ? 'Today ' +minutes+ ' minutes ago' : days + ' day and '+minutes+' ago';

                preparedData += `
                    <p>When: ${date}</p>
                    <p>What: ${notification['action_type']}</p>
                    <p>On: ${notification['entity_type']}</p>
                    <p>Where: ${notification['entity_id']}</p>
                `;
            }
            preparedData += '</div>';

            $('#friend-notification-list').html(preparedData);
            //setTimeout(friendNotificator, 120000);
        });
    }

    function messageNotificator() {
        $.post($('#message-list-link').attr('href'), function(data) {
            $('#message-list').html(data);

            //setTimeout(messageNotificator, 120000);
        });
    }

    function generalNotificator() {
        $.post($('#notification-list-link').attr('href'), function(data) {
            $('#notification-list').html(data);

            //setTimeout(generalNotificator, 120000);
        });
    }

    friendNotificator();
    messageNotificator();
    generalNotificator();

    // Friend Notification menu
    $('#friend-notification-list-link').on('click', function(e) {
        e.preventDefault();

    });

    // Message menu
    $('#message-list-link').on('click', function(e) {
        e.preventDefault();

    });

    // Notification menu
    $('#notification-list-link').on('click', function(e) {
        e.preventDefault();

    });
})(jQuery);