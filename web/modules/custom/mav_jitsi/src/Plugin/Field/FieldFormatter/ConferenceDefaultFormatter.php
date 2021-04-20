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
    $url = Url::fromUri('https://' . $domain . '/' . $room, ['attributes' => ['id' => 'roomlink', 'title' => $this->t('Right click to copy')]]);
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
