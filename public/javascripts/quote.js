'use strict';
(function($) {
   // Get quote from api
   /* $.getJSON('https://api.forismatic.com/api/1.0/?method=getQuote&lang=en&format=jsonp&jsonp=?', (response) => {
        $('#quote').html(`
            <div class="text-center mt-4 mb-4 text-muted animated fadeIn">
               <cite class="h5">
                  <i class="fas fa-quote-left"></i>
                  ${response.quoteText}
                  <i class="fas fa-quote-right"></i>
               </cite>
               <br>
               <a class="h5" style="text-decoration:none;color:inherited;" href="${response.quoteLink}">
               - ${response.quoteAuthor}
               </a>
            </div>
        `);
   }); */
})(jQuery);