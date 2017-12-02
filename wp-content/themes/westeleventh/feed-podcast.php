<?php
/**
 * Sets proper headers for RSS Feeds using Timber Library and Twig Templating Engine
 *   Fallbacks:
 *     1. application/rss+xml,
 *     2. application/rdf+xml,
 *     3. application/atom+xml,
 *     4. application/xml,
 *     5. text/xml
 */
header("Content-Type: application/rss+xml, application/rdf+xml, application/atom+xml, application/xml, text/xml");

/**
 * The template for displaying the RSS feed for the 'podcast' taxonomy
 *
 * @package  WordPress
 * @subpackage  Timber
 */

# Timber Context
$context = Timber::get_context();
$context['podcast'] = new TimberTerm(get_query_var('podcast'));
$context['posts'] = Timber::get_posts('\WestEleventh\Episode');

# Render View
Timber::render('feed-podcast.twig', $context);
