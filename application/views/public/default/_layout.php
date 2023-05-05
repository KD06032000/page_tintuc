<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="<?= $this->lang_code ?>">

<head>
    <base href="<?= BASE_URL ?>">
    <?php $this->load->view($this->template_path . '_meta') ?>
    <link rel="stylesheet" href="<?= $this->templates_assets . 'css/app.css' ?>">
    <link rel="stylesheet" href="<?= $this->templates_assets . 'css/style.css' ?>">
    <script type="text/javascript" src="<?= $this->templates_assets . 'js/head.js' ?>"></script>
    <script type="text/javascript" src="<?= $this->templates_assets . 'js/main.js' ?>"></script>
    <script>
        var current_url = '<?= current_url() ?>',
            base_url = '<?= base_url(); ?>',
            media_url = '<?= MEDIA_URL . '/'; ?>',
            controller = '<?= $this->_controller ?>',
            album = [],
            <?php
            switch ($this->lang_code) {
                case 'en':
                    $lang_fb = 'en_US';
                    break;
                default:
                    $lang_fb = 'vi_VN';
            }
            ?>
            lang = "<?= $lang_fb ?>";
    </script>
    <?= !empty($this->settings['script_head']) ? $this->settings['script_head'] : '' ?>
</head>

<body>

<?php
$this->load->view($this->template_path . '_nav');
?>
<div id="page">
    <?php
    $this->load->view($this->template_path . '_header');
    echo !empty($main_content) ? $main_content : '';
    $this->load->view($this->template_path . '_footer');
    ?>
    <a href="#" id="back-to-top" class="back-top"><i class="fas fa-chevron-up"></i> </a>
</div>
<div id="fb-root"></div>
<script src="<?= $this->templates_assets . 'js/app.js' ?>"></script>
<script src="<?= $this->templates_assets . 'js/utils.js' ?>"></script>
<!-- <script src="<?= $this->templates_assets . 'js/custom.js' ?>"></script> -->

<?= !empty($this->settings['embeb_js']) ? $this->settings['embeb_js'] : '' ?>

</body>

</html>