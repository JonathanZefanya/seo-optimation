<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

defined('ALTUMCODE') || die();

class Sitemap extends Controller {

    public function index() {

        /* Set the header as xml so the browser can read it properly */
        header('Content-Type: text/xml');

        $page = isset($this->params[0]) ? $this->params[0] : null;

        /* Different answers for different parts */
        switch($page) {

            /* Sitemap index */
            case null:

                /* Calculate the needed sitemaps */
                $total_sitemaps = 1;

                /* Main View */
                $data = [
                    'total_sitemaps' => $total_sitemaps
                ];

                $view = new \Altum\View('sitemap/sitemap_index', (array) $this);

                break;

            /* Output base pages like the homepage, register..etc*/
            case 1:

                /* Get all pages & categories */
                if(settings()->content->pages_is_enabled) {
                    $pages = db()->where('type', 'internal')->where('is_published', 1)->get('pages', null, ['url', 'language']);
                    $pages_categories = db()->get('pages_categories', null, ['url', 'language']);
                }

                /* Get all blog posts & blog categories */
                if(settings()->content->blog_is_enabled) {
                    $blog_posts = db()->where('is_published', 1)->get('blog_posts', null, ['url', 'language']);
                    $blog_posts_categories = db()->get('blog_posts_categories', null, ['url', 'language']);
                }

                /* Main View */
                $data = [
                    'pages' => $pages ?? null,
                    'pages_categories' => $pages_categories ?? null,
                    'blog_posts' => $blog_posts ?? [],
                    'blog_posts_categories' => $blog_posts_categories ?? null,
                ];

                $view = new \Altum\View('sitemap/sitemap_1', (array) $this);

                break;

            default:

                redirect('not-found');

                break;
        }


        echo $view->run($data);

        die();
    }

}
