<?php

namespace Drupal\mav_jitsi\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;

/**
 * Provides a form for mav_jitsi join room.
 *
 * @category Join_Class
 * @package Drupal\mav_jitsi\Form
 * @license https://opensource.org/licenses/MIT MIT License
 */
class Join extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mav_jitsi_join_room';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['room'] = [
      '#type' => 'textfield',
      '#id' => 'roomname',
      '#size' => 25,
      '#title' => $this->t('Join existing meeting'),
      '#attributes' => ['placeholder' => $this->t('Enter room name to join')],
      '#required' => FALSE,
      '#prefix' => '<div class="container-inline">',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Join'),
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => [$this, 'open'],
      ],
    ];

    $form['item'] = [
      '#type' => 'item',
      '#markup' => "<a id='joinroom' href='#'></a>",
    ];

    return $form;
  }

  /**
   * Function Ajax callback.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state values.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Return HTML Ajax Response.
   */
  public function open(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new InvokeCommand('#joinroom', 'click', []));
    return $response;
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   *
   * @param array $form
   *   Form Parameter.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form State parameter.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Submit Form Video Join.
   *
   * @param array $form
   *   Form parameter.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form State parameter.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
