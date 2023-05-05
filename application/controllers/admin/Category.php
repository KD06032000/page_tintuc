<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    
    

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->lang->load('category');
        $this->load->model('category_model');
        $this->_data = new Category_model();
        $this->_name_controller = $this->router->fetch_class();
        
    }

    public function get_list($data, $layout = 'index')
    {
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/' . $layout, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function post()
    {
        $data['heading_title'] = "Bài viết";
        $data['heading_description'] = "Danh mục bài viết";
        $this->session->category_type = $data['category_type'] = $this->router->fetch_method();
        $this->get_list($data);
    }



    /*
     * Ajax trả về datatable
     * */
    public function ajax_list($category_type)
    {
        $this->checkRequestPostAjax();
        $data = array();
        $post = $this->input->post();
        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;
        $offset = ($page - 1) * $length;

        if (!empty($post['status'])) {
            $params['is_status'] = intval($post['status']) - 1;
        }

        if (empty($post['parent_id'])) {
            $params['category_type'] = $category_type;
            $params['limit'] = $this->_data->getTotalAll();
            $params['order'] = ['category.created_time' => 'DESC'];
            $list = $this->_data->getData($params);
            $total = $this->_data->getTotal($params);
        } 

        $list = array_slice($list, $offset, $length);
        if (!empty($list)) foreach ($list as $item) {
            if (is_object($item)) {
                $oneItem['data'] = $item;
                $oneItem['title'] = $item->title;
            } else {
                $oneItem = $item;
            }
            $row = array();
            $row[] = $oneItem['data']->id;
            $row[] = showCenter($oneItem['data']->id);
            $row[] = $oneItem['title'];
            $row[] = showFeatured($oneItem['data']->is_featured);
            $row[] = showOrder($oneItem['data']->id, $oneItem['data']->order);
            $row[] = showStatus($oneItem['data']->is_status);
            $row[] = showCenter(formatDate($oneItem['data']->created_time, 'd/m/Y'));
            //thêm action
            $action = '<div class="text-center">';
            $action .= button_action($oneItem['data']->id, ['edit', 'delete']);
            $action .= '</div>';
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data,
        );
        //trả về json
        $this->returnJson($output);
    }

    public function ajax_load($type)
    {
        $this->checkRequestGetAjax();
        $search = $this->input->get("q");
        $id = $this->input->get('id') ? $this->input->get('id') : 0;
        $params = [
            'category_type' => !(empty($type)) ? $type : null,
            'is_status' => 1,
            'not_in' => ['id' => $id],
            'search' => $search,
            'limit' => 10,
            'order' => array('created_time' => 'desc')
        ];
        $list = $this->_data->getData($params);
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();
        $parentId = !empty($data_store['parent_id']) ? $data_store['parent_id'] : 0;
        if ($id_category = $this->_data->save($data_store)) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action: " . $id_category;
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data = $this->_data->getById($id);
        if (!empty($data[0]->parent_id)) $data['parent_id'] = $this->_data->getSelect2($data[0]->parent_id);
        die(json_encode($data));
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $data_store['parent_id'] = isset($data_store['parent_id']) ? $data_store['parent_id'] : 0;
        // check category_id
        $data = $this->_data->getById($this->input->post('id'));
        if (!empty($data)) {
            $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store, $this->_data->table);
            if ($response != false) {
                // log action
                $action = $this->router->fetch_class();
                $note = "Update $action: " . $data_store['id'];
                $this->addLogaction($action, $note);
                $message['type'] = 'success';
                $message['message'] = $this->lang->line('mess_update_success');
            } else {
                $message['type'] = 'error';
                $message['message'] = $this->lang->line('mess_update_unsuccess');
            }
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }

        die(json_encode($message));
    }

    public function ajax_update_field()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' ) {
            $id = $this->input->post('id');
            $field = $this->input->post('field');
            $value = $this->input->post('value');
            $response = $this->_data->update(['id' => $id], [$field => $value]);
            if ($response != false) {
                $message['type'] = 'success';
                $message['message'] = $this->lang->line('mess_update_success');
            } else {
                $message['type'] = 'error';
                $message['message'] = $this->lang->line('mess_update_unsuccess');
            }
            print json_encode($message);
        }
        exit;
    }

    public function ajax_delete($type, $id)
    {

        //If delete root category, find category level 1 and change to root category
        $data = $this->_data->getById($id, $this->_data->table, '*', $this->session->admin_lang);

        if (!empty($data)) {
                $response = $this->_data->delete(['id' => $id]);
                if ($response != false) {
                    $this->_data->delete(["id" => $id], $this->_data->table_trans);
                    // log action
                    $action = $this->router->fetch_class();
                    $note = "Update $action: $id";
                    $this->addLogaction($action, $note);
                    $this->_message['type'] = 'success';
                    $this->_message['message'] = $this->lang->line('mess_delete_success');
                } else {
                    $this->_message['type'] = 'error';
                    $this->_message['message'] = $this->lang->line('mess_delete_unsuccess');
                    $this->_message['error'] = $response;
                    log_message('error', $response);
                }
            }
        } else {
            $this->_message['type'] = 'error';
            $this->_message['message'] = $this->lang->line('mess_delete_duplicate');
        }

        $this->returnJson($this->_message);
    }

    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        $this->checkRequestPostAjax();
        $seo = true;

        if (in_array($this->session->category_type, [ 'redirect'])) {
            $seo = false;
        }

        $rules = [];
        if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
            $rulesLang = $this->default_rules_lang($lang_code, [], $seo);
            $rules = array_merge($rules, $rulesLang);
        }
        $rules[] = [
            'field' => 'order',
            'label' => 'thứ tự',
            'rules' => 'trim|strip_tags|xss_clean|is_natural'
        ];
        $rules[] = [
            'field' => 'style',
            'label' => 'Layout style',
            'rules' => 'trim|callback_validate_html|xss_clean'
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }


    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        if (!in_array($data['is_status'], [0, 1])) {
            $data['is_status'] = 0;
        }
        if (!isset($data['parent_id'])) $data['parent_id'] = 0;
        $data['updated_time'] = date('Y-m-d');
        return $data;
    }
}
