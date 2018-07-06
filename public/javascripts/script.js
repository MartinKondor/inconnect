'use strict';
(function($) {
   console.log('Help us, to make the most out of InConnect: ' + 'https://github.com/in-connect/inconnect');

   $('.comment-link').on('click', function(e) {
      e.preventDefault();
      let id = $(this).attr('id').replace(/\-link$/gi, '').trim();

      // Toggle comments 
      if ($(`#${id}-comments`).css('display') === 'none') {
         $('#' + $(this).attr('id') + ' i').addClass('fas').removeClass('far');
         $(`#${id}-comments`).css('display', 'block');
      } else {
         $('#' + $(this).attr('id') + ' i').addClass('far').removeClass('fas');
         $(`#${id}-comments`).css('display', 'none');
      }
   });

   $('#deleteAccount').on('click', function(e) {
      if (confirm('Are you sure you want to delete your profile? Every message, pictures, posts, and data about you will be completely deleted.')) {
         e.preventDefault();

         $.ajax({
             method: 'POST',
             url: $(this).attr('href')
         }).done((data) => {
            if (data === 'success') {
                alert('Please log out.');
            } else {
                alert('Server error, please try again it later.');
            }
         });
      }
   });

   setTimeout(() => {
      $('header .brand-logo img').addClass('animated rubberBand');
   }, 2222);

   // Upvote 
   $('.upvote-icon').on('click', function(e) {
      e.preventDefault();

      $.ajax({
          method: 'POST',
          url: $(this).attr('href')
      }).done((data) => {

         if ((/^up-\d*$/gi).test(data))
            $(this).html('<i class="fas fa-thumbs-up animated zoomIn"></i> ' + data.replace(/\D/g, ''));

         if ((/^down-\d*$/gi).test(data))
            $(this).html('<i class="far fa-thumbs-up animated zoomIn"></i> ' + data.replace(/\D/g, ''));
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
             if (data === 'success') {
                 let aUserName = $('#actual-user-name').html();
                 let aUserLink = $('#actual-user-name').attr('href');
                 let aUserProfilePic = $('#actual-user-profile-pic').attr('src');

                 let wholeComment = `<div class="comment animated rotateIn">
                      <div>
                         <a href="${aUserLink}" class="comment-commenter">
                            <img class="xxs-profile" src="${aUserProfilePic}">
                            ${aUserName}
                         </a>
                         &middot;
                         <span class="comment-time">Now</span>
                      </div>
                      <span class="comment-content">${commentContent}</span>
                   </div>`;

                 $('#post-' + postId + '-comments').append(wholeComment);
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
