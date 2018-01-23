<?php

namespace Drupal\imprecise_date\Plugin\Field\FieldWidget;

use Drupal\datetime_range\Plugin\Field\FieldWidget\DateRangeWidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Base class for the 'daterange_*' widgets.
 */
class ImpreciseDateBaseWidget extends DateRangeWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $element['date_imprecise_chk'] = [
      '#type' => 'checkbox',
      '#title' => 'Date Is Imprecise',
    ];

    $element['end_value']['#states'] = [
      'visible' => [
        ':input[name="date_imprecise_chk"]' => array('checked' => TRUE),
      ],
    ];

    $element['date_imprecise_chk2'] = [
      '#type' => 'textfield',
      '#title' => 'Date Is Imprecise',
      '#states' => [
        'visible' => [
          ':input[name="date_imprecise_chk"]' => array('checked' => TRUE),
        ],
      ]
    ];

    if ($items[$delta]->mean_date) {
      /** @var \Drupal\Core\Datetime\DrupalDateTime $mean_date */
      $mean_date = $items[$delta]->mean_date;
      $element['mean_value']['#default_value'] = $this->createDefaultValue($mean_date, $element['mean_value']['#date_timezone']);
    }

    return $element;
  }

}
