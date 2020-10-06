<?php

namespace Drupal\anb_catalog;

use Drupal\Core\Render\Markup;
use Drupal\Core\Url;

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

  /**
   * Get childs-by-city terms for given term or it's parent term.
   *
   * @param $tid int
   * @return array
   */
    public function getCitiesTermsList($term) {
      $cities_block = [
        'block_title' => NULL,
        'cities' => NULL,
      ];

      $term_storage = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term');

      // Set parent term id variable.
      $parent_id = intval($term->parent->target_id);

      if ($parent_id === 0) {
        $parent_id = intval($term->id());
      }

      // Get cities block title from parent term.
      $parent_term = $term_storage->load($parent_id);

      $block_title = $parent_term->field_cities_block_title->value;
      $markup = new Markup();
      $cities_block['block_title'] = $markup->create($block_title);

      // Get children cities terms.
      $child_terms = $term_storage->loadTree('catalog', $parent_id , 2, false);

      if (empty($child_terms)) {
        return [];
      }

      foreach ($child_terms as $child_term) {
        $first_character = mb_substr($child_term->name, 0, 1);

        $cities_block['cities'][$first_character][] = [
          'name' => $child_term->name,
          'url' => Url::fromRoute(
            'entity.taxonomy_term.canonical',
            ['taxonomy_term' => $child_term->tid], ['absolute' => false])
            ->toString()
        ];

        sort($cities_block['cities'][$first_character]);
      }

      ksort($cities_block['cities']);

      return $cities_block;
    }
}
