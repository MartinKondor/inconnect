'use strict';
(function($) {
   console.log('Help us, to make the most out of InConnect: https://github.com/in-connect/inconnect');

   $('.comment-link').on('click', function(e) {
      e.preventDefault();

      // Get the id of the post from the id of the comment link
      let id = $(this).attr('id').replace(/\-link$/gi, '').trim();

      // Toggle comments 
      if ($(`#${id}-comments`).css('display') === 'none') {
         $('#' + $(this).attr('id') + ' i').addClass('fas').removeClass('far');
         $(`#${id}-comments`).css('display', 'block');
         $(`#${id}-comment-field`).css('display', 'flex');
      } else {
         $('#' + $(this).attr('id') + ' i').addClass('far').removeClass('fas');
         $(`#${id}-comments`).css('display', 'none');
         $(`#${id}-comment-field`).css('display', 'none');
      }
   });

   // Upvote 
   $('.upvote-icon').on('click', function(e) {
      e.preventDefault();

      // Upvote post and get the upvote count
      $.ajax({
          method: 'POST',
          url: $(this).attr('href')
      }).done((upvoteData) => {
          if (upvoteData['way'] === 'up') {
              $(this).html(`<i class="fas fa-thumbs-up animated zoomIn"></i> ${upvoteData["upvoteCount"]}`);
          }
          if (upvoteData['way'] === 'down') {
              $(this).html(`<i class="far fa-thumbs-up animated zoomIn"></i> ${upvoteData["upvoteCount"]}`);
          }
      });
  });

  $('.send-comment').on('click', function(e) {
        e.preventDefault();

        let url = $(this).attr('href');

        // Getting the post id from the url
        let urlArray = url.split('/');
        let postId = urlArray[urlArray.length - 2];
        let commentContent = $('#comment-area-' + postId).val();

        if (commentContent) {
            $.ajax({
                method: 'POST',
                url: url,
                data: { comment: commentContent }
            }).done((commentData) => {

                // Place comment on the page by forming the html
                if (commentData['status'] === 'success') {

                    let commentHtml = `
                        <div class="comment animated lightSpeedIn">
                            <div>
                                <a href="${commentData['actualUserLink']}" class="comment-commenter">
                                    <img class="xxs-profile" src="/images/profiles/${commentData['actualUserProfilePic']}" alt="Profile picture of ${commentData['actualUserFullName']},">
                                    ${commentData['actualUserFullName']}
                                </a>
                                &middot;
                                <span class="comment-time">Now</span>
                            </div>
                            <pre class="comment-content">${commentContent}</pre>
                        </div>`;

                    $('#post-' + postId + '-comments').append(commentHtml);
                    $('#comment-area-' + postId).val('');
                }
            });
        }
   });

    // Friend adding
   $('.add-friend-btn').on('click', function(e) {
       e.preventDefault();

       $.ajax({
           method: 'POST',
           url: $(this).attr('href')
       }).done((done) => {

           if (done === 'success') {
               $(this).html('Friend request sent');
               $(this).addClass('disabled');
               $(this).prop('disabled', true);
           }
       });
   });

   $('.post-with-page-link').on('click', function(e) {
       e.preventDefault();

       let idOfPage = $(this).attr('id').replace(/\D/gi, '').trim();

       // Open the related form
       if ($('#post-with-' + idOfPage +'-form').css('display') === 'none') {
           $('#post-with-' + idOfPage +'-form').css('display', 'block');
       } else {
           $('#post-with-' + idOfPage +'-form').css('display', 'none');
       }
   });

})(jQuery);
