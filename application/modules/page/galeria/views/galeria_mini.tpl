<script type="text/javascript" src="/www/page/js/jquery.ad-gallery.js"></script>

<div class="galeriaMini">
    <div class="ad-gallery">
      <div class="ad-image-wrapper" style="display: none;"></div>
      <div class="ad-controls" style="display: none;"></div>
      <div class="ad-nav">
        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
            {foreach from=$galeria->zdjecia key=zdjecieId item=zdjeciePlik}
                <li>
                  <a href="/images/galerie/2/{$zdjeciePlik}">
                    <img src="/images/galerie/1/{$zdjeciePlik}" title="{$galeria->foto_nazwy.zdjecieId.$jezyk_id}">
                  </a>
                </li>
            {/foreach}
          </ul>
        </div>
      </div>
    </div>
</div>

{literal}
<script type="text/javascript">
jQuery(document).ready(function () {

    var galleries = $('.galeriaMini .ad-gallery').adGallery({
      loader_image: 'loader.gif',
      width: false, // Width of the image, set to false and it will read the CSS width
      height: false, // Height of the image, set to false and it will read the CSS height
      thumb_opacity: 0.7, // Opacity that the thumbs fades to/from, (1 removes fade effect)
                          // Note that this effect combined with other effects might be resource intensive
                          // and make animations lag
      start_at_index: 0, // Which image should be displayed at first? 0 is the first image
      description_wrapper: false, // Either false or a jQuery object, if you want the image descriptions
                                               // to be placed somewhere else than on top of the image
      animate_first_image: false, // Should first image just be displayed, or animated in?
      animation_speed: 400, // Which ever effect is used to switch images, how long should it take?
      display_next_and_prev: true, // Can you navigate by clicking on the left/right on the image?
      display_back_and_forward: true, // Are you allowed to scroll the thumb list?
      scroll_jump: 0, // If 0, it jumps the width of the container
      slideshow: {
        enable: false,
        autostart: true,
        speed: 5000,
        start_label: 'Start',
        stop_label: 'Stop',
        stop_on_scroll: true, // Should the slideshow stop if the user scrolls the thumb list?
        countdown_prefix: '(', // Wrap around the countdown
        countdown_sufix: ')',
        onStart: function() {
          // Do something wild when the slideshow starts
        },
        onStop: function() {
          // Do something wild when the slideshow stops
        }
      },
      effect: 'fade', // or 'slide-vert', 'resize', 'fade', 'none' or false
      enable_keyboard_move: true, // Move to next/previous image with keyboard arrows?
      cycle: true, // If set to false, you can't go from the last image to the first, and vice versa
      // All callbacks has the AdGallery objects as 'this' reference
      callbacks: {
        onClick: function () {
            // funkcja dopisana recznie
            {/literal}
            document.location = '{$linkDoGalerii}';
            {literal}
        },
        // Executes right after the internal init, can be used to choose which images
        // you want to preload
        init: function() {
          // preloadAll uses recursion to preload each image right after one another
          //this.preloadAll();
          // Or, just preload the first three
          this.preloadImage(0);
          this.preloadImage(1);
          this.preloadImage(2);
        },
        // This gets fired right after the new_image is fully visible
        afterImageVisible: function() {
          // For example, preload the next image
          var context = this;
          this.loading(true);
          this.preloadImage(this.current_index + 1,
            function() {
              // This function gets executed after the image has been loaded
              context.loading(false);
            }
          );

          // Want slide effect for every other image?
          /*if(this.current_index % 2 == 0) {
            this.settings.effect = 'slide-hori';
          } else {
            this.settings.effect = 'fade';
          }*/
        },
        // This gets fired right before old_image is about to go away, and new_image
        // is about to come in
        beforeImageVisible: function(new_image, old_image) {
          // Do something wild!
        }
      }
    });

    // Change effect on the fly
    //galleries[0].settings.effect = 'fade';
    
});
</script>
{/literal}
