'use strict';
(function($) {
    let keyCounter = 0;

    $('#search-field').keypress(function() {
        if (keyCounter % 2 !== 0 && $(this).val().length >= 2) {
            $.ajax({
                method: 'POST',
                url: $(this).attr('href'),
                data: { query: $(this).val() }
            }).done((data) => {
                console.log(data);
                let searchList = '';
                for (let p in data) {
                    for (let pd in data[p]) {
                        searchList += `
                        <li class="animated pulse"><a href="/u/${data[p][pd]['permalink']}">
                            <img class="m-profile" src="/images/profiles/${data[p][pd]['profile_pic']}" alt="Profile picture">
                             ${data[p][pd]['first_name']} ${data[p][pd]['last_name']}
                        </a></li>`;
                    }
                }
                $('#search-list').css('display', 'block');
                $('#search-list').html(searchList);
            });
            keyCounter = 0;
        }
        keyCounter++;
    });

    $('html').on('click', function() {
        $('#search-list').css('display', 'none');
    });
})(jQuery);