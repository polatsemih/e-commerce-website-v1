<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Sepet | <?php echo BRAND; ?></title>
    <?php require 'View/SharedHome/_home_head.php'; ?>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <?php require 'View/SharedHome/_home_body.php'; ?>
        <?php require 'View/SharedHome/_home_search.php'; ?>
    </header>
    <main>
        <?php echo isset($data['notification']) ? $data['notification'] : '<div class="notification"></div>'; ?>
        <section id="cart-section">
            <div class="container">
                <?php if (!empty($data['cart_items'])) : ?>
                    <div class="row-space">
                        <div class="col-9">
                            <?php $item_count = 0;
                            $item_total_price = 0; ?>
                            <?php foreach ($data['cart_items'] as $cart_item) : ?>
                                <?php
                                $item = $cart_item['item_propeties'];
                                $item_count += 1;
                                $item_total_price += $item['item_discount_price'] * $cart_item['item_quantity'];
                                ?>
                                <div class="cart-card">
                                    <div class="row-center">
                                        <div class="cart-image-container">
                                            <a href="<?php echo URL; ?>/HomeController/HomeItemDetails/<?php echo $item['item_url']; ?>">
                                                <img class="cart-item-image" src="<?php echo isset($item['item_images']) ? URL . '/assets/images/items/' . $item['id'] . '/' . $item['item_images'] : ''; ?>" alt="<?php echo ucwords($item['item_name']); ?>">
                                            </a>
                                        </div>
                                        <a class="cart-item-name" href="<?php echo URL; ?>/HomeController/HomeItemDetails/<?php echo $item['item_url']; ?>"><?php echo ucwords($item['item_name']) . ' (Beden: ' . strtoupper($cart_item['item_size']) . ')'; ?></a>
                                    </div>
                                    <div class="row-center">
                                        <span class="cart-item-price cart-old-price"><?php echo ($item['item_price'] * $cart_item['item_quantity']); ?>₺</span>
                                        <span class="cart-item-price cart-new-price"><?php echo ($item['item_discount_price'] * $cart_item['item_quantity']); ?>₺</span>
                                        <div class="reduce-quantity">
                                            <form action="<?php echo URL; ?>/AccountController/RemoveFromCart" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <input type="hidden" name="item_size" value="<?php echo $cart_item['item_size']; ?>">
                                                <input type="hidden" name="item_quantity" value="1">
                                                <button class="btn-cart-quantity" type="submit" name="submit_removefromcart"><i class="fas fa-minus quantity-icon"></i></button>
                                            </form>
                                        </div>
                                        <span class="item-quantity"><?php echo $cart_item['item_quantity']; ?></span>
                                        <div class="increase-quantity">
                                            <?php if ($cart_item['item_quantity'] == 10) : ?>
                                                <button class="btn-cart-quantity btn-disable"><i class="fas fa-plus quantity-icon"></i></button>
                                            <?php else : ?>
                                                <form action="<?php echo URL; ?>/AccountController/AddToCart" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                    <input type="hidden" name="item_size" value="<?php echo $cart_item['item_size']; ?>">
                                                    <input type="hidden" name="item_quantity" value="1">
                                                    <button class="btn-cart-quantity" type="submit" name="submit_addtocart"><i class="fas fa-plus quantity-icon"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="row">
                                <div class="row-right">
                                    <form action="<?php echo URL; ?>/AccountController/EmptyCart" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                        <button class="btn-submit-cart" type="submit" name="submit_emptycart">Sepeti Boşalt</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="cart-card-vertical">
                                <div class="selected-item">
                                    <span class="selected-item-text">Seçilen Ürün Adedi <?php echo $item_count; ?></span>
                                </div>
                                <div class="cart-price-infos">
                                    <span class="price-text">Kargo Ücreti</span>
                                    <span class="price-text">29.99₺</span>
                                </div>
                                <div class="cart-price-infos">
                                    <span class="price-text">Toplam Ücret</span>
                                    <span class="price-text"><?php echo ($item_total_price + 29.99); ?>₺</span>
                                </div>
                            </div>
                            <a href="" class="btn-submit-cart">Alışverişi Tamamla</a>
                        </div>
                    </div>
                <?php elseif (!empty($data['notfound_cookie'])) : ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_cookie']; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/home.js"></script>
    <?php if (isset($data['authed_user'])) : ?>
        <script src="<?php echo URL; ?>assets/js/home_authed.js"></script>
    <?php else : ?>
        <script src="<?php echo URL; ?>assets/js/home_action.js"></script>
    <?php endif; ?>
</body>

</html>