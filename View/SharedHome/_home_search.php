<div id="header-search-container" class="header-search-container">
    <div class="container">
        <form id="form-search">
            <div class="search-row">
                <input id="input-search" class="input-search" name="search_item" type="text" placeholder="Ne Arıyorsunuz?">
            </div>
        </form>
        <ul class="nav-search hidden">
            <h4 class="search-title">Arama Sonuçları</h4>
            <div id="nav-search-wrapper"></div>
        </ul>
        <ul class="nav-search-popular">
            <h4 class="search-title">Popüler Aramalar</h4>
            <?php if (!empty($web_data['search_popular_items'])) : ?>
                <?php foreach ($web_data['search_popular_items'] as $search_popular_item) : ?>
                    <li class="search-item"><a class="search-link" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $search_popular_item['item_url']; ?>"><?php echo $search_popular_item['item_name']; ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>