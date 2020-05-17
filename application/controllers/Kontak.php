<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Kontak extends REST_Controller {

    var $upload_path, $server_ip, $upload_url;

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();

        $this->upload_path = "Upload/Pictures/";
        $this->server_ip = 'http://' . $_SERVER['SERVER_ADDR'] . '/';
        $this->upload_url = $this->server_ip . 'rest_ci/' . $this->upload_path;
    }

    //Menampilkan data kontak
    function index_get() {
        $id = $this->get('id');
        if ($id == '') {
            $this->db->order_by('nama', 'ASC');
            $kontak = $this->db->get('telepon')->result();
        } else {
            $this->db->where('id', $id);
            $kontak = $this->db->get('telepon')->result();
        }
        $this->response($kontak, 200);
    }

    //Mengirim atau menambah data kontak baru
    function index_post() {

        if (!empty($_FILES['avatar']['name'])) {
            $fileinfo = pathinfo($_FILES['avatar']['name']);
            $file_path = $this->upload_path . $fileinfo['basename'];

            move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path);
        }

        if (!empty($_POST['avatar'])) {
            $file_url = $this->upload_url;
        } else {
            $file_url = "image not selected";
        }

        $data = array(
            'id' => $this->post('id'),
            'nama' => $this->post('nama'),
            'nomor' => $this->post('nomor'),
            'alamat' => $this->post('alamat'),
            'avatar' => $file_url . $this->post('avatar'));
        $insert = $this->db->insert('telepon', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //Memperbarui data kontak yang telah ada
    function index_put() {
        $id = $this->put('id');

        if (!empty($_FILES['avatar']['name'])) {
            $fileinfo = pathinfo($_FILES['avatar']['name']);
            $file_path = $this->upload_path . $fileinfo['basename'];

            move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path);
        }

        if (empty($_POST['avatar'])) {
            $file_url = $this->upload_url;
        } else {
            $file_url = 'image null';
        }

        $data = array(
            'id' => $this->put('id'),
            'nama' => $this->put('nama'),
            'nomor' => $this->put('nomor'),
            'alamat' => $this->put('alamat'),
            'avatar' => $file_url . $this->put('avatar'));
        $this->db->where('id', $id);
        $update = $this->db->update('telepon', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //Menghapus salah satu data kontak
    function index_delete() {
        $id = $this->delete('id');
        $this->db->where('id', $id);
        $delete = $this->db->delete('telepon');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

}
