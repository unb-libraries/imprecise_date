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

    $element['mean_value'] = [
      '#title' => $this->t('Mean date'),
    ] + $element['value'];

    if ($items[$delta]->mean_date) {
      /** @var \Drupal\Core\Datetime\DrupalDateTime $mean_date */
      $mean_date = $items[$delta]->mean_date;
      $element['mean_value']['#default_value'] = $this->createDefaultValue($mean_date, $element['mean_value']['#date_timezone']);
    }

    return $element;
  }

}
