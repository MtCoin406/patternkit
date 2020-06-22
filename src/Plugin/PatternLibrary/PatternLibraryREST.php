<?php

namespace Drupal\patternkit\Plugin\PatternLibrary;

use Drupal\Core\Extension\Extension;
use Drupal\Core\State\StateInterface;
use Drupal\patternkit\Entity\PatternInterface;
use Drupal\patternkit\PatternEditorConfig;
use Drupal\patternkit\PatternLibrary;
use Drupal\patternkit\PatternLibraryPluginDefault;

/**
 * @PatternLibrary(
 *   id = "rest",
 * )
 */
class PatternLibraryREST extends PatternLibraryPluginDefault {

  /**
   * The state key/value store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a PatternRESTCollectionRenderer.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state key/value store.
   *
   * {@inheritDoc}
   */
  public function __construct($root, $parser, $config_factory, $configuration, $plugin_id, $plugin_definition, StateInterface $state) {
    parent::__construct($root, $parser, $config_factory, $configuration, $plugin_id, $plugin_definition);
    $this->state = $state;
  }

  /**
   * {@inheritDoc}
   */
  public function getEditor(PatternInterface $pattern = NULL,
    PatternEditorConfig $config = NULL) {
    // @todo Implement getEditor() method.
  }

  /**
   * {@inheritDoc}
   */
  public function getMetadata(Extension $extension, PatternLibrary $library, $path): array {
    // @todo Implement getMetadata() method.
    return [];
  }

  /**
   * {@inheritdoc}
   *
   * This class evaluates the aggregation enabled/disabled condition on a group
   * by group basis by testing whether an aggregate file has been made for the
   * group rather than by testing the site-wide aggregation setting. This allows
   * this class to work correctly even if modules have implemented custom
   * logic for grouping and aggregating files.
   *
   * @throws \Exception
   */
  public function render(array $assets): array {
    $elements = [];
    /** @var PatternInterface $pattern */
    foreach ($assets as $pattern) {
      $config = $pattern->config ?? [];
      if (empty($config['presentation_style']) || empty($config['instance_id'])) {
        return [];
      }
      $pattern_name = $pattern->label();
      if ($config['presentation_style'] === 'webcomponent') {
        $elements[] = "<$pattern_name-pattern></$pattern_name-pattern>";
      }
      else {
        $filename = "public://patternkit/$pattern_name/{$config['instance_id']}/body.html";
        $elements[] = file_get_contents($filename);
      }
    }
    return $elements;
  }

}
