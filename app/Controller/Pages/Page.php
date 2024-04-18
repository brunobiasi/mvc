<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page {

    private static function getHeader() {
        return View::render('pages/header');
    }

    private static function getFooter() {
        return View::render('pages/footer');
    }

    private static function getPaginationLink($queryParams, $page, $url, $label = null) {
        $queryParams['page'] = $page['pagina'];

        $link = $url . '?' . http_build_query($queryParams);

        return View::render('pages/pagination/link', [
            'page' => $label ?? $page['pagina'],
            'link' => $link,
            'active' => $page['atual'] ? 'active' : ''
        ]);
    }

    public static function getPagination($request, $obPagination) {
        $pages = $obPagination->getPages();

        if (count($pages) <= 1) return '';

        $links = '';

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->getQueryParams();

        $currentPage = $queryParams['page'] ?? 1;

        $limit = getenv('PAGINATION_LIMIT');

        $middle = ceil($limit / 2);

        $start = $middle > $currentPage ? 0 : $currentPage - $middle;

        $limit = $limit + $start;

        if ($limit > count($pages)) {
            $diff = $limit - count($pages);
            $start = $start - $diff;
        }

        if ($start > 0) {
            $links .= self::getPaginationLink($queryParams, reset($pages), $url, '<<');
        }

        foreach ($pages as $page) {
            if ($page['pagina'] <= $start) continue;

            if ($page['pagina'] > $limit) {
                $links .= self::getPaginationLink($queryParams, end($pages), $url, '>>');
                break;
            }

            $links .= self::getPaginationLink($queryParams, $page, $url);
        }

        return View::render('pages/pagination/box', [
            'links' => $links
        ]);
    }

    public static function getPage($title, $content) {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }
}
