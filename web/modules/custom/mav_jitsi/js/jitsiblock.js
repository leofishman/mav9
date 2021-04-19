(function ($, Drupal, drupalSettings) {

    Drupal.behaviors.jitsiblock = {
        attach: function (context, settings) {

            jQuery('#jitsi_new_meeting').once().click(function () {
                window.open("/jitsi/start", "", 'width=' + getW(), 'height=' + $(window).height());
            });

            jQuery('#joinroom').once().click(function () {
                var room = $('#roomname').val();
                if (room == '') {
                    alert(Drupal.t('You must enter a room name'));
                } else {
                    window.open("/jitsi/join/" + room, "", 'width=' + getW(), 'height=' + $(window).height());
                }
            });

            function getW() {
                var w = settings.mav_jitsi_block.width;
                return  w;
            }
            

        } 
    }; 
})(jQuery, Drupal, drupalSettings);


