'use strict';
(function($) {
    function notificator() {
        $.post($('#notification-link').attr('href'), function(data) {
            let friendsData = '';
            let messageData = ``;
            let generalData = '';

            for (let i in data) {
                if (i === 'friend') {
                    for (let friend of data[i]) {
                        friendsData += `
                        <li class="mb-1 pl-1 pr-1">
                            <a href="${friend['link']}">
                                <button class="btn btn-link">
                                    <img src="/images/profiles/${friend['pic']}" class="xs-profile"
                                        alt="Profile picture of ${friend['name']}">
                                    <span class="font-weight-bold mt-1 mb-1">Friend request from ${friend['name']}</span>
                                </button>
                            </a>
                            <form method="POST" action="${friend['acceptLink']}" style="display: inline-block">
                                <button class="btn btn-link friend-accept-link" type="submit" >Accept</button>
                            </form>
                            <form method="POST" action="${friend['declineLink']}" style="display: inline-block">
                                <button class="btn btn-link text-danger friend-decline-link" type="submit">Decline</button>
                            </form>
                        </li><hr>`;
                    }
                }
                if (i === 'message') {
                    messageData += ``;
                }
                if (i === 'general') {
                    for (let general of data[i]) {

                        let when = formatDate(general);

                        let what = '';
                        if (general['what'] === 'comment') what = 'You got a new Comment!';
                        if (general['what'] === 'upvote') what = 'You got a new Upvote!';

                        generalData += `
                        <li class="mb-3 pl-1 pr-1">
                            <form action="${general['deleteLink']}" method="POST">
                                  <button type="submit" class="btn btn-link text-danger mb-0 mt-0">&times;</button>
                            </form>
                            <a href="${general['link']}">
                                <button class="btn btn-link">
                                      <span class="font-weight-bold">${what}</span>
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
            friendsData += '';
            messageData += '';
            generalData += '';
            $('#friend-notification-list').html(friendsData);
            $('#message-list').html(messageData);
            $('#notification-list').html(generalData);

            setTimeout(notificator, 120000);
        });
    }

    if ($('#notification-link').attr('href')) notificator();

    function formatDate(date) {
        if (date['when'].h === 0 && date['when'].i === 0)
            return `Just now.`;
        else if (date['when'].h === 0 && date['when'].i >= 1)
            return `${date['when'].i} minutes ago.`;
        else
            return `${date['when'].h} hour and ${date['when'].i} minutes ago.`;
    }
})(jQuery);