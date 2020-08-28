<?php

/**
 * @file
 * Contains myentity.page.inc.
 *
 * Page callback for Myentity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Myentity templates.
 *
 * Default template: myentity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_myentity(array &$variables) {
  // Fetch Myentity Entity Object.
  $myentity = $variables['elements']['#myentity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
