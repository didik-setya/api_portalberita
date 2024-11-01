<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Data_model extends CI_Model
{
    public function different_category($data, $from)
    {
        $kategori_data = [];
        $kategori_db = [];
        $data_kategori = $this->db->select('kategori')->from('kategori')->get()->result();
        foreach ($data_kategori as $dk) {
            $kategori_db[] = $dk->kategori;
        }

        foreach ($data as $d) {
            $kategori_data[] = $d['category'];
        }

        $array_diff = array_diff($kategori_data, $kategori_db);
        $result_array = array_unique($array_diff);


        if ($result_array) {
            $insert_kategori = [];
            foreach ($result_array as $ra) {
                $row = ['kategori' => $ra];
                $insert_kategori[] = $row;
            }
            $this->db->insert_batch('kategori', $insert_kategori);
        }
        $this->insert_data_berita($data, $from);
    }

    private function insert_data_berita($data, $from)
    {
        $data_insert = [];
        foreach ($data as $d) {
            $get_kategori = $this->db->get_where('kategori', ['kategori' => $d['category']])->row();

            if ($get_kategori) {
                $kategori = $get_kategori->id_kategori;
            } else {
                $kategori = 0;
            }
            $row = [
                'judul' => $d['title'],
                'id_kategori' => $kategori,
                'source' => $d['from'],
                'gambar' => $d['image'],
                'teks_berita' => nl2br($d['content']),
                'tgl_posting' => date('Y-m-d H:i:s'),
                'id_admin' => 1,
                'dilihat' => 0,
                'url_source' => $d['source']
            ];
            $data_insert[] = $row;
        }

        $this->db->insert_batch('berita', $data_insert);
        if ($this->db->affected_rows() > 0) {
            $jml_data = $this->db->affected_rows();
            $message = 'Success add data from ' . $from . ' . total ' . $jml_data . ' data';
            $params = ['status' => true, 'msg' => $message];
            $this->insert_scrap_history($message);
        } else {
            $message = 'Failed add data from ' . $from;
            $params = ['status' => true, 'msg' => $message];
            $this->insert_scrap_history($message);
        }
        echo json_encode($params);
    }

    public function insert_scrap_history($message)
    {
        $data = [
            'time' => date('Y-m-d H:i:s'),
            'message' => $message
        ];
        $this->db->insert('scrap_history', $data);
    }
}
