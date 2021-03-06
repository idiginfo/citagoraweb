(function($) {  
  
$.fn.newsScroll = function(options) {  
  
    return this.each(function() {     
  
        var  
          $this = $(this),   
  
          defaults = {  
            speed: 400,  
            delay: 3000,  
            list_item_height: $this.children('li').outerHeight()  
         },  
  
          settings = $.extend({}, defaults, options);   
  
      setInterval(function() {  
            $this.children('li:first')  
                    .animate({  
                        marginTop : '-' + settings.list_item_height,  
                       opacity: 'hide' },  
  
                       settings.speed,  
  
                       function() {  
                            $this  
                              .children('li:first')  
                              .appendTo($this)  
                              .css('marginTop', 0)  
                              .fadeIn(300);  
                      }  
                  ); // end animate  
      }, settings.delay); // end setInterval  
    });  
}  
  
})(jQuery);  