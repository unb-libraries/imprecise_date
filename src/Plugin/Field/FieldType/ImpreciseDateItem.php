<?php

namespace Drupal\imprecise_date\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\datetime\DateTimeComputed;
use Drupal\datetime_range\Plugin\Field\FieldType\DateRangeItem;

/**
 * Plugin implementation of the 'daterange' field type.
 *
 * @FieldType(
 *   id = "imprecise_date",
 *   label = @Translation("Imprecise Date"),
 *   description = @Translation("Create and store imprecise dates."),
 *   default_widget = "imprecise_date_default",
 *   default_formatter = "imprecise_date_default",
 * )
 */

class ImpreciseDateItem extends DateRangeItem {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('start_date')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = parent::schema($field_definition);

    $schema['columns']['mean_value'] = [
      'description' => 'The mean date value.',
    ] + $schema['columns']['value'];

    $schema['indexes']['mean_value'] = ['mean_value'];

    return $schema;
  }

   /**
    * {@inheritdoc}
    */
   public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
     $properties = parent::propertyDefinitions($field_definition);

     $properties['mean_value'] = DataDefinition::create('datetime_iso8601')
       ->setLabel(t('Mean date value'))
       ->setRequired(TRUE);

     $properties['mean_date'] = DataDefinition::create('any')
       ->setLabel(t('Computed end date'))
       ->setDescription(t('The computed end DateTime object.'))
       ->setComputed(TRUE)
       ->setClass(DateTimeComputed::class)
       ->setSetting('date source', 'mean_value');

     return $properties;
   }

   /**
    * {@inheritdoc}
    */
   public function onChange($property_name, $notify = TRUE) {
     // Enforce that the computed date is recalculated.
     if ($property_name == 'mean_value') {
       $this->mean_date = NULL;
     }
     parent::onChange($property_name, $notify);
   }

}
