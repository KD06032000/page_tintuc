<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post_model extends APS_Model
{

    public $table;
    public $table_trans;
    public $table_category;
    

    public function __construct()
    {
        parent::__construct();
        $this->table = "post";
        $this->table_trans = "post_translations";
        $this->table_category = "post_category";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table_trans.is_featured", "$this->table.is_status", "$this->table.order", "$this->table.created_time", "$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args)
    {
        if (!empty($args['category_info'])) {
            $this->db->join($this->table_category, "$this->table.id = $this->table_category.post_id");
            $this->db->join('category_translations', "$this->table_category.category_id = category_translations.id");
        }
    }

    public function getCategoryByPostId($postId, $lang_code = null, $return = 'result')
    {
        
        $this->db->select();
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->join("category", "$this->table_category.category_id = category.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".{$this->table}_id", $postId);
        if ($return == 'result') $data = $this->db->get()->result();
        else $data = $this->db->get()->row();
        return $data;
    }


    public function getCategorySelect2($postId, $lang_code = null)
    {
         
        $this->db->select("$this->table_category.category_id AS id, category_translations.title AS text");
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".{$this->table}_id", $postId);
        $data = $this->db->get()->result();
        
        return $data;
    }


    public function listIdByCategory($category_id)
    {
        $this->db->from($this->table_category);
        $this->db->where('category_id', $category_id);
        $result = $this->db->get()->result();
        $listPostId = [];
        if (!empty($result)) foreach ($result as $item) {
            $listPostId[] = $item->post_id;
        }
        return $listPostId;
    }


    public function getCateIdById($id)
    {
        $this->db->select('category_id');
        $this->db->from($this->table_category);
        $this->db->where("{$this->table}_id", $id);
        $data = $this->db->get()->result();
        $listId = [];
        if (!empty($data)) foreach ($data as $item) {
            $listId[] = $item->category_id;
        }
        return $listId;
    }
}
