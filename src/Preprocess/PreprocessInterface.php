<?php

namespace Drupal\dbt\Preprocess;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Preprocess actions interface.
 */
interface PreprocessInterface extends ContainerFactoryPluginInterface {

  /**
   * Execute preprocess.
   *
   * @param array $variables
   */
  public function execute(array &$variables);

}
