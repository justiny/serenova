<?php
/**
* Template Name: Blog Listing
**/

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}
$context = Timber::get_context();
$args = array(
    'posts_per_page' => 9,
    'paged' => $paged
);
$context['posts'] = new Timber\PostQuery($args);

Timber::render('pages/blog-template.twig', $context);
