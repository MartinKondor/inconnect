'use strict';

(function($) {

    function notificator() {
        $.post($('#notification-link').attr('href'), function(data) {

            if (data = []) {
                $('#friend-notification-list').html('<div class="mx-2 my-2">There are no friend requests to show.</div>');
                $('#message-list').html('<div class="mx-2 my-2">There are no messages to show.</div>');
                $('#notification-list').html('<div class="mx-2 my-2">There are no notifications to show.</div>');
                return null;
            }

            let friendsData = '<div>';
            let messageData = '<div>';
            let generalData = '';

            for (let i in data) {

                if (i === 'friend') {
                    friendsData += ``;
                }

                if (i === 'message') {
                    messageData += ``;
                }

                if (i === 'general') {
                    for (let general of data[i]) {

                        let when = '';
                        if (general['when'].h === 0 && general['when'].i === 0)
                            when = `Just now.`;
                        else if (general['when'].h === 0 && general['when'].i >= 1)
                            when = `${general['when'].i} minutes ago.`;
                        else
                            when = `${general['when'].h} hour and ${general['when'].i} minutes ago.`;

                        let what = '';
                        if (general['what'] === 'comment') what = 'You got a new Comment!';
                        if (general['what'] === 'upvote') what = 'You got a new Upvote!';

                        generalData += `
                        <li class="mb-4 pl-1 pr-1">
                            <a href="${general['link']}">
                                <button class="btn btn-link">
                                    <span class="font-weight-bold mt-1 mb-1">${what}</span>
                                    <br/>
                                    <span class="text-muted">${when}</span>
                                </button>
                            </a>
                        </li>`;
                    }
                }

                if (i === 'counters') {
                    $('#friend-notification-counter').html(data[i]['friend'] === 0 ? '' : data[i]['friend']);
                    $('#message-notification-counter').html(data[i]['message'] === 0 ? '' : data[i]['message']);
                    $('#general-notification-counter').html(data[i]['general'] === 0 ? '' : data[i]['general']);
                }
            }

            friendsData += '</div>';
            messageData += '</div>';
            generalData += '';
            $('#friend-notification-list').html(friendsData);
            $('#message-list').html(messageData);
            $('#notification-list').html(generalData);

            setTimeout(notificator, 120000);
        });
    }

    notificator();

})(jQuery);