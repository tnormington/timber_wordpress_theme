<?php

$context = Timber::get_context();
$context['post'] = Timber::get_post(4);

$project_args = array(
    'post_type' => 'project',
    'post_count' => 3,
);
$context['projects'] = Timber::get_posts($project_args);

$post_args = array(
    'post_type' => 'post',
    'post_count' => 3,
);

$context['posts'] = Timber::get_posts($post_args);

Timber::render( 'front-page.twig', $context );
