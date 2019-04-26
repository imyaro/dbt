<?php

namespace Drupal\dbt;

use Drupal\dbt\Preprocess\PreprocessDiscover;
use Drupal\dbt\Preprocess\PreprocessInterface;

/**
 * dbt theme class.
 */
class Theme {

  protected static $themeInstance;

  /**
   * Preprocesses definitions array.
   *
   * @var array
   */
  protected $preprocesses;

  /**
   * Preprocess discovery.
   *
   * @var \Drupal\dbt\Preprocess\PreprocessDiscover
   */
  protected $preprocessDiscover;

  /**
   * Process given variables using the theme engine.
   *
   * @param array $variables
   *   Variables array
   */
  public static function preprocess(array &$variables) {
    // @todo allow to override Theme object.
    if (static::$themeInstance === NULL) {
      static::$themeInstance = new static();
    }

    static::$themeInstance->execute('preprocess', $variables);
  }

  /**
   * Execute action.
   *
   * @param string $action
   *   Action identifier
   * @param array $context
   *   Context.
   */
  protected function execute(string $action, array &$context)  {
    switch ($action) {
      case 'preprocess':
        $themeHookOriginal = $context['theme_hook_original'];
        if ($themeHookOriginal === 'field') {
          $entityType = $context['entity_type'];
          $fieldName = $context['field_name'];

          $preprocessId = "{$themeHookOriginal}_{$entityType}_$fieldName";
        }
        else {
          $preprocessId = $themeHookOriginal;
        }

        $preprocess = $this->getPreprocess($preprocessId);

        if ($preprocess) {
          $preprocess->execute($context);
        }
        break;
    }
  }

  /**
   * Get preprocess instance by hook.
   *
   * @param string $hook
   *   Hook ID.
   *
   * @return \Drupal\dbt\Preprocess\PreprocessInterface | false
   *   Preprocess Instance or FALSE if not exists.
   */
  protected function getPreprocess(string $hook) {
    if ($this->preprocessDiscover === NULL) {
      $this->preprocessDiscover = new PreprocessDiscover();
    }

    return $this->preprocessDiscover->getInstance(['id' => $hook]);
  }

}
