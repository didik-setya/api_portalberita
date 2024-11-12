<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Main extends CI_Controller
{
    public function index($from = null)
    {
        if ($from) {
            if ($from == 'vimanews') {
                $get_data = $this->scrap->main_scrap('https://www.vimanews.id/', $from);
            } else if ($from == 'radartegal') {
                $get_data = $this->scrap->main_scrap('https://radartegal.disway.id/', $from);
            } else if ($from == 'panturapost') {
                $get_data = $this->scrap->main_scrap('https://www.panturapost.com/', $from);
            } else if ($from == 'jatengdisway') {
                $get_data = $this->scrap->main_scrap('https://jateng.disway.id/', $from);
            } else if ($from == 'mitratoday') {
                $get_data = $this->scrap->main_scrap('https://www.mitratoday.com', $from);
            } else if ($from == 'seputarpantura') {
                $get_data = $this->scrap->main_scrap('https://seputarpantura.com/', $from);
            } else if ($from == 'mantiqmedia') {
                $now = date('Y-m-d');
                $create_date = date_create($now);
                $day = date_format($create_date, 'd');
                $month = date_format($create_date, 'm');
                $year = date_format($create_date, 'Y');

                $get_data = $this->scrap->main_scrap('https://mantiqmedia.id/indeks/?dy=' . $day . '&mt=' . $month . '&yr=' . $year . '', $from);
            } else if ($from == 'smpantura') {
                $get_data = $this->scrap->main_scrap('https://www.smpantura.news/', $from);
            } else if ($from == 'tegalkota') {
                $get_data = $this->scrap->main_scrap('https://www.tegalkota.go.id/v2/index.php?option=com_content&view=category&id=20&Itemid=252&lang=id', $from);
            } else if ($from == 'dprd') {
                $get_data = $this->scrap->main_scrap('https://dprd-tegalkab.go.id/', $from);
            } else {
                $params = [
                    'status' => false,
                    'msg' => 'Parameter is not registered'
                ];
                echo json_encode($params);
                die;
            }

            // var_dump($get_data);
            // die;

            if ($get_data) {
                $this->database->different_category($get_data, $from);
            } else {
                $message = 'No data result from ' . $from;
                $this->database->insert_scrap_history($message);
                $params = [
                    'status' => false,
                    'msg' => $message
                ];
                echo json_encode($params);
                die;
            }
        } else {
            $params = [
                'status' => false,
                'msg' => 'Invalid parameter'
            ];
            echo json_encode($params);
        }
    }

    public function delete_empty_content()
    {
        $this->database->delete_empty_content();
    }
}
