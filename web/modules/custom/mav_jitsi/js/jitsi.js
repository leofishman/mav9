(function ($, Drupal, drupalSettings) {

    Drupal.behaviors.mav_jitsi = {
        attach: function (context, settings) {
            var logger = [];
            $('#meet-stop').hide();
            $('#on-meet-controls').hide();

            $('#meet-start').once().click(function (event) {
                event.preventDefault();
                var videodomain = settings.mav_jitsi.domain;
                const options = {
                    roomName: settings.mav_jitsi.room,
                    width: '100%',
                    height: '100%',
                    parentNode: document.querySelector('#meet'),
                    userInfo: {
                        email: settings.mav_jitsi.email,
                        displayName: settings.mav_jitsi.user,
                    },
                    interfaceConfigOverwrite: {enableClosePage: true},
                };

                api = new JitsiMeetExternalAPI(videodomain, options);
                api.executeCommand('sendTones', {
                    tones: '192837#', 
                });
                
                api.executeCommand('avatarUrl', settings.mav_jitsi.avatar);

                api.on("videoConferenceJoined", function (element) {
                    log_event('Conference Joined', element);
                });

                api.on("videoConferenceLeft", function (element) {
                    log_event('Conference Left', element);
                });

                api.on("participantJoined", function (element) {
                    log_event('Participant Joined', element);
                });

                api.on("participantKickedOut", function (element) {
                    log_event('Participant Kicked out', element);
                })

                api.on("participantLeft", function (element) {
                    log_event('Participant left', element);
                });

                $('#meet-stop').show();
                $('#meet-start').hide();
                $('#on-meet-controls').show(500);

            });

            $('#meet-stop').click(function (event) {
                event.preventDefault();
                $('#meet-start').show();
                $('#meet-stop').hide();
                $('#on-meet-controls').hide(300);
                api.dispose();
            });

            $('#set-name').click(function (event) {
                event.preventDefault();
                var name = $('#staticName').val();
                api.executeCommand('displayName', name);
            });

            $('#set-subject').click(function (event) {
                event.preventDefault();
                var subject = $('#staticSubject').val();
                api.executeCommand('subject', subject);
            });

            $('#set-password').click(function (event) {
                event.preventDefault();
                var password = $('#staticPassword').val();
                api.executeCommand('password', password);
            });
            
            $('#share-screen').click(function (event) {
                event.preventDefault();
                api.executeCommand('toggleShareScreen');
            });
                        
            function log_event(event, element) {
                var d = new Date();
                var t = d.getTime();
                var data = [{
                        'type': event,
                        'time': t,
                        'element': element,
                    }]
                logger.push(data);
                console.log(data);
            }

            $(".copylink").click(function () {
                var text = $('#roomlink').attr('href');
                $body = document.getElementsByTagName('body')[0];
                var $tempInput = document.createElement('INPUT');
                $body.appendChild($tempInput);
                $tempInput.setAttribute('value', text)
                $tempInput.select();
                document.execCommand('copy');
                $body.removeChild($tempInput);
            });
        }
    };
})(jQuery, Drupal, drupalSettings);


