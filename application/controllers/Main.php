<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
            } else {
                $params = [
                    'status' => false,
                    'msg' => 'Parameter is not registered'
                ];
                echo json_encode($params);
                die;
            }

            $this->database->different_category($get_data, $from);
        } else {
            $params = [
                'status' => false,
                'msg' => 'Invalid parameter'
            ];
            echo json_encode($params);
        }
    }
}
