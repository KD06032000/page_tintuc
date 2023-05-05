<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('contact_model');
        $this->_data = new Contact_model();
    }

    public function get_list($data, $layout = 'index')
    {
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'contact/' . $layout, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function contact()
    {
        $this->session->contact_type = $data['contact_type'] = $this->router->fetch_method();
        $data['heading_title'] = 'Liên hệ';
        $data['heading_description'] = "Danh sách liên hệ";
        $this->get_list($data, 'contact');
    }

    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        $params['page'] = $page;
        $params['limit'] = $length;
        $params['where'] = [
            'type' => $this->session->contact_type
        ];

        if (!empty($post['page_id'])) {
            $params['where'] = [
                'page_id' => $post['page_id']
            ];
        }

        $list = $this->_data->getData($params);
        $data = array();
        foreach ($list as $item) {
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->fullname;
            $row[] = showCenter($item->phone);
            $row[] = $item->email;

            if (in_array($this->session->contact_type, ['conference', 'party'])) {
                $row[] = showCenter($item->humans);
                $row[] = showCenter(formatDate($item->date_start, 'd/m/Y'));
                $row[] = showCenter(formatDate($item->time_start, 'H:m:i') . ' - ' . formatDate($item->time_end, 'H:m:i'));
            }

            $row[] = $item->content;

            $row[] = showCenter(formatDateTime($item->created_time));
            $action = button_action($item->id, ['delete']);
            $row[] = $action;
            $data[] = $row;
        }

        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data,
        );
        $this->returnJson($output);
    }

    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != false) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: $id";
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_delete_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_delete_unsuccess');
            $message['error'] = $response;
            log_message('error', $response);
        }
        die(json_encode($message));
    }
}
