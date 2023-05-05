<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Public_Controller
{
    protected $_data_contact;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contact_model'));
        $this->_data_contact = new Contact_model();
    }

    private function _validate($callback = '')
    {
        $this->checkRequestPostAjax();
        $rules = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|max_length[255]|valid_email|xss_clean'
            ),
            array(
                'field' => 'phone',
                'label' => lang('phone_number'),
                'rules' => 'required|trim|strip_tags|regex_match[/^[0-9]{10,11}$/]|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'fullname',
                'label' => lang('full_name'),
                'rules' => 'required|trim|max_length[255]|strip_tags|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'company',
                'label' => lang('company'),
                'rules' => 'trim|strip_tags|max_length[255]|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'content',
                'label' => lang('content_mess'),
                'rules' => 'trim|strip_tags|max_length[255]|xss_clean|callback_validate_html'
            ),
        );

        if (is_callable($callback)) {
            $rules = array_merge($rules, $callback());
        }

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    public function send_contact()
    {
        $this->_validate();

        $data = $this->input->post();
        $data['type'] = 'contact';

        $template_mail = 'contact';

        $listEmailBCC = array(
            $this->settings['email_admin']
        );

        $email_admin = $this->settings['email_admin'];

        $subject = lang('customer_contact');

        $response = sendMail($data['email'], $email_admin, $subject, $template_mail, $data, '', $listEmailBCC);

        if ($response && $this->_data_contact->insert($data)) {
            $message['type'] = "success";
            $message['message'] = lang('send_contact_success');
        } else {
            $message['type'] = "warning";
            $message['message'] = lang('send_contact_unsuccess');
        };

        die(json_encode($message));
    }
}
