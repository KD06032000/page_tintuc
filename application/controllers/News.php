<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News extends Public_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_data_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['page_model', 'category_model', 'post_model']);
        $this->_data = new Post_model();
        $this->_data_page = new Page_model();
        $this->_data_category = new Category_model();
        
    }

    public function category($id)
    {
        $data['main'] = $main = $this->_data_category->getById($id, '', '*', $this->lang_code);
        if (empty($main)) show_404();

        $data['SEO'] = $this->blockSEO($main, getUrlCateNews($main));
        $data['page_news'] = $this->_data_page->getPageByLayout('news', '*');

        $data['tags'] = getDataTag(null);

        $this->breadcrumbs->push(lang('home'), base_url());
        $this->breadcrumbs->push($main->title, getUrlCateNews($main));

        $data['main_content'] = $this->load->view($this->template_path . 'page/page-news-categories', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }


    public function detail($id)
    {

        $data['main'] = $main = $this->_data->getById($id, '', '*', $this->lang_code);
        if (empty($main)) show_404();

        // UPDATE VIEWS
        $this->_data->update(array('id' => $id), ['views' => (int)$main->views + 1], $this->_data->table);

        if ($this->input->get('lang')) {
            $langData = $this->_data->getById($id, '', '*', $this->lang_code);
            redirect(getUrlNews(['slug' => $langData->slug, 'id' => $langData->id]));
        }

        $data['SEO'] = $this->blockSEO($main, getUrlNews($main));

        $data['cate'] = $category = $this->_data->getOneCateIdById($id);

        $params = array(
            'is_status' => 1,
            'lang_code' => $this->lang_code,
            'not_in' => $id,
            'limit' => 4,
            'order' => ['order' => 'DESC', 'displayed_time' => 'DESC', 'created_time' => 'DESC'],
            'where' => ['displayed_time <=' => time_now()],
        );
        $data['list_new'] = $this->_data->getData($params);

        $params['category_id'] = $category->id;
        $data['list_related'] = $this->_data->getData($params);

        $data['main_content'] = $this->load->view(
            "{$this->template_path}news/detail",
            $data,
            TRUE
        );
        $this->load->view($this->template_main, $data);
    }

    public function ajax_call($page = 1)
    {
        $limit = $default = 9;
        $post = $this->input->get();

        $layout = '_item_list';

        if (!empty($post['page'])) {
            $page = intval($post['page']);
            $limit = $page * $default;
        }

        $params = array(
            'is_status' => 1,
            'lang_code' => $this->lang_code,
            'limit' => $limit,
            'order' => ['order' => 'DESC', 'displayed_time' => 'DESC', 'created_time' => 'DESC'],
            'where' => ['displayed_time <=' => time_now()],
           'search' => $search,
           'page' => $page
        );

        if (!empty($post['category_id']) && $post['category_id'] !== 0) {
            $params['category_id'] = $post['category_id'];
        }

        if (!empty($post['tag_id'])) {
            $params['tag_id'] = $post['tag_id'];
        }

        $data['data'] = $this->_data->getData($params);
        $data['page_next'] = $page + 1;

        $total = $this->_data->getTotal($params);//Total data

        $data['isShowMore'] = $limit < $total;

        $this->returnJson([
            'error' => false,
            'data' => [
                'html' => $this->load->view($this->template_path . 'news/_block/' . $layout, $data, TRUE)
            ]
        ]);
    }
}
