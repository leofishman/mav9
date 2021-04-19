<?php

namespace Drupal\mav_jitsi\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

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
    $base_meet_url = \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_domain') ?
      \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_domain') : 'meet.jit.si';
    foreach ($items as $delta => $item) {
      if ($item->jitsi_conf) {
        $element[$delta]['jitsi_conf'] = [
          '#type' => 'item',
          '#title' => $this->t('jitsi conf'),
          '#markup' => 'https://' . $base_meet_url . '/' . $item->jitsi_conf,
        ];
      }

    }

    return $element;
  }

}
