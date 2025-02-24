<?php
/*
Plugin Name: Tech Updates USA SEO Optimizer
Plugin URI: https://techupdatesusa.com/
Description: A custom SEO plugin to optimize Tech Updates USA for Google rankings.
Version: 1.1
Author: Payel Miah
Author URI: https://techupdatesusa.com/
License: GPL2
*/

// Prevent direct access
defined('ABSPATH') or die('No direct access allowed.');

// Auto-generate SEO title and description if not manually set
function techupdatesusa_auto_generate_seo($post_id) {
    if (get_post_status($post_id) !== 'publish') {
        return;
    }
    
    $post = get_post($post_id);
    $title = get_the_title($post_id);
    $content = wp_strip_all_tags($post->post_content);
    $excerpt = substr($content, 0, 160);
    
    if (!get_post_meta($post_id, '_techupdatesusa_seo_title', true)) {
        update_post_meta($post_id, '_techupdatesusa_seo_title', $title . ' | Tech Updates USA');
    }
    
    if (!get_post_meta($post_id, '_techupdatesusa_seo_description', true)) {
        update_post_meta($post_id, '_techupdatesusa_seo_description', $excerpt);
    }
}
add_action('save_post', 'techupdatesusa_auto_generate_seo');

// Add SEO meta tags to the frontend
function techupdatesusa_add_seo_meta_tags() {
    if (is_single()) {
        global $post;
        $seo_title = get_post_meta($post->ID, '_techupdatesusa_seo_title', true);
        $seo_description = get_post_meta($post->ID, '_techupdatesusa_seo_description', true);
        if ($seo_title) {
            echo '<title>' . esc_html($seo_title) . '</title>' . "\n";
        }
        if ($seo_description) {
            echo '<meta name="description" content="' . esc_attr($seo_description) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'techupdatesusa_add_seo_meta_tags');

// Generate XML Sitemap
function techupdatesusa_generate_sitemap() {
    $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
    $sitemap = "<?xml version='1.0' encoding='UTF-8'?>\n";
    $sitemap .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
    foreach ($posts as $post) {
        $sitemap .= "<url>\n";
        $sitemap .= "<loc>" . get_permalink($post->ID) . "</loc>\n";
        $sitemap .= "<lastmod>" . get_the_modified_time('Y-m-d', $post->ID) . "</lastmod>\n";
        $sitemap .= "</url>\n";
    }
    $sitemap .= "</urlset>";
    file_put_contents(ABSPATH . 'sitemap.xml', $sitemap);
}
add_action('publish_post', 'techupdatesusa_generate_sitemap');
add_action('publish_page', 'techupdatesusa_generate_sitemap');

?>
