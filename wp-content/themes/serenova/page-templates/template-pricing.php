<?php
/**
* Template Name: Pricing
**/

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['post'] = new Timber\Post();
Timber::render('core/page.twig', $context);
