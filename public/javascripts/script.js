'use strict';
(function($) {
   console.log('Contribute on the FlyingStrawberry: ' + 'https://github.com/MartinKondor/flyingstrawberry.com');

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

   $('#friends').on('click', function(e) {
      e.preventDefault();
      if ($('#friend-list').css('display') === 'none') {
         $('#friend-list').css('display', 'block');
      } else {
         $('#friend-list').css('display', 'none');
      }
   });

   $('#posts').on('click', function(e) {
      e.preventDefault();
      if ($('#post-list').css('display') === 'none') {
         $('#post-list').css('display', 'block');
      } else {
         $('#post-list').css('display', 'none');
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
            $(this).html('<i class="fas fa-thumbs-up"></i> ' + data.replace(/\D/g, ''));

         if ((/^down-\d*$/gi).test(data))
            $(this).html('<i class="far fa-thumbs-up"></i> ' + data.replace(/\D/g, ''));
      });
  });

})(jQuery);
