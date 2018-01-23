<?php

namespace Drupal\imprecise_date\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\datetime_range\Plugin\Field\FieldType\DateRangeItem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\datetime_range\Plugin\Field\FieldWidget\DateRangeDefaultWidget;

/**
 * Plugin implementation of the 'datetime_default' widget.
 *
 * @FieldWidget(
 *   id = "imprecise_date_default",
 *   label = @Translation("Imprecise Date"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class ImpreciseDateDefaultWidget extends ImpreciseDateBaseWidget implements ContainerFactoryPluginInterface {

  /**
     * The date format storage.
     *
     * @var \Drupal\Core\Entity\EntityStorageInterface
     */
    protected $dateStorage;

    /**
     * {@inheritdoc}
     */
    public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityStorageInterface $date_storage) {
      parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, $date_storage);

      $this->dateStorage = $date_storage;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
      return new static(
        $plugin_id,
        $plugin_definition,
        $configuration['field_definition'],
        $configuration['settings'],
        $configuration['third_party_settings'],
        $container->get('entity_type.manager')->getStorage('date_format')
      );
    }

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
      $element = parent::formElement($items, $delta, $element, $form, $form_state);

      $date_type = 'date';
      $time_type = 'none';
      $date_format = $this->dateStorage->load('html_date')->getPattern();
      $time_format = '';

      $element['value'] += [
        '#date_date_format' => $date_format,
        '#date_date_element' => $date_type,
        '#date_date_callbacks' => [],
        '#date_time_format' => $time_format,
        '#date_time_element' => $time_type,
        '#date_time_callbacks' => [],
      ];

      $element['end_value'] += [
        '#date_date_format' => $date_format,
        '#date_date_element' => $date_type,
        '#date_date_callbacks' => [],
        '#date_time_format' => $time_format,
        '#date_time_element' => $time_type,
        '#date_time_callbacks' => [],
      ];

      return $element;
    }

}
