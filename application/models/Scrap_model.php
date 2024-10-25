<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'assets/simplehtmldom/simple_html_dom.php';
class Scrap_model extends CI_Model
{
    public function main_scrap($main_url, $from)
    {
        switch ($from) {
            case 'vimanews':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div.latest__wrap div.latest__item');
                    $output = [];
                    for ($i = 0; $i < 5; $i++) {
                        $link = $get_link[$i]->find('div.latest__right h2.latest__title a', 0)->href;
                        $data_result = $this->get_article($link, $from);
                        $output[] = $data_result;
                    }
                }

                return $output;
                break;
            case 'radartegal':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('section div.bottom-15 div.media-content h2.media-heading');


                    $output = [];
                    for ($i = 0; $i < 5; $i++) {
                        $link = $get_link[$i]->find('a', 0)->attr['href'];
                        $data_result = $this->get_article($link, $from);
                        $output[] = $data_result;
                    }
                }

                return $output;
                break;
            case 'panturapost':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div.latest__wrap div.latest__item div.latest__img');


                    $output = [];
                    for ($i = 0; $i < 5; $i++) {
                        $link = $get_link[$i]->find('a', 0)->attr['href'];
                        $data_result = $this->get_article($link, $from);
                        $output[] = $data_result;
                    }
                }

                return $output;


                break;
        }
    }

    private function get_article($url, $from)
    {
        switch ($from) {
            case 'vimanews':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $title = $html->find('div.read__header h1.read__title', 0)->plaintext;
                    $img = $html->find('div.photo__img img', 0)->src;
                    $category = $html->find('div.breadcrumb ul.breadcrumb__wrap li a', 1)->plaintext;
                    $content = $html->find('article.read__content p');
                    $paging = $html->find('div.read__paging div.paging--article div.paging__wrap div.paging__item a');
                    $jml_page = 0;
                    if ($paging) {
                        $jml_page = count($paging);
                    }
                    $html_content = '';
                    if ($jml_page > 0) {
                        $html_content = $this->scrap_page($url, $jml_page, $from);
                    } else {
                        foreach ($content as $ct) {
                            $filter = $ct->find('strong.read__others');
                            $link = $ct->find('a');
                            $strong = $ct->find('strong');

                            foreach ($link as $l) {
                                $l->outertext = $l->plaintext;
                            }

                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter) {
                                $html_content .= $ct->plaintext . "\n";
                            }
                        }
                    }

                    $output = [
                        'category' => $category,
                        'title' => $title,
                        'image' => $img,
                        'jml_page' => $jml_page,
                        'source' => $url,
                        'from' => $from,
                        'content' => $html_content,
                    ];
                }
                return $output;
                break;
            case 'radartegal':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('div.entry-content ul.breadcrumb li a', 1)->plaintext;
                    $title = $html->find('div.post h1', 0)->plaintext;
                    $img = $html->find('div.post div.bottom-15 img', 0)->src;
                    $paging = $html->find('ul.pagination li a');
                    if ($paging) {
                        $jml_page = count($paging);
                    } else {
                        $jml_page = 0;
                    }


                    $html_content = '';
                    if ($jml_page > 0) {
                        $html_content = $this->scrap_page($url, $jml_page, $from);
                    } else {
                        $content = $html->find('section.entry-box div.entry-content div.post p span');

                        foreach ($content as $ct) {
                            $filter = $ct->find('strong a');
                            $strong = $ct->find('strong');


                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter) {
                                $html_content .= $ct->plaintext . "\n";
                            }
                        }
                    }

                    $output = [
                        'source' => $url,
                        'from' => $from,
                        'category' => $category,
                        'title' => $title,
                        'image' => $img,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                    ];
                    return $output;
                }
                break;
            case 'panturapost':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('ul.breadcrumb__wrap li.breadcrumb__item a', 1)->plaintext;
                    $title = $html->find('div.read__header h1.read__title', 0)->plaintext;
                    $image = $html->find('div.read__header div.photo div.photo__img img', 0)->src;
                    $page = $html->find('div.read__paging div.paging--article div.paging__wrap div.paging__item a.paging__link');

                    if ($page) {
                        $jml_page = count($page);
                    } else {
                        $jml_page = 0;
                    }


                    $html_content = '';
                    if ($jml_page > 0) {
                        $html_content = $this->scrap_page($url, $jml_page, $from);
                    } else {
                        $content = $html->find('article.read__content p');
                        foreach ($content as $ct) {
                            $filter = $ct->find('strong.read__others');
                            $link = $ct->find('a');
                            $strong = $ct->find('strong');

                            foreach ($link as $l) {
                                $l->outertext = $l->plaintext;
                            }

                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter) {
                                $html_content .= $ct->plaintext . "\n";
                            }
                        }
                    }


                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $image,
                        'jml_page' => $jml_page,
                        'content' => $html_content
                    ];
                }
                return $output;
                break;
        }
    }

    private function scrap_page($url, $jml_page, $from)
    {
        switch ($from) {
            case 'vimanews':
                $html_content = '';
                for ($i = 1; $i < $jml_page; $i++) {
                    $url_page = $url . '?page=' . $i;
                    $html = file_get_html($url_page);

                    if ($html) {
                        $content = $html->find('article.read__content p');
                        foreach ($content as $ct) {
                            $filter = $ct->find('strong.read__others');
                            $link = $ct->find('a');
                            $strong = $ct->find('strong');

                            foreach ($link as $l) {
                                $l->outertext = $l->plaintext;
                            }

                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter) {
                                $html_content .= $ct->plaintext . "\n";
                            }
                        }
                    }
                }
                return $html_content;
                break;
            case 'radartegal':
                $html_content = '';
                $jml_looping = ($jml_page - 1) * 15;
                for ($i = 0; $i < $jml_looping; $i += 15) {
                    $url_page = $url . '/' . $i;
                    $html = file_get_html($url_page);
                    if ($html) {
                        $content = $html->find('section.entry-box div.entry-content div.post p span');

                        foreach ($content as $ct) {
                            $filter = $ct->find('strong a');
                            $strong = $ct->find('strong');

                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter) {
                                $html_content .= $ct->plaintext . "\n";
                            }
                        }
                    }
                }
                return $html_content;
                break;
            case 'panturapost':
                $html_content = '';
                for ($i = 1; $i < $jml_page; $i++) {
                    $url_page = $url . '?page=' . $i;
                    $html = file_get_html($url_page);
                    if ($html) {
                        $content = $html->find('article.read__content p');
                        foreach ($content as $ct) {
                            $filter = $ct->find('strong.read__others');
                            $link = $ct->find('a');
                            $strong = $ct->find('strong');

                            foreach ($link as $l) {
                                $l->outertext = $l->plaintext;
                            }

                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter) {
                                $html_content .= $ct->plaintext . "\n";
                            }
                        }
                    }
                }
                return $html_content;
                break;
        }
    }
}
