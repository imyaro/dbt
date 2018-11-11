<?php

namespace Drupal\dbt\Preprocess;

use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base preprocess class.
 */
abstract class PreprocessBase extends PluginBase implements PreprocessInterface {

  /**
   * Variables for preprocess.
   *
   * @var array
   */
  protected $variables;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Preprocess method base. Must be called before any other preprocess methods.
   *
   * @param array $variables
   *   Variables array.
   */
  public function execute(array &$variables) {
    $this->variables = &$variables;
  }

  protected function formatMethod($rawMethod) {
    return preg_replace_callback('/_+(.?)/', function ($part) {
      return strtoupper($part[1]);
    }, $rawMethod);
  }
}
