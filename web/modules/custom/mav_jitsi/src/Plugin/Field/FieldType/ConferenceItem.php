<?php

namespace Drupal\mav_jitsi\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'mav_jitsi_conference' field type.
 *
 * @FieldType(
 *   id = "mav_jitsi_conference",
 *   label = @Translation("conference"),
 *   description = @Translation("Field for creating jitsi conferences"),
 *   category = @Translation("Mav"),
 *   default_widget = "mav_jitsi_conference",
 *   default_formatter = "mav_jitsi_conference_default"
 * )
 */
class ConferenceItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->jitsi_conf !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['jitsi_conf'] = DataDefinition::create('string')
      ->setLabel(t('jitsi conf'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    // @todo Add more constraints here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'jitsi_conf' => [
        'type' => 'varchar',
        'length' => 255,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    $random = new Random();

    $values['jitsi_conf'] = $random->word(mt_rand(1, 255));

    return $values;
  }

}
