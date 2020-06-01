<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Kontak_android extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data kontak
    function index_get() {
        $this->db->order_by('nama', 'ASC');
        $kontak = $this->db->get('telepon')->result();
        $this->response(array("result" => $kontak, 200));
    }

    //Fungsi Search
    function index_post() {
        $keyword = $this->post('keyword');

        if (!empty($keyword)) {
            $this->db->like('nama', $keyword);
            $search = $this->db->get('telepon')->result();
            $this->response(array("result" => $search, 200));
        } else {
            $this->response(array("no result"), 200);
        }
    }

}

