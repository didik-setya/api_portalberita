<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
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
            case 'jatengdisway':
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
            case 'mitratoday':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('ul.posts-list-container li a.post-thumb');
                    $output = [];
                    for ($i = 0; $i < 5; $i++) {
                        $link = $get_link[$i]->href;
                        $real_link = $main_url . '/' . $link;
                        $data_result = $this->get_article($real_link, $from, $main_url);
                        $output[] = $data_result;
                    }
                }

                return $output;
                break;
            case 'seputarpantura':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('main#primary div#infinite-container article div.box-item a.post-thumbnail');
                    $output = [];
                    for ($i = 0; $i < 2; $i++) {
                        $link = $get_link[$i]->attr['href'];
                        $data_result = $this->get_article($link, $from, $main_url);
                        $output[] = $data_result;
                    }
                }

                return $output;
                break;
            case 'mantiqmedia':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div#infinite-container article.type-post div.box-item a.post-thumbnail');

                    $jml_article = count($get_link);
                    if ($jml_article > 0) {
                        $output = [];
                        for ($i = 0; $i < $jml_article; $i++) {
                            $link = $get_link[$i]->attr['href'];
                            $data_result = $this->get_article($link, $from, $main_url);
                            $output[] = $data_result;
                        }
                    } else {
                        $output = [];
                    }
                }
                return $output;

                break;
            case 'smpantura':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div#infinite-container article.type-post div.box-item');
                    $this_date = date("Y-m-d", strtotime('-1 day'));

                    foreach ($get_link as $gl) {
                        $link = $gl->find('a.post-thumbnail', 0)->href;
                        $get_date = $gl->find('div.box-content div.gmr-meta-topic span.meta-content span.posted-on time.entry-date', 0)->attr['datetime'];
                        $date_upload = date('Y-m-d', strtotime($get_date));

                        // var_dump($date_upload);

                        if ($date_upload == $this_date) {
                            $data_result = $this->get_article($link, $from, $main_url);
                            $output[] = $data_result;
                        }
                    }
                }
                return $output;
                break;
            case 'tegalkota':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('table.category tbody tr');

                    foreach ($get_link as $gl) {
                        $based = 'https://www.tegalkota.go.id';
                        $link = $gl->find('td.list-title a', 0)->href;
                        $get_date = $gl->find('td.list-date', 0)->plaintext;
                        $formated_date = $this->format_date($get_date);
                        $this_date = date("Y-m-d", strtotime('-1 day'));

                        $link_target = $based . $link;
                        if ($formated_date == $this_date) {
                            $data_result = $this->get_article($link_target, $from, $based);
                            $output[] = $data_result;
                        }
                    }
                }
                return $output;
                break;
        }
    }

    private function get_article($url, $from, $main_url = null)
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
            case 'jatengdisway':
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
            case 'mitratoday':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('nav#breadcrumb a', 1)->plaintext;
                    $title = $html->find('div.entry-header h1.post-title ', 0)->plaintext;
                    $image = $html->find('figure.single-featured-image img', 0)->src;
                    $jml_page = 0;


                    $html_content = '';
                    $content = $html->find('article#the-post div.entry-content p');
                    $no = time();
                    foreach ($content as $ct) {
                        $html_content .= $ct->plaintext . "\n";
                    }


                    $save_path = 'assets/images/' . $no . '.webp';
                    // Download the image
                    file_put_contents($save_path, file_get_contents($main_url . $image));

                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => base_url($save_path),
                        'jml_page' => $jml_page,
                        'content' => $html_content
                    ];
                }
                return $output;
                break;
            case 'seputarpantura':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('span.cat-links-content a', 1)->plaintext;
                    $title = $html->find('h1.entry-title strong', 0)->plaintext;
                    $image = $html->find('figure.post-thumbnail img', 0)->src;
                    $jml_page = 0;


                    $html_content = '';
                    $content = $html->find('div.single-wrap div.entry-content p');

                    foreach ($content as $ct) {
                        $html_content .= $ct->plaintext . "\n";
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
            case 'mantiqmedia':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('main.site-main article.content-single div.breadcrumbs span a span', 1)->plaintext;
                    $title = $html->find('header.entry-header h1.entry-title strong', 0)->plaintext;
                    $image = $html->find('figure.post-thumbnail img.attachment-post-thumbnail', 0)->src;
                    $get_page = $html->find('div.page-links a.post-page-numbers');
                    if ($get_page) {
                        $jml_page = count($get_page) + 2;
                    } else {
                        $jml_page = 0;
                    }

                    $html_content = '';
                    if ($jml_page > 0) {
                        $html_content = $this->scrap_page($url, $jml_page, $from);
                    } else {
                        $content = $html->find('div.single-wrap div.entry-content p');
                        foreach ($content as $ct) {
                            $link = $ct->find('a');
                            foreach ($link as $l) {
                                $l->outertext = $l->plaintext;
                            }
                            $html_content .= $ct->plaintext . "\n";
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
            case 'smpantura':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('div.breadcrumbs span a', 1)->plaintext;
                    $title = $html->find('header.entry-header h1.entry-title strong', 0)->plaintext;
                    $image = $html->find('figure.post-thumbnail img.attachment-post-thumbnail', 0)->src;

                    $jml_page = 0;

                    $html_content = '';

                    $content = $html->find('div#page div#content div.container div.row main.site-main article.content-single div.single-wrap div.entry-content p');
                    foreach ($content as $ct) {
                        $link = $ct->find('a');
                        foreach ($link as $l) {
                            $l->outertext = $l->plaintext;
                        }
                        $html_content .= $ct->plaintext . "\n";
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
            case 'tegalkota':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = 'Berita';
                    $title = $html->find('div#jsn-mainbody div.item-page div.page-header h2 a', 0)->plaintext;
                    $image = $html->find('div#jsn-mainbody div.item-page p img', 0)->src;

                    $jml_page = 0;

                    $html_content = '';

                    $content = $html->find('div#jsn-mainbody div.item-page p');
                    foreach ($content as $ct) {
                        $html_content .= $ct->plaintext . "\n";
                    }

                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $main_url . "/v2/" . $image,
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
            case 'jatengdisway':
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
            case 'mantiqmedia':
                $html_content = '';

                for ($i = 1; $i < $jml_page; $i++) {
                    $url_page = $url . '/' . $i;
                    $html = file_get_html($url_page);

                    if ($html) {

                        $content = $html->find('div.single-wrap div.entry-content p');
                        foreach ($content as $ct) {
                            $link = $ct->find('a');
                            foreach ($link as $l) {
                                $l->outertext = $l->plaintext;
                            }
                            $html_content .= $ct->plaintext . "\n";
                        }
                    }
                }

                return $html_content;
                break;
        }
    }


    private function format_date($str_date)
    {
        $months = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December'
        ];

        $eng_date = str_replace(array_keys($months), array_values($months), $str_date);
        $date = strtotime($eng_date);
        return date('Y-m-d', $date);
    }
}
