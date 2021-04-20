<?php

namespace Drupal\mav_jitsi\Plugin\Field\FieldWidget;


use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'mav_jitsi_conference' field widget.
 *
 * @FieldWidget(
 *   id = "mav_jitsi_conference",
 *   label = @Translation("jitsi conference"),
 *   field_types = {"mav_jitsi_conference"},
 * )
 */
class ConferenceWidget extends WidgetBase {

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
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['jitsi_conf'] = [
      '#type' => 'textfield',
      '#title' => $this->t('jitsi conf'),
      '#default_value' => isset($items[$delta]->jitsi_conf) ? $items[$delta]->jitsi_conf : $this->fieldDefinition->getUniqueIdentifier(),
      '#size' => 20,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'mav-jitsi-conference-elements';
    $element['#attached']['library'][] = 'mav_jitsi/mav_jitsi_conference';
    $element['#attached']['library'][] = 'mav_jitsi/video';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }
}
