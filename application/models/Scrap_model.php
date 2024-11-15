<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
require 'assets/simplehtmldom/simple_html_dom.php';
class Scrap_model extends CI_Model
{
    public function main_scrap($main_url, $from)
    {
        $now_date = date('Y-m-d');
        // $now_date = date('Y-m-d', strtotime('-1 day'));

        switch ($from) {
            case 'vimanews':
                $html = file_get_html($main_url);
                $output = [];
                if ($html) {
                    $get_link = $html->find('div.latest__wrap div.latest__item');
                    $output = [];
                    foreach ($get_link as $gl) {
                        $link = $gl->find('div.latest__right h2.latest__title a', 0)->href;
                        $date = $gl->find('div.latest__right date.latest__date', 0)->plaintext;
                        $clean_date = preg_replace('/,\s*\d{2}:\d{2}\s*WIB/', '', $date);
                        $formated_date = $this->format_date($clean_date);

                        if ($formated_date == $now_date) {

                            $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();
                            if ($get_data <= 0) {
                                $data_result = $this->get_article($link, $from);
                                $output[] = $data_result;
                            }
                        }
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
                    foreach ($get_link as $gl) {
                        $link = $gl->find('a', 0)->attr['href'];
                        $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();
                        if ($get_data <= 0) {
                            $data_result = $this->get_article($link, $from);
                            $output[] = $data_result;
                        }
                    }
                }

                return $output;
                break;
            case 'panturapost':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div.latest__wrap div.latest__item');
                    $output = [];
                    foreach ($get_link as $gl) {
                        $link = $gl->find('div.latest__img a', 0)->attr['href'];
                        $date = $gl->find('div.latest__right date.latest__date', 0)->plaintext;
                        $clean_date = preg_replace('/,\s*\d{2}:\d{2}\s*WIB/', '', $date);
                        $formated_date = $this->format_date($clean_date);

                        if ($formated_date == $now_date) {
                            $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();
                            if ($get_data <= 0) {
                                $data_result = $this->get_article($link, $from);
                                $output[] = $data_result;
                            }
                        }
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
                    foreach ($get_link as $gl) {
                        $link = $gl->find('a', 0)->attr['href'];
                        $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();
                        if ($get_data <= 0) {
                            $data_result = $this->get_article($link, $from);
                            $output[] = $data_result;
                        }
                    }
                }

                return $output;
                break;
            case 'mitratoday':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div#tie-block_3151 div.container-wrapper div.mag-box-container ul.posts-list-container li a.post-thumb');
                    $output = [];
                    foreach ($get_link as $gl) {
                        $link = $gl->href;
                        $real_link = $main_url . '/' . $link;
                        $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $real_link])->get()->num_rows();

                        if ($get_data <= 0) {
                            $data_result = $this->get_article($real_link, $from, $main_url);
                            $output[] = $data_result;
                        }
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
                    foreach ($get_link as $gl) {
                        $link = $gl->attr['href'];
                        $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();

                        if ($get_data <= 0) {
                            $data_result = $this->get_article($link, $from, $main_url);
                            $output[] = $data_result;
                        }
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
                        foreach ($get_link as $gl) {
                            $link = $gl->attr['href'];
                            $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();

                            if ($get_data <= 0) {
                                $data_result = $this->get_article($link, $from, $main_url);
                                $output[] = $data_result;
                            }
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
                    // $this_date = date("Y-m-d", strtotime('-1 day'));

                    foreach ($get_link as $gl) {
                        $link = $gl->find('a.post-thumbnail', 0)->href;
                        $get_date = $gl->find('div.box-content div.gmr-meta-topic span.meta-content span.posted-on time.entry-date', 0)->attr['datetime'];
                        $date_upload = date('Y-m-d', strtotime($get_date));


                        if ($date_upload == $now_date) {
                            $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();

                            if ($get_data <= 0) {
                                $data_result = $this->get_article($link, $from, $main_url);
                                $output[] = $data_result;
                            }
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

                        $link_target = $based . $link;
                        if ($formated_date == $now_date) {
                            $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link_target])->get()->num_rows();

                            if ($get_data <= 0) {
                                $data_result = $this->get_article($link_target, $from, $based);
                                $output[] = $data_result;
                            }
                        }
                    }
                }
                return $output;
                break;
            case 'dprd':
                $html = file_get_html($main_url);

                $output = [];
                if ($html) {
                    $get_link = $html->find('div.wpb_wrapper div.jeg_pagination_nextprev div.jeg_block_container article.jeg_post');

                    foreach ($get_link as $gl) {
                        $link = $gl->find('div.jeg_thumb a', 0)->href;

                        $get_data = $this->db->select('judul')->from('berita')->where(['source' => $from, 'url_source' => $link])->get()->num_rows();

                        if ($get_data <= 0) {
                            $data_result = $this->get_article($link, $from);
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

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($img);

                    $output = [
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'source' => $url,
                        'from' => $from,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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
                        $content = $html->find('section.entry-box div.entry-content div.post p');

                        foreach ($content as $ct) {
                            $filter = $ct->find('strong a');
                            $strong = $ct->find('strong');


                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            $html_content .= $ct->plaintext . "\n";
                        }
                    }

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($img);

                    $output = [
                        'source' => $url,
                        'from' => $from,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($image);


                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($img);

                    $output = [
                        'source' => $url,
                        'from' => $from,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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

                    foreach ($content as $ct) {
                        $html_content .= $ct->plaintext . "\n";
                    }


                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $save_path = $this->download_image($main_url . $image);


                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $save_path,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
                    ];
                }
                return $output;
                break;
            case 'seputarpantura':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('div.breadcrumbs span a span', 1)->plaintext;
                    $title = $html->find('h1.entry-title strong', 0)->plaintext;
                    $image = $html->find('figure.post-thumbnail img', 0)->src;
                    $jml_page = 0;


                    $html_content = '';
                    $content = $html->find('div.single-wrap div.entry-content p');

                    foreach ($content as $ct) {
                        $html_content .= $ct->plaintext . "\n";
                    }

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($image);


                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($image);


                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($image);



                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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


                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $url_image = $main_url . "/v2/" . $image;
                    $source_image = $this->download_image($url_image);

                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
                    ];
                }
                return $output;
                break;
            case 'dprd':
                $html = file_get_html($url);
                $output = [];
                if ($html) {
                    $category = $html->find('div#breadcrumbs span a', 1)->plaintext;
                    $title = $html->find('div.entry-header h1.jeg_post_title', 0)->plaintext;
                    $image = $html->find('div.jeg_featured a div.thumbnail-container img.wp-post-image', 0)->src;
                    $jml_page = 0;

                    $html_content = '';
                    $content = $html->find('div.content-inner p');

                    foreach ($content as $ct) {
                        $html_content .= $ct->plaintext . "\n";
                    }

                    $keywordsMetaTag = $html->find('meta[name=keywords]', 0);
                    if ($keywordsMetaTag) {
                        $meta_keyword = 'singularity, pantura, jawa tengah, ' . $keywordsMetaTag->content;
                    } else {
                        $meta_keyword = 'singularity, jawa tengah, pantura, Berita Terpercaya, Berita indonesia,';
                    }
                    $source_image = $this->download_image($image);

                    $output = [
                        'from' => $from,
                        'source' => $url,
                        'category' => $category,
                        'title' => $title,
                        'image' => $source_image,
                        'jml_page' => $jml_page,
                        'content' => $html_content,
                        'keyword' => $meta_keyword
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
                        $content = $html->find('section.entry-box div.entry-content div.post p');

                        foreach ($content as $ct) {
                            $filter = $ct->find('strong a');
                            $strong = $ct->find('strong');
                            $fsource = $ct->find('p.bottom-15');

                            foreach ($strong as $st) {
                                $st->outertext = $st->plaintext;
                            }

                            if (!$filter && !$fsource) {
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


    private function download_image($image = null)
    {
        if ($image) {
            $no = $this->generateRandomString(9);
            $save_path = 'assets/images/' . $no . '.webp';
            file_put_contents($save_path, file_get_contents($image));
            $output = base_url($save_path);
        } else {
            $output = 'defaultimage.jpg';
        }
        return $output;
    }

    private function generateRandomString($length)
    {
        // Define the characters to choose from
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Generate the random string
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
