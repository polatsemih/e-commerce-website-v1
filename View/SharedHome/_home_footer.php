<footer class="footer">
    <div class="container row">
        <div class="col">
            <div class="quick-pages-container">
            <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL; ?>">Anasayfa</a></span>
                <?php foreach ($web_data['genders'] as $gender) : ?>
                    <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL . URL_ITEMS . '/' . $gender['gender_url']; ?>"><?php echo $gender['gender_name']; ?></a></span>
                <?php endforeach; ?>
                <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL . URL_FAVORITES; ?>">Favorilerim</a></span>
                <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL . URL_CART; ?>">Sepetim</a></span>
            </div>
        </div>
        <div class="col">
            <div class="quick-pages-container">
                <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL . URL_AGREEMENTS . '/' . URL_AGREEMENT_TERMS; ?>">Kullanım Şartları</a></span>
                <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL . URL_AGREEMENTS . '/' . URL_AGREEMENT_PRIVACY; ?>">Gizlilik Sözleşmesi</a></span>
                <span class="quick-page-span"><a class="quick-page-link" href="<?php echo URL . URL_AGREEMENTS . '/' . URL_AGREEMENT_RETURN_POLICY; ?>">İade Politikası</a></span>
            </div>
        </div>
        <div class="col">
            <div class="right-col">
                <div class="socials">
                    <h4 class="socials-title">Bizi Takip Edebilirsiniz</h4>
                    <a href="<?php echo BRAND_TWITTER; ?>"><i class="fab fa-instagram social-link"></i></a>
                    <a href="<?php echo BRAND_INSTAGRAM; ?>"><i class="fab fa-twitter social-link"></i></a>
                </div>
                <p class="text">&#169; Copyright 2022 <?php echo BRAND; ?> - Her Hakkı Saklıdır</p>
            </div>
        </div>
        <div id="back-to-top" class="back-to-top"><i class="fas fa-angle-up"></i></div>
    </div>
</footer>
<script src="<?php echo URL; ?>assets/js/home.js"></script>
<script src="<?php echo URL; ?>assets/js/jQuery.js"></script>