<footer class="pd-30">
    <div class="container">
        <div class="row">
            <div class="col-md-3 company-info">
                <div class="logo-ft">
                    <a href="" title="">
                        <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('logo_ft')) ?>" alt="ghn">
                    </a>
                </div>
                <?= SiteSettings::item('description_web') ?>
            </div>
            <div class="col-md-2 col-6 ft-menu">
                <h3 class="title">Get in Touch</h3>
                <div class="contact-info">
                    <p><i class="fa fa-map-marker"></i>134 Phố Viên, Cổ Nhuế,Bắc Từ Liêm, Hà Nội</p>
                    <p><i class="fa fa-envelope"></i>kieudao0603@gmail.com</p>
                    <p><i class="fa fa-phone"></i>+84 376312334</p>
                    <div class="social">
                        <a href=""><i class="fab fa-twitter"></i></a>
                        <a href=""><i class="fab fa-facebook-f"></i></a>
                        <a href=""><i class="fab fa-linkedin-in"></i></a>
                        <a href=""><i class="fab fa-instagram"></i></a>
                        <a href=""><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <?= menuFooter1('goto_tab') ?>
            </div>
            <div class="col-md-2 col-6 ft-menu">
                <h3 class="title">Useful Links</h3>
                <ul>
                    <li><a href="#">Lorem ipsum</a></li>
                    <li><a href="#">Pellentesque</a></li>
                    <li><a href="#">Aenean vulputate</a></li>
                    <li><a href="#">Vestibulum sit amet</a></li>
                    <li><a href="#">Nam dignissim</a></li>
                </ul>
                <?= menuFooter2('goto_tab') ?>
            </div>
            <div class="col-md-2 col-6 ft-menu">
                <h3 class="title">Quick Links</h3>
                <ul>
                    <li><a href="#">Lorem ipsum</a></li>
                    <li><a href="#">Pellentesque</a></li>
                    <li><a href="#">Aenean vulputate</a></li>
                    <li><a href="#">Vestibulum sit amet</a></li>
                    <li><a href="#">Nam dignissim</a></li>
                </ul>
                <?= menuFooter3() ?>
            </div>
            <div class="col-md-3 col-12 ft-menu">
                <h3 class="title">Newsletter</h3>
                <div class="newsletter">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sed porta dui. Class aptent taciti sociosqu
                    </p>
                    <form>
                        <input class="form-control" type="email" placeholder="Your email here">
                        <button class="btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-6 copyright">
                <p>Copyright &copy; <a href="https://htmlcodex.com">HTML Codex</a>. All Rights Reserved</p>
            </div>

            <div class="col-md-6 template-by">
                <p>Template By <a href="https://htmlcodex.com">HTML Codex</a></p>
            </div>
        </div>
    </div>
</div>
