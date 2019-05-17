<?php

namespace Drupal\dbt\Preprocess;

use Drupal\Core\Entity\EntityInterface;

abstract class PreprocessEntityBase extends PreprocessBase {

  /**
   * Entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * {@inheritDoc}
   */
  public function execute(array &$variables) {
    parent::execute($variables);

    $hook = $variables['theme_hook_original'];

    if (isset($variables['elements']["#$hook"]) && is_a($variables['elements']["#$hook"], EntityInterface::class)) {
      $this->entity = $variables['elements']["#$hook"];

      if ($this->entity) {
        $bundle = $this->entity->bundle();
        $viewMode = $this->variables["elements"]["#view_mode"];

        $methods[] = 'preprocess';
        $methods[] = $this->formatMethod("preprocess_$bundle");
        $methods[] = $this->formatMethod("preprocess_$viewMode");
        $methods[] = $this->formatMethod("preprocess_{$bundle}_$viewMode");

        foreach ($methods as $method) {
          if (method_exists($this, $method)) {
            $this->{$method}();
          }
        }
      }
    }
  }

}
