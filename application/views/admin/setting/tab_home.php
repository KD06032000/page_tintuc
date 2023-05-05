<?php
$name = 'home';
?>

<div class="tab-pane row" id="tab_<?= $name ?>">

    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked custom">
            <li role="presentation" class="active">
                <a data-toggle="tab" href="#tab_home_1">Chung</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab_home_2">Triết lý & Sứ mệnh</a>
            </li>
        </ul>
    </div>

    <div class="col-md-10">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_home_1">
                <fieldset class="form-group album-contain">
                    <legend>Chung</legend>

                    <?php

                    $blogCategories = getDataCategory('post', null);

                    $list_field_normal = [
                        ['name' => 'number_banner', 'id' => 'number_banner', 'title' => 'Số lượng banner hiển thị', 'type' => 'input_text'],
                        ['name' => 'link_donate', 'id' => 'link_donate', 'title' => 'Link quyên góp', 'type' => 'input_text'],
                        ['name' => 'home_category_left', 'id' => 'home_category_left', 'title' => 'Danh mục tin tức (trái)', 'type' => 'input_select', 'options' => $blogCategories],
                        ['name' => 'home_category_right', 'id' => 'home_category_right', 'title' => 'Danh mục tin tức (phải)', 'type' => 'input_select', 'options' => $blogCategories]
                    ];
                    ?>

                    <div class="box-body">
                        <?php if (!empty($list_field_normal)) foreach ($list_field_normal as $key => $item):
                            $item['value'] = !empty(${$item['name']}) ? ${$item['name']} : '';
                            $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                            ?>
                        <?php endforeach ?>
                    </div>
                </fieldset>

            </div>

            <div class="tab-pane" id="tab_home_2">
                <fieldset class="form-group album-contain">
                    <legend>Triết lý & Sứ mệnh</legend>

                    <?php $this->load->view($this->template_path . 'setting/items/input_multi', ['id' => 'about_home', 'layout' => 'home']); ?>

                </fieldset>
            </div>
        </div>
    </div>

</div>