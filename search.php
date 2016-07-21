<?php
/**
 * The template for displaying search results pages.
  *
  * Methods for TimberHelper can be found in the /lib sub-directory
  *
  * @package Simple Grey
  * @subpackage  Timber
  * @since    Timber 0.1
  */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['search_query'] = get_search_query();
Timber::render( array( 'search.twig' ), $context );
