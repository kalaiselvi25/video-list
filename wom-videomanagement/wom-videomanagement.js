jQuery(document).ready(function($) {
        $('.play-button').magnificPopup({
            type: 'iframe',
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com/',
                        id: 'v=',
                        src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                    },
                    vimeo: {
                        index: 'vimeo.com/',
                        id: '/',
                        src: 'https://player.vimeo.com/video/%id%?autoplay=1'
                    }
                }
            }
        });

		
		$(".play-button3").click(function(e){
			e.preventDefault();
			var postid= $(this).attr('data-postid');
			$('a.open-popup'+postid).trigger("click");
		});
  


});
