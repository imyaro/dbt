<?php

namespace Drupal\dbt\Preprocess;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\dbt\Annotation\Preprocess;

class PreprocessDiscover extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $additionalTypes = $this->getDefinedTypes();
    $additionalNamespaces = [];

    foreach ($additionalTypes as $additionalType) {
      $additionalNamespaces[] = __NAMESPACE__ . "\\$additionalType";
    }

    parent::__construct(
      'Preprocess',
      $this->getNamespacesObject(),
      \Drupal::service('module_handler'),
      PreprocessInterface::class,
      Preprocess::class,
      $additionalNamespaces
    );
  }

  /**
   * Namespaces map object.
   *
   * @return \ArrayObject
   */
  protected function getNamespacesObject() {
    return new \ArrayObject([
      // @todo make it usable for sub-themes.
      'Drupal\\dbt' => $this->getRoot()
    ]);
  }

  /**
   * Theme source root.
   */
  protected function getRoot() {
    // @todo make it usable for sub-themes.
    return drupal_get_path('theme', 'dbt') . '/src';
  }

  /**
   * Get available types.
   *
   * @return string[]
   */
  protected function getDefinedTypes() : array {
    $types = [];

    $directoryIterator = new \DirectoryIterator(__DIR__);
    foreach ($directoryIterator as $directoryIteratorItem) {
      if (!$directoryIteratorItem->isDot() && $directoryIteratorItem->isDir()) {
        $types[] = $directoryIteratorItem->getFilename();
      }
    }

    return $types;
  }

  /**
   * Finds plugin definitions.
   *
   * @return array
   *   List of definitions to store in cache.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidDeriverException
   *   Thrown.. Never.
   */
  protected function findDefinitions() {
    $definitions = $this->getDiscovery()->getDefinitions();
    foreach ($definitions as $plugin_id => &$definition) {
      $this->processDefinition($definition, $plugin_id);
    }
    $this->alterDefinitions($definitions);

    return $definitions;
  }

  /**
   * Provides prreprocess instance.
   *
   * @param array $options
   *
   * @return \Drupal\dbt\Preprocess\PreprocessInterface | false
   */
  public function getInstance(array $options) {
    if (isset($options['id'])) {
      $definitions = $this->getDefinitions();
      $definition = $definitions[$options['id']] ?? FALSE;
      if ($definition) {
        return $this
          ->getFactory()
          ->createInstance($options['id']);
      }
    }

    return FALSE;
  }

}
