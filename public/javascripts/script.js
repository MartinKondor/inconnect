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

      $.ajax({
          method: 'POST',
          url: $(this).attr('href')
      }).done((data) => {

          if (data['way'] === 'up')
              $(this).html('<i class="fas fa-thumbs-up animated zoomIn"></i> Upvote ' + data['upvoteCount']);

          if (data['way'] === 'down')
              $(this).html('<i class="far fa-thumbs-up animated zoomIn"></i> Upvote ' + data['upvoteCount']);
      });
  });

  $('.send-comment').on('click', function(e) {
     e.preventDefault();
     let url = $(this).attr('href');
     let postId = url.split('/')[3];
     let commentContent = $('#comment-area-' + postId).val();

     if ($('#comment-area-' + url.split('/')[3]).val()) {
         $.ajax({
             method: 'POST',
             url: url,
             data: { comment: commentContent }
         }).done((data) => {

             // Place comment on the page
             if (data['status'] === 'success') {
                 let aUserName = data['actualUserFullName'];
                 let aUserLink = data['actualUserLink'];
                 let aUserProfilePic = data['actualUserProfilePic'];

                 let commentHtml = `
                        <div class="comment animated rotateIn">
                            <div>
                                <a href="${aUserLink}" class="comment-commenter">
                                    <img class="xxs-profile" src="/images/profiles/${aUserProfilePic}" alt="Profile picture of ${aUserName},">
                                    ${aUserName}
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
})(jQuery);
