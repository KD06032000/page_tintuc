<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
    protected $product;
    protected $category;
    protected $post;
    protected $page;
    protected $field;
    protected $document;
    protected $property;
    protected $library;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'post_model', 'library_model', 'page_model']);
        $this->category = new Category_model;
        $this->post = new Post_model();
        $this->page = new Page_model();
        $this->library = new Library_model();
    }

    public function index()
    {
        if ($this->input->get('lang')) redirect();

        $data['philosophy'] = SiteSettings::item('about_home', [], []);

        $data['page_media'] = $this->page->getPageByLayout('gallery', '*');
        $data['cate_left'] = $this->getCateDetail(SiteSettings::item('home_category_left', [], 0));
        $data['cate_right'] = $this->getCateDetail(SiteSettings::item('home_category_right', [], 0));
        $data['blog_cate_left'] = $this->getBlogByCateId(SiteSettings::item('home_category_left', [], 0), 3);
        $data['blog_cate_right'] = $this->getBlogByCateId(SiteSettings::item('home_category_right', [], 0), 4);

        $data['cate_ecosystem'] = getDataCategory('ecosystem', 5);

        $data['main_content'] = $this->load->view($this->template_path . 'home/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    private function getBlogByCateId($id, $limit = 10)
    {
        $params = [
            'category_id' => $id,
            'is_status' => 1,
            'lang_code' => $this->lang_code,
            'limit' => $limit,
            'have_tag' => true,
            'where' => ['displayed_time <=' => time_now()],
            'order' => ['order' => 'DESC', 'created_time' => 'DESC']
        ];
        return $this->post->getData($params);
    }


    private function getCateDetail($id)
    {
        return $this->category->getById($id, '', '*', $this->lang_code);
    }

    public function load($files)
    {
        $files = explode('-', $files);
        if (count($files) > 0) {
            $lang_text = '';
            foreach ($files as $file) {
                $this->lang->load(trim($file));
                foreach ($this->lang->language as $key => $lang) {
                    $lang_text .= "language['" . $key . "'] = '" . $lang . "';";
                }
            }
            print $lang_text;
            exit;
        }
    }
}
