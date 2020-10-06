<?php

namespace Drupal\anb_catalog;

/**
 * Catalog helper class with useful functions.
 *
 * @package Drupal\anb_catalog
 */
class CatalogManager {

  /**
   * Get top level categories for given vocabulary.
   */
    public function getTopLevelCategories($vid) {
      $categories = [];

      $catalog_tree = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree('catalog', 0 ,1, false);

      foreach ($catalog_tree as $term) {
        $categories[$term->tid] = $term->name;
      }

      return $categories;
    }

}
