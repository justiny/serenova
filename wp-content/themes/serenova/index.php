<?php
// use Timber\Timber;
// uncomment this if issue on archives
$context = Timber::get_context();

// Single Posts
if ( is_single() ) :

    $post = new TimberPost();
    $context['current_user'] = new Timber\User();
    $context['post'] = $post;
    $currentID = get_the_ID();

    if (!isset($paged) || !$paged){
      $paged = 1;
    }

    // this is important to get the object of the query
    $post_object = get_queried_object();

    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'no_found_rows' => true,
      'posts_per_page' => 3,
      'date_query' => array(
        array(
            'before' => $post_object->post_date,  // Get posts after the current post, use current post post_date
            'inclusive' => false, // Don't include the current post in the query
        )
      )
    );
    $context['posts'] = Timber::get_posts($args);

    $args = array(
      'post_type' => 'location',
      'posts_per_page' => 3,
      'post_status' => 'publish',
      'no_found_rows' => true,
      'date_query' => array(
        array(
            'before' => $post_object->post_date,  // Get posts after the current post, use current post post_date
            'inclusive' => false, // Don't include the current post in the query
        )
      )
    );
    $context['locations'] = Timber::get_posts($args);

    $args = array(
      'post_type' => 'press',
      'posts_per_page' => 3,
      'post_status' => 'publish',
      'no_found_rows' => true,
      'date_query' => array(
        array(
            'before' => $post_object->post_date,  // Get posts after the current post, use current post post_date
            'inclusive' => false, // Don't include the current post in the query
        )
      )
    );
    $context['press'] = Timber::get_posts($args);

    $args = array(
      'post_type' => 'product',
      'posts_per_page' => 3,
      'post_status' => 'publish',
      'no_found_rows' => true,
      'date_query' => array(
        array(
            'before' => $post_object->post_date,  // Get posts after the current post, use current post post_date
            'inclusive' => false, // Don't include the current post in the query
        )
      )
    );
    $context['product'] = Timber::get_posts($args);

    // $context['extra'] = Timber::get_terms('category');

    if ( post_password_required( $post->ID ) ) {
      Timber::render( 'core/single-password.twig', $context );
      } else {
      Timber::render( array(
        'core/single-' . $post->ID . '.twig',
        'core/single-' . $post->post_type . '.twig',
        'core/single.twig' ),
        $context
      );
    }

// Pages
elseif (is_page() ) :

    $post = new TimberPost();
    $context['post'] = $post;

    if (!isset($paged) || !$paged){
      $paged = 1;
    }

    $args = array(
      'posts_per_page' => -1,
      'post_type' => 'location',
      'orderby' => 'title',
      'order' => 'ASC',
      'paged' => $paged
    );
    $context['location'] = Timber::get_posts($args);

    Timber::render( array(
      'core/page-' . $post->post_name . '.twig',
      'core/page.twig' ),
    $context );

elseif (is_tax() ) :

  $context['post'] = new TimberPost();

  if (!isset($paged) || !$paged){
      $paged = 1;
    }

    // Grab resource industry reports
  $query = array(
  'post_type' => 'resource',
  'paged' => $paged,
  'tax_query' => array(
      array(
        'taxonomy' => 'resource-category',
        'field' => 'slug', //can be set to ID
        'terms' => 'industry-reports'
      )
    )
  );
  $context['industry'] = Timber::get_posts($query);

  $query = array(
  'post_type' => 'resource',
  'paged' => $paged,
  'tax_query' => array(
      array(
        'taxonomy' => 'resource-category',
        'field' => 'slug', //can be set to ID
        'terms' => 'data-sheets'
      )
    )
  );
  $context['data_sheets'] = Timber::get_posts($query);

  // Grab all resource posts and all terms
  $term = $wp_query->queried_object;
  $query = array(
    'post_type' => 'resource',
    'orderby' => 'date',
    'order'   => 'DESC',
    'paged' => $paged,
    'tax_query' => array(
        array(
            'taxonomy' => 'resource-category',
            'field' => 'slug', //can be set to ID
            'terms' => $term->slug
        )
    )
  );
  $context['non_industry'] = Timber::get_posts($query);

  // this will show the tabs for each cpt
  $context['categories'] = Timber::get_terms('resource-category');
  $context['product_categories'] = Timber::get_terms('product-category');
  $context['press_categories'] = Timber::get_terms('press-category');

  // this will display the title
  $context['term_page'] = new TimberTerm();

  if ($post) :
    Timber::render( array(
      'categories/category-' . $post->post_type . '.twig',
      'core/category.twig'
    ),
    $context);
  else :
    Timber::render( array(
      'core/category.twig'
    ),
    $context);
  endif;

elseif (is_archive() ) :

    global $paged;

    $context['resource_featured'] = new Timber\PostQuery( get_field('resource_featured', 'option') );

    $post = new TimberPost();
    $context['post'] = $post;

    // this will show the tabs
    $context['categories'] = Timber::get_terms('resource-category');
    $context['press_categories'] = Timber::get_terms('press-category');
    $context['product_categories'] = Timber::get_terms('product-category');

    // this will display the title
    $context['term_page'] = new TimberTerm();

    if (!isset($paged) || !$paged){
      $paged = 1;
    }

    $args = array(
      'posts_per_page' => 9,
      'post_type' => 'press',
      'order'   => 'DESC',
      'paged' => $paged
    );
    // $context['press'] = Timber::get_posts($args);
    $context['press'] = new Timber\PostQuery($args);

    $args = array(
      'posts_per_page' => 9,
      'post_type' => 'product',
      'paged' => $paged
    );
    // $context['product'] = Timber::get_posts($args);
    $context['product'] = new Timber\PostQuery($args);

    $args = array(
      'posts_per_page' => 9,
      'post_type' => 'resource',
      'orderby' => 'date',
      'order'   => 'DESC',
      'paged' => $paged
    );
    // $context['resource'] = Timber::get_posts($args);
    $context['resource'] = new Timber\PostQuery($args);

    Timber::render( array(
      'archives/archive-' . $post->post_type . '.twig'
    ),
    $context);


elseif (is_search() ) :

    $post = new TimberPost();
    $context['post'] = $post;

    $context['categories'] = Timber::get_terms('category');
    $context['press_categories'] = Timber::get_terms('press-category');
    $context['product_categories'] = Timber::get_terms('product-category');
    $context['resource_categories'] = Timber::get_terms('resource-category');

    $templates = array(
      'core/search.twig'
    );
    $context['search_title'] = 'Search results for ' . get_search_query();

  Timber::render($templates, $context);

elseif (is_404() ) :

    Timber::render(array(
      'core/404.twig'
    ), $context);

endif;
