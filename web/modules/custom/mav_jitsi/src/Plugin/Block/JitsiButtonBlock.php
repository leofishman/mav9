<?php

namespace Drupal\mav_jitsi\Plugin\Block;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'video room button for Jitsi service' .
 *
 * @Block(
 *   id = "jitsi_button_block",
 *   admin_label = @Translation("Jitsi video button"),
 *   category = @Translation("MAV jitsi block")
 * )
 */
class JitsiButtonBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $items = [];
    $items['title'] = '';
    $items['id'] = 'jitsi_buttons';

    $items['button'] = "<div id='tour-jitsi'><a id='jitsi_new_meeting' class='button'  "
      . "href='#'><span class='jitsi_start_button'></span> " . $this->t('Start New Meeting') . "</a></div>";

    $items['form'] = \Drupal::formBuilder()->getForm('Drupal\mav_jitsi\Form\Join');
    $width = \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_window_width') ?
      \Drupal::config('mav_jitsi.settings')->get('mav_jitsi_window_width') : 800;

    return [
      '#items' => $items,
      '#theme' => 'jitsi_video',
      '#attached' => [
        'drupalSettings' => [
          'mav_jitsi_block' => [
            'width' => $width,
          ],
        ],
        'library' => 'mav_jitsi/block',
      ],
      '#cache' => [
        'tags' => ['jitsi_button_block'],
        'contexts' => [],
      ],
    ];
  }

  /**
   * Function blockAccess.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Object AccountInterface.
   *
   * @return \Drupal\Core\Access\AccessResult|\Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
   *   Return the AccessResult.
   */
  protected function blockAccess(AccountInterface $account) {
    if ($account->hasPermission('jitsi video')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}
