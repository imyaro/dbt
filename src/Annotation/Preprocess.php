<?php

namespace Drupal\dbt\Annotation;


use Drupal\Component\Annotation\Plugin;

/**
 * @Annotation
 */
class Preprocess extends Plugin {

  /**
   * Id of the preprocess.
   *
   * @var string
   */
  public $id;

}
