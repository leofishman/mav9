<?php

namespace Drupal\mav_jitsi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure mav_jitsi settings for this site.
 */
class Settings extends ConfigFormBase {

  /**
   * Settings.
   *
   * @var string Config settings
   */
  const SETTINGS = 'mav_jitsi.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mav_jitsi_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['mav_jitsi_domain'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Signal server'),
      '#attributes' => ['placeholder' => $this->t('domain')],
      '#default_value' => $config->get('mav_jitsi_domain') ? $config->get('mav_jitsi_domain') : 'meet.jit.si',
      '#description' => $this->t('Server that manage video connections'),
    ];

    $form['mav_jitsi_view'] = [
      '#type' => 'select',
      '#title' => $this->t('Jitsi view mode'),
      '#required' => TRUE,
      '#options' => [
        'full' => $this->t("Full screen"),
        'framed' => $this->t("Framed"),
      ],
      '#size' => 1,
      '#default_value' => $config->get('mav_jitsi_view') ? $config->get('mav_jitsi_view') : 'full',
      '#description' => $this->t('Display full screen or framed into your site layout'),
    ];

    $form['mav_jitsi_window_width'] = [
      '#type' => 'number',
      '#min' => 0,
      '#max' => 3000,
      '#title' => $this->t('Jitsi window width'),
      '#required' => TRUE,
      '#default_value' => $config->get('mav_jitsi_window_width') ? $config->get('mav_jitsi_window_width') : 800,
      '#description' => $this->t('size of popup window'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->configFactory->getEditable(static::SETTINGS)
      ->set('mav_jitsi_domain', $form_state->getValue('mav_jitsi_domain'))
      ->set('mav_jitsi_view', $form_state->getValue('mav_jitsi_view'))
      ->set('mav_jitsi_window_width', $form_state->getValue('mav_jitsi_window_width'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
