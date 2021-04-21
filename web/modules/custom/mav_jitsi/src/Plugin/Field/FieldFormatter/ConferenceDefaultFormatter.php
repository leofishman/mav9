<?php

namespace Drupal\mav_jitsi\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Render\BareHtmlPageRenderer;

/**
 * Plugin implementation of the 'mav_jitsi_conference_default' formatter.
 *
 * @FieldFormatter(
 *   id = "mav_jitsi_conference_default",
 *   label = @Translation("Default"),
 *   field_types = {"mav_jitsi_conference"}
 * )
 */
class ConferenceDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return ['domain' => 'meet.jit.si'] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $element['domain'] = [
      '#type' => 'textfield',
      '#title' => $this->t('domain'),
      '#default_value' => $settings['domain'],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();
    $summary[] = $this->t('domain: @domain', ['@domain' => $settings['domain']]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $domain = \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_domain') ?
      \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_domain') : 'meet.jit.si';
    foreach ($items as $delta => $item) {
      if ($item->jitsi_conf) {
        $element[$delta]['jitsi_conf'] = $this->join($item->jitsi_conf);
          /*[
          '#type' => 'inline_template',
          '#template' => '<iframe  allow="camera; microphone; display-capture" src="{{ url }}" name="jitsiConferenceFrame0" id="jitsiConferenceFrame0" allowfullscreen="true" style="height: 450px; width: 100%; border: 0px;"></iframe>',
          '#context' => [
            'url' => 'https://meet-app.makeavent.com/606b52303890510ce62cbff6#jitsi_meet_external_api_id=0&amp;config.hosts=%7B%22domain%22%3A%22meet-app.makeavent.com%22%2C%22muc%22%3A%22conference.meet-app.makeavent.com%22%7D&amp;config.bosh=%22%2F%2Fmeet-app.makeavent.com%2Fhttp-bind%22&amp;config.websocket=%22wss%3A%2F%2Fmeet-app.makeavent.com%2Fxmpp-websocket%22&amp;config.clientNode=%22http%3A%2F%2Fjitsi.org%2Fjitsimeet%22&amp;config.testing=%7B%22enableFirefoxSimulcast%22%3Afalse%2C%22p2pTestMode%22%3Afalse%7D&amp;config.enableNoAudioDetection=false&amp;config.enableNoisyMicDetection=false&amp;config.useNicks=false&amp;config.userInfo=%7B%22displayName%22%3A%22leo%22%7D&amp;config.disableRemoteMute=true&amp;config.remoteVideoMenu=%7B%22disableKick%22%3Atrue%7D&amp;config.desktopSharingChromeExtId=null&amp;config.desktopSharingChromeSources=%5B%22screen%22%2C%22window%22%2C%22tab%22%5D&amp;config.desktopSharingChromeMinExtVersion=%220.1%22&amp;config.fileRecordingsEnabled=true&amp;config.liveStreamingEnabled=true&amp;config.channelLastN=-1&amp;config.requireDisplayName=false&amp;config.enableWelcomePage=true&amp;config.defaultLanguage=undefined&amp;config.enableUserRolesBasedOnToken=false&amp;config.disableThirdPartyRequests=true&amp;config.p2p=%7B%22enabled%22%3Atrue%2C%22stunServers%22%3A%5B%7B%22urls%22%3A%22stun%3Ameet-jit-si-turnrelay.jitsi.net%3A443%22%7D%5D%2C%22preferH264%22%3Atrue%7D&amp;config.analytics=%7B%7D&amp;config.deploymentInfo=%7B%7D&amp;config.localRecording=%7B%22enabled%22%3Atrue%7D&amp;config._peerConnStatusOutOfLastNTimeout=false&amp;config._peerConnStatusRtcMuteTimeout=false&amp;config.abTesting=false&amp;config.avgRtpStatsN=false&amp;config.callStatsConfIDNamespace=false&amp;config.callStatsCustomScriptUrl=false&amp;config.desktopSharingSources=false&amp;config.disableAEC=false&amp;config.disableAGC=false&amp;config.disableAP=false&amp;config.disableHPF=false&amp;config.disableNS=false&amp;config.enableLipSync=false&amp;config.enableTalkWhileMuted=false&amp;config.forceJVB121Ratio=false&amp;config.hiddenDomain=%22recorder.meet-app.makeavent.com%22&amp;config.ignoreStartMuted=false&amp;config.nick=false&amp;config.startBitrate=false&amp;config.makeJsonParserHappy=%22even%20if%20last%20key%20had%20a%20trailing%20comma%22&amp;interfaceConfig.DEFAULT_BACKGROUND=%22%23474747%22&amp;interfaceConfig.DISABLE_VIDEO_BACKGROUND=false&amp;interfaceConfig.INITIAL_TOOLBAR_TIMEOUT=5000&amp;interfaceConfig.TOOLBAR_TIMEOUT=4000&amp;interfaceConfig.TOOLBAR_ALWAYS_VISIBLE=false&amp;interfaceConfig.DEFAULT_REMOTE_DISPLAY_NAME=%22%22&amp;interfaceConfig.SHOW_JITSI_WATERMARK=true&amp;interfaceConfig.JITSI_WATERMARK_LINK=%22https%3A%2F%2Fjitsi.org%22&amp;interfaceConfig.SHOW_WATERMARK_FOR_GUESTS=false&amp;interfaceConfig.SHOW_BRAND_WATERMARK=true&amp;interfaceConfig.BRAND_WATERMARK_LINK=%22%22&amp;interfaceConfig.SHOW_POWERED_BY=false&amp;interfaceConfig.SHOW_DEEP_LINKING_IMAGE=false&amp;interfaceConfig.GENERATE_ROOMNAMES_ON_WELCOME_PAGE=true&amp;interfaceConfig.DISPLAY_WELCOME_PAGE_CONTENT=true&amp;interfaceConfig.DISPLAY_WELCOME_PAGE_TOOLBAR_ADDITIONAL_CONTENT=false&amp;interfaceConfig.APP_NAME=%22Makeavent%22&amp;interfaceConfig.NATIVE_APP_NAME=%22Makeavent%22&amp;interfaceConfig.PROVIDER_NAME=%22Makeavent%22&amp;interfaceConfig.LANG_DETECTION=true&amp;interfaceConfig.INVITATION_POWERED_BY=true&amp;interfaceConfig.AUTHENTICATION_ENABLE=true&amp;interfaceConfig.TOOLBAR_BUTTONS=%5B%22fullscreen%22%2C%22sharedvideo%22%2C%22microphone%22%2C%22camera%22%2C%22desktop%22%2C%22tileview%22%2C%22settings%22%5D&amp;interfaceConfig.SETTINGS_SECTIONS=%5B%22devices%22%2C%22language%22%2C%22moderator%22%2C%22profile%22%2C%22calendar%22%5D&amp;interfaceConfig.VIDEO_LAYOUT_FIT=%22both%22&amp;interfaceConfig.filmStripOnly=false&amp;interfaceConfig.VERTICAL_FILMSTRIP=true&amp;interfaceConfig.CLOSE_PAGE_GUEST_HINT=false&amp;interfaceConfig.SHOW_PROMOTIONAL_CLOSE_PAGE=false&amp;interfaceConfig.RANDOM_AVATAR_URL_PREFIX=false&amp;interfaceConfig.RANDOM_AVATAR_URL_SUFFIX=false&amp;interfaceConfig.FILM_STRIP_MAX_HEIGHT=120&amp;interfaceConfig.ENABLE_FEEDBACK_ANIMATION=false&amp;interfaceConfig.DISABLE_FOCUS_INDICATOR=false&amp;interfaceConfig.DISABLE_DOMINANT_SPEAKER_INDICATOR=false&amp;interfaceConfig.DISABLE_TRANSCRIPTION_SUBTITLES=false&amp;interfaceConfig.DISABLE_RINGING=false&amp;interfaceConfig.AUDIO_LEVEL_PRIMARY_COLOR=%22rgba(255%2C255%2C255%2C0.4)%22&amp;interfaceConfig.AUDIO_LEVEL_SECONDARY_COLOR=%22rgba(255%2C255%2C255%2C0.2)%22&amp;interfaceConfig.POLICY_LOGO=null&amp;interfaceConfig.LOCAL_THUMBNAIL_RATIO=1.7777777777777777&amp;interfaceConfig.REMOTE_THUMBNAIL_RATIO=1&amp;interfaceConfig.LIVE_STREAMING_HELP_LINK=%22https%3A%2F%2Fjitsi.org%2Flive%22&amp;interfaceConfig.MOBILE_APP_PROMO=true&amp;interfaceConfig.MAXIMUM_ZOOMING_COEFFICIENT=1.3&amp;interfaceConfig.SUPPORT_URL=%22https%3A%2F%2Fcommunity.jitsi.org%2F%22&amp;interfaceConfig.CONNECTION_INDICATOR_AUTO_HIDE_ENABLED=true&amp;interfaceConfig.CONNECTION_INDICATOR_AUTO_HIDE_TIMEOUT=5000&amp;interfaceConfig.CONNECTION_INDICATOR_DISABLED=true&amp;interfaceConfig.VIDEO_QUALITY_LABEL_DISABLED=true&amp;interfaceConfig.RECENT_LIST_ENABLED=true&amp;interfaceConfig.OPTIMAL_BROWSERS=%5B%22chrome%22%2C%22chromium%22%2C%22firefox%22%2C%22nwjs%22%2C%22electron%22%5D&amp;interfaceConfig.UNSUPPORTED_BROWSERS=%5B%5D&amp;interfaceConfig.AUTO_PIN_LATEST_SCREEN_SHARE=%22remote-only%22&amp;interfaceConfig.DISABLE_PRESENCE_STATUS=false&amp;interfaceConfig.DISABLE_JOIN_LEAVE_NOTIFICATIONS=true&amp;interfaceConfig.SHOW_CHROME_EXTENSION_BANNER=false&amp;interfaceConfig.HIDE_KICK_BUTTON_FOR_GUESTS=true&amp;userInfo.displayName=%22leo%22',
          ],
            //'<iframe allow="camera; microphone; display-capture" src="https://meet-app.makeavent.com/606b52303890510ce62cbff6#jitsi_meet_external_api_id=0&amp;config.hosts=%7B%22domain%22%3A%22meet-app.makeavent.com%22%2C%22muc%22%3A%22conference.meet-app.makeavent.com%22%7D&amp;config.bosh=%22%2F%2Fmeet-app.makeavent.com%2Fhttp-bind%22&amp;config.websocket=%22wss%3A%2F%2Fmeet-app.makeavent.com%2Fxmpp-websocket%22&amp;config.clientNode=%22http%3A%2F%2Fjitsi.org%2Fjitsimeet%22&amp;config.testing=%7B%22enableFirefoxSimulcast%22%3Afalse%2C%22p2pTestMode%22%3Afalse%7D&amp;config.enableNoAudioDetection=false&amp;config.enableNoisyMicDetection=false&amp;config.useNicks=false&amp;config.userInfo=%7B%22displayName%22%3A%22leo%22%7D&amp;config.disableRemoteMute=true&amp;config.remoteVideoMenu=%7B%22disableKick%22%3Atrue%7D&amp;config.desktopSharingChromeExtId=null&amp;config.desktopSharingChromeSources=%5B%22screen%22%2C%22window%22%2C%22tab%22%5D&amp;config.desktopSharingChromeMinExtVersion=%220.1%22&amp;config.fileRecordingsEnabled=true&amp;config.liveStreamingEnabled=true&amp;config.channelLastN=-1&amp;config.requireDisplayName=false&amp;config.enableWelcomePage=true&amp;config.defaultLanguage=undefined&amp;config.enableUserRolesBasedOnToken=false&amp;config.disableThirdPartyRequests=true&amp;config.p2p=%7B%22enabled%22%3Atrue%2C%22stunServers%22%3A%5B%7B%22urls%22%3A%22stun%3Ameet-jit-si-turnrelay.jitsi.net%3A443%22%7D%5D%2C%22preferH264%22%3Atrue%7D&amp;config.analytics=%7B%7D&amp;config.deploymentInfo=%7B%7D&amp;config.localRecording=%7B%22enabled%22%3Atrue%7D&amp;config._peerConnStatusOutOfLastNTimeout=false&amp;config._peerConnStatusRtcMuteTimeout=false&amp;config.abTesting=false&amp;config.avgRtpStatsN=false&amp;config.callStatsConfIDNamespace=false&amp;config.callStatsCustomScriptUrl=false&amp;config.desktopSharingSources=false&amp;config.disableAEC=false&amp;config.disableAGC=false&amp;config.disableAP=false&amp;config.disableHPF=false&amp;config.disableNS=false&amp;config.enableLipSync=false&amp;config.enableTalkWhileMuted=false&amp;config.forceJVB121Ratio=false&amp;config.hiddenDomain=%22recorder.meet-app.makeavent.com%22&amp;config.ignoreStartMuted=false&amp;config.nick=false&amp;config.startBitrate=false&amp;config.makeJsonParserHappy=%22even%20if%20last%20key%20had%20a%20trailing%20comma%22&amp;interfaceConfig.DEFAULT_BACKGROUND=%22%23474747%22&amp;interfaceConfig.DISABLE_VIDEO_BACKGROUND=false&amp;interfaceConfig.INITIAL_TOOLBAR_TIMEOUT=5000&amp;interfaceConfig.TOOLBAR_TIMEOUT=4000&amp;interfaceConfig.TOOLBAR_ALWAYS_VISIBLE=false&amp;interfaceConfig.DEFAULT_REMOTE_DISPLAY_NAME=%22%22&amp;interfaceConfig.SHOW_JITSI_WATERMARK=true&amp;interfaceConfig.JITSI_WATERMARK_LINK=%22https%3A%2F%2Fjitsi.org%22&amp;interfaceConfig.SHOW_WATERMARK_FOR_GUESTS=false&amp;interfaceConfig.SHOW_BRAND_WATERMARK=true&amp;interfaceConfig.BRAND_WATERMARK_LINK=%22%22&amp;interfaceConfig.SHOW_POWERED_BY=false&amp;interfaceConfig.SHOW_DEEP_LINKING_IMAGE=false&amp;interfaceConfig.GENERATE_ROOMNAMES_ON_WELCOME_PAGE=true&amp;interfaceConfig.DISPLAY_WELCOME_PAGE_CONTENT=true&amp;interfaceConfig.DISPLAY_WELCOME_PAGE_TOOLBAR_ADDITIONAL_CONTENT=false&amp;interfaceConfig.APP_NAME=%22Makeavent%22&amp;interfaceConfig.NATIVE_APP_NAME=%22Makeavent%22&amp;interfaceConfig.PROVIDER_NAME=%22Makeavent%22&amp;interfaceConfig.LANG_DETECTION=true&amp;interfaceConfig.INVITATION_POWERED_BY=true&amp;interfaceConfig.AUTHENTICATION_ENABLE=true&amp;interfaceConfig.TOOLBAR_BUTTONS=%5B%22fullscreen%22%2C%22sharedvideo%22%2C%22microphone%22%2C%22camera%22%2C%22desktop%22%2C%22tileview%22%2C%22settings%22%5D&amp;interfaceConfig.SETTINGS_SECTIONS=%5B%22devices%22%2C%22language%22%2C%22moderator%22%2C%22profile%22%2C%22calendar%22%5D&amp;interfaceConfig.VIDEO_LAYOUT_FIT=%22both%22&amp;interfaceConfig.filmStripOnly=false&amp;interfaceConfig.VERTICAL_FILMSTRIP=true&amp;interfaceConfig.CLOSE_PAGE_GUEST_HINT=false&amp;interfaceConfig.SHOW_PROMOTIONAL_CLOSE_PAGE=false&amp;interfaceConfig.RANDOM_AVATAR_URL_PREFIX=false&amp;interfaceConfig.RANDOM_AVATAR_URL_SUFFIX=false&amp;interfaceConfig.FILM_STRIP_MAX_HEIGHT=120&amp;interfaceConfig.ENABLE_FEEDBACK_ANIMATION=false&amp;interfaceConfig.DISABLE_FOCUS_INDICATOR=false&amp;interfaceConfig.DISABLE_DOMINANT_SPEAKER_INDICATOR=false&amp;interfaceConfig.DISABLE_TRANSCRIPTION_SUBTITLES=false&amp;interfaceConfig.DISABLE_RINGING=false&amp;interfaceConfig.AUDIO_LEVEL_PRIMARY_COLOR=%22rgba(255%2C255%2C255%2C0.4)%22&amp;interfaceConfig.AUDIO_LEVEL_SECONDARY_COLOR=%22rgba(255%2C255%2C255%2C0.2)%22&amp;interfaceConfig.POLICY_LOGO=null&amp;interfaceConfig.LOCAL_THUMBNAIL_RATIO=1.7777777777777777&amp;interfaceConfig.REMOTE_THUMBNAIL_RATIO=1&amp;interfaceConfig.LIVE_STREAMING_HELP_LINK=%22https%3A%2F%2Fjitsi.org%2Flive%22&amp;interfaceConfig.MOBILE_APP_PROMO=true&amp;interfaceConfig.MAXIMUM_ZOOMING_COEFFICIENT=1.3&amp;interfaceConfig.SUPPORT_URL=%22https%3A%2F%2Fcommunity.jitsi.org%2F%22&amp;interfaceConfig.CONNECTION_INDICATOR_AUTO_HIDE_ENABLED=true&amp;interfaceConfig.CONNECTION_INDICATOR_AUTO_HIDE_TIMEOUT=5000&amp;interfaceConfig.CONNECTION_INDICATOR_DISABLED=true&amp;interfaceConfig.VIDEO_QUALITY_LABEL_DISABLED=true&amp;interfaceConfig.RECENT_LIST_ENABLED=true&amp;interfaceConfig.OPTIMAL_BROWSERS=%5B%22chrome%22%2C%22chromium%22%2C%22firefox%22%2C%22nwjs%22%2C%22electron%22%5D&amp;interfaceConfig.UNSUPPORTED_BROWSERS=%5B%5D&amp;interfaceConfig.AUTO_PIN_LATEST_SCREEN_SHARE=%22remote-only%22&amp;interfaceConfig.DISABLE_PRESENCE_STATUS=false&amp;interfaceConfig.DISABLE_JOIN_LEAVE_NOTIFICATIONS=true&amp;interfaceConfig.SHOW_CHROME_EXTENSION_BANNER=false&amp;interfaceConfig.HIDE_KICK_BUTTON_FOR_GUESTS=true&amp;userInfo.displayName=%22leo%22" name="jitsiConferenceFrame0" id="jitsiConferenceFrame0" allowfullscreen="true" style="height: 450px; width: 100%; border: 0px;"></iframe>',
        ];
         */
      }
    }
    return $element;
  }


  /**
   * Function to Start video Call.
   *
   * @return array
   *   Return HtmlResponse.
   */
 /* public function start() {

    $config = \Drupal::config(static::SETTINGS);
    $content = [];
    $domain = $config->get('mav_jitsi_domain') ? $config->get('mav_jitsi_domain') : 'meet.jit.si';
    $room = md5(time() + rand());
    $user = User::load(\Drupal::currentUser()->id());
    $username = $user->getAccountName();
    $mail = $user->getEmail();
    if ($user->get('user_picture')->entity) {
      $picture = file_create_url($user->get('user_picture')->entity->getFileUri());
    }
    else {
      $theme = theme_get_setting('logo');
      $picture = \Drupal::request()->getSchemeAndHttpHost() . $theme['url'];
    }
    $url = Url::fromUri('https://' . $domain . '/' . $room, ['attributes' => ['id' => 'roomlink', 'title' => $this->t('Right click to copy')]]);
    $content = [
      '#theme' => 'jitsi_video_page',
      '#room' => $room,
      '#user' => $username,
      '#link_external' => Link::fromTextAndUrl('Room link', $url),
      '#attached' => [
        'drupalSettings' => [
          'mav_jitsi' => [
            'domain' => $domain,
            'room' => $room,
            'user' => $username,
            'email' => $mail,
            'avatar' => $picture,
          ],
        ],
        'library' => ['mav_jitsi/video'],
      ],
    ];
    if ($config->get('mav_jitsi_view') == 'full') {
      $attachments = \Drupal::service('html_response.attachments_processor');
      $renderer = \Drupal::service('renderer');
      $bareHtmlPageRenderer = new BareHtmlPageRenderer($renderer, $attachments);
      $response = $bareHtmlPageRenderer->renderBarePage($content, 'New conference', 'maintenance_page');
      return $response;
    }
    else {
      return $content;
    }
  }
*/
  /**
   * Function to join video call.
   *
   * @param string $room
   *   Key room.
   *
   * @return array
   *   Return HtmlResponse.
   */
  public function join($room) {

    if (!$room) {
      throw new NotFoundHttpException();
    }
    else {
      $roomStr = ucfirst($room);
      preg_match_all('/((?:^|[A-Z0-9])[a-z0-9]+)/', $roomStr, $matches);
      $roomName = NULL;
      foreach ($matches[0] as $k => $w) {
        if (preg_match('/([A-Za-z]+)(\d+)/', $w, $wd)) {
          $roomName .= $wd[1] . " " . $wd[2] . " ";
        }
        else {
          $roomName .= $w . " ";
        }
      }
    }
    $content = [];
    $domain = \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_domain') ?
      \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_domain') : 'meet.jit.si';    $user = User::load(\Drupal::currentUser()->id());
    $username = $user->getAccountName();
    $mail = $user->getEmail();
    if ($user->get('user_picture')->entity) {
      $picture = file_create_url($user->get('user_picture')->entity->getFileUri());
    }
    else {
      $theme = theme_get_setting('logo');
      $picture = \Drupal::request()->getSchemeAndHttpHost() . $theme['url'];
    }

    $url = Url::fromRoute('<current>');
    //$url = Url::fromUri('https://' . $domain . '/' . $room, ['attributes' => ['id' => 'roomlink', 'title' => $this->t('Right click to copy')]]);
    $content = [
      '#theme' => 'jitsi_video_page_join',
      '#room' => $roomName,
      '#user' => $username,
      '#link_external' => Link::fromTextAndUrl('Room link', $url),
      '#attached' => [
        'drupalSettings' => [
          'mav_jitsi' => [
            'domain' => $domain,
            'room' => $room,
            'user' => $username,
            'email' => $mail,
            'avatar' => $picture,
          ],
        ],
        'library' => ['mav_jitsi/video'],
      ],
    ];
    return $content;

  }
}
