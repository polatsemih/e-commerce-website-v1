<?php require 'View/SharedAdmin/_admin_html.php'; ?>
<title><?php echo isset($data['item']['item_name']) ? ucwords($data['item']['item_name']) . ' | ' : ''; ?><?php echo BRAND; ?> - Yönetim Paneli</title>
<?php require 'View/SharedAdmin/_admin_head.php'; ?>
<?php $item_url = isset($data['item']['item_url']) ? $data['item']['item_url'] : ''; ?>
<script>
    $(document).ready(function() {
        $('#select_filter').change(function() {
            window.location.href = '<?php echo URL . '/AdminController/ItemComments/' . $item_url . '?'; ?>' + $('#filter_form').serialize();
        });
        $('#select_limit').change(function() {
            window.location.href = '<?php echo URL . '/AdminController/ItemComments/' . $item_url . '?'; ?>' + $('#limit_form').serialize();
        });
    });
</script>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
<a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Items">
    <span class="btn-header-text">Ürünlere Geri Dön</span>
    <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
</a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
<section>
    <div class="container">
        <?php if (empty($data['notfound_item'])) : ?>
            <div class="row-center table-filter-container">
                <div class="row-space row-left">
                    <h2 class="second-title">"<?php echo ucwords($data['item']['item_name']); ?>" Yorumlar (<span id="comment-count"><?php echo $data['total_comment']; ?></span>)</h2>
                    <form class="row filter-container" action="<?php echo URL . '/AdminController/ItemComments/' . $item_url; ?>" method="GET" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                        <?php if (!empty($data['params_without_search'])) :
                            echo $data['params_without_search'];
                        endif; ?>
                        <?php if (!empty($data['search'])) : ?>
                            <input class="table-search" type="search" name="search" placeholder="Başka Bir Yorum Ara">
                        <?php else : ?>
                            <input class="table-search" type="search" name="search" placeholder="Yorum Ara">
                        <?php endif; ?>
                        <button class="btn-table-search" type="submit"><i class="fas fa-search table-search-icon"></i></button>
                    </form>
                </div>
                <div class="row-center row-right">
                    <form id="filter_form" class="row filter-container" title="Yorumların Görünürlük Durumuları">
                        <?php if (!empty($data['params_without_filter'])) :
                            echo $data['params_without_filter'];
                        endif; ?>
                        <span class="filter-select-text">Filtrele</span>
                        <select id="select_filter" class="filter-select" name="filter">
                            <option value="butun-yorumlar" <?php echo (!empty($data['filter']) && ($data['filter'] == 'butun-yorumlar')) ? ' selected' : ''; ?>>Bütün Yorumlar</option>
                            <option value="gorunur-yorumlar" <?php echo (!empty($data['filter']) && ($data['filter'] == 'gorunur-yorumlar')) ? ' selected' : ''; ?>>Görünür Olan Yorumlar</option>
                            <option value="gorunur-olmayan-yorumlar" <?php echo (!empty($data['filter']) && ($data['filter'] == 'gorunur-olmayan-yorumlar')) ? ' selected' : ''; ?>>Görünür Olmayan Yorumlar</option>
                        </select>
                    </form>
                    <form id="limit_form" class="row filter-container" title="Tabloda gösterilecek yorum sayısı">
                        <?php if (!empty($data['params_without_limit'])) :
                            echo $data['params_without_limit'];
                        endif; ?>
                        <span class="filter-select-text">Grupla</span>
                        <select id="select_limit" class="filter-select" name="limit">
                            <option value="5" <?php echo (!empty($data['limit']) && $data['limit'] == 5) ? ' selected' : ''; ?>>5</option>
                            <option value="10" <?php echo (!empty($data['limit']) && $data['limit'] == 10) ? ' selected' : ''; ?>>10</option>
                            <option value="25" <?php echo (!empty($data['limit']) && $data['limit'] == 25) ? ' selected' : ''; ?>>25</option>
                            <option value="50" <?php echo (!empty($data['limit']) && $data['limit'] == 50) ? ' selected' : ''; ?>>50</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="tree">
                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Items">ürünler</a>
                <span class="seperater">&gt;</span>
                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/ItemDetails/<?php echo $item_url; ?>"><?php echo $item_url; ?></a>
                <span class="seperater">&gt;</span>
                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/ItemComments/<?php echo $item_url; ?><?php echo (!empty($data['params_search_link']) && ($data['params_search_link'] != '?')) ? rtrim($data['params_search_link'], '&') : ''; ?>">yorumlar</a>
                <?php if (!empty($data['search'])) : ?>
                    <span class="seperater">&gt;</span>
                    <a class="tree-guide" href="<?php echo URL; ?>/AdminController/ItemComments/<?php echo $item_url; ?><?php echo (!empty($data['params_search_link']) && ($data['params_search_link'] != '?') && !empty($data['search'])) ? $data['params_search_link'] . 'search=' . $data['search'] : '?search=' . $data['search']; ?>"><?php echo $data['search']; ?></a>
                <?php endif; ?>
            </div>
            <?php if (isset($data['comments']) && isset($data['comment_users'])) : ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="table-align-center">Görsel</th>
                            <th style="width: 15%;" class="table-align-center">
                                <div class="row-center">
                                    <span class="th-space">Kullanıcı Adı</span>
                                </div>
                            </th>
                            <th style="width: 30%;" class="table-align-center">
                                <div class="row-center">
                                    <span class="th-space">Yorum</span>
                                </div>
                            </th>
                            <th style="width: 10%;" class="table-align-center">
                                <div class="row-center">
                                    <span class="th-space">Herkese Görünür</span>
                                </div>
                            </th>
                            <th style="width: 15%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL . '/AdminController/ItemComments/' . $item_url; ?>" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="ekleme-tarihi-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'ekleme-tarihi-azalan')) ? ' selected' : ''; ?>" title="Eklenme tarihini göre büyükten küçüğe sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">Eklenme Tarihi</span>
                                    <form action="<?php echo URL . '/AdminController/ItemComments/' . $item_url; ?>" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="ekleme-tarihi-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'ekleme-tarihi-artan')) ? ' selected' : ''; ?>" title="Eklenme tarihini göre küçükten büyüğe sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 15%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL . '/AdminController/ItemComments/' . $item_url; ?>" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="guncelleme-tarihi-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'guncelleme-tarihi-azalan')) ? ' selected' : ''; ?>" title="Güncelleme tarihini göre büyükten küçüğe sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">Son Güncellenme Tarihi</span>
                                    <form action="<?php echo URL . '/AdminController/ItemComments/' . $item_url; ?>" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="guncelleme-tarihi-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'guncelleme-tarihi-artan')) ? ' selected' : ''; ?>" title="Güncelleme tarihini göre küçükten büyüğe sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 7%;" class="table-align-center">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['comments'] as $comment) : ?>
                            <?php $comment_id = isset($comment['id']) ? $comment['id'] : ''; ?>
                            <?php $is_comment_approved = isset($comment['is_comment_approved']) ? $comment['is_comment_approved'] : ''; ?>
                            <?php $item_url = isset($data['item']['item_url']) ? $data['item']['item_url'] : ''; ?>
                            <?php $user_id = isset($comment['user_id']) ? $comment['user_id'] : ''; ?>
                            <?php $first_name = isset($data['comment_users'][$user_id]['first_name']) ? ucwords($data['comment_users'][$user_id]['first_name']) : '-'; ?>
                            <?php $last_name = isset($data['comment_users'][$user_id]['last_name']) ? ucwords($data['comment_users'][$user_id]['last_name']) : '-'; ?>
                            <tr id="item_comment_<?php echo $comment_id; ?>" class="item-comment-row">
                                <?php if (isset($data['comment_users'][$user_id]['profile_image'])) : ?>
                                    <td class="table-no-space"><img class="user-comment-image" src="<?php echo URL . USER_IMAGES_PATH . $user_id . '/' . $data['comment_users'][$user_id]['profile_image']; ?>" alt="<?php echo $first_name . ' ' . $last_name; ?>"></td>
                                <?php else : ?>
                                    <td class="table-align-left"><span class="text-danger">Ürün görseli bulunamadı</span></td>
                                <?php endif; ?>
                                <td class="table-align-center"><a class="btn-theme" href="<?php echo URL; ?>/AdminController/UserDetails/<?php echo $user_id; ?>"><?php echo $first_name . ' ' . $last_name; ?></a></td>
                                <?php if (isset($comment['comment'])) : ?>
                                    <td class="table-align-left"><?php echo $comment['comment']; ?></td>
                                <?php endif; ?>
                                <td class="table-align-center">
                                    <form id="approve_form_<?php echo $comment_id; ?>" class="comment-btn-approve btn-2">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                                        <button class="btn-purify btn_approves" data-id="<?php echo $comment_id; ?>">
                                            <label for="approved_<?php echo $comment_id; ?>" class="label-checkbox">
                                                <span id="comment_checkmark_<?php echo $comment_id; ?>" class="comment-checkmark-text"><?php echo (isset($comment['is_comment_approved']) && ($comment['is_comment_approved'] == 1)) ? 'Açık' : 'Kapalı'; ?></span>
                                                <input type="checkbox" class="checkbox comment_checkbox_<?php echo $comment_id; ?>" id="approved_<?php echo $comment_id; ?>" name="approved" <?php echo (isset($comment['is_comment_approved']) && ($comment['is_comment_approved'] == 1)) ? 'checked' : '' ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </button>
                                    </form>
                                </td>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'date_added_0') || ($data['sort_deg'] == 'date_added_1'))) : ?>
                                    <td class="table-align-center<?php echo ($data['sort_deg'] == 'date_added_0') ? ' desc' : ' asc'; ?>"><?php echo isset($comment['comment_date_added']) ? date('d/m/Y H.i.s', strtotime($comment['comment_date_added'])) : '-' ?></td>
                                <?php else : ?>
                                    <td class="table-align-center"><?php echo isset($comment['comment_date_added']) ? date('d/m/Y H.i.s', strtotime($comment['comment_date_added'])) : '-' ?></td>
                                <?php endif; ?>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'date_update_0') || ($data['sort_deg'] == 'date_update_1'))) : ?>
                                    <td class="table-align-center<?php echo ($data['sort_deg'] == 'date_update_0') ? ' desc' : ' asc'; ?>"><?php echo isset($comment['comment_date_updated']) ? date('d/m/Y H.i.s', strtotime($comment['comment_date_updated'])) : '-' ?></td>
                                <?php else : ?>
                                    <td class="table-align-center"><?php echo isset($comment['comment_date_updated']) ? date('d/m/Y H.i.s', strtotime($comment['comment_date_updated'])) : '-' ?></td>
                                <?php endif; ?>
                                <td>
                                    <div class="btn-setting-container">
                                        <button class="comment-btn btn-3 btn_comment_delete" data-id="<?php echo $comment_id; ?>" title="<?php echo $item_name; ?> Ürününe Yapılan Yorumu Sil">Yorumu Sil</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (isset($data['total_page'])) : ?>
                    <div class="row">
                        <div class="row-right">
                            <div class="row-space">
                                <?php for ($i = 1; $i <= $data['total_page']; $i++) : ?>
                                    <?php if (!empty($data['params_page_link']) && ($data['params_page_link'] != '?')) : ?>
                                        <a class="pagination-link<?php echo (isset($data['page']) && ($data['page'] == $i)) ? ' selected' : ''; ?>" href="<?php echo URL . '/AdminController/ItemComments/' . $data['item']['item_url'] . $data['params_page_link'] . 'page=' . $i; ?>"><?php echo $i; ?></a>
                                    <?php else : ?>
                                        <a class="pagination-link<?php echo (isset($data['page']) && ($data['page'] == $i)) ? ' selected' : ''; ?>" href="<?php echo URL . '/AdminController/ItemComments/' . $data['item']['item_url'] . '?page=' . $i; ?>"><?php echo $i; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="notfound-container">
                    <span class="text-notfound">Ürüne Ait Yorum Bulunamadı</span>
                    <a class="btn-notfound row-center" href="<?php echo URL; ?>/AdminController/Items">
                        <span class="btn-header-text">Ürünlere Geri Dön</span>
                        <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                    </a>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="notfound-container">
                <span class="text-notfound">Ürün Bulunamadı</span>
                <a class="btn-notfound row-center" href="<?php echo URL; ?>/AdminController/Items">
                    <span class="btn-header-text">Ürünlere Geri Dön</span>
                    <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
<div class="delete-popup disable">
    <div class="confirm-box">
        <div class="confirm-title-container">
            <h3 class="confirm-title confirm-title-danger"></h3>
        </div>
        <div class="confirm-btns">
            <button class="btn-popup btn-cancel btn-2" title="Silme İşlemini İptal Et">İptal</button>
        </div>
    </div>
</div>
<?php if (empty($data['notfound_item'])) : ?>
    <script>
        let deletePopup = document.querySelector('.delete-popup');
        let btnCancel = document.querySelector('.btn-cancel');
        <?php if (isset($data['comments']) && isset($data['comment_users'])) : ?>
            $(document).ready(function() {
                var request;
                var timeout;
                var timeout2;
                var using = false;
                var notification = $('.notification');
                var btnAprroves = $('.btn_approves');
                btnAprroves.each(function() {
                    $(this).click(function(e) {
                        e.preventDefault();
                        if (using) {
                            request.abort();
                            clearTimeout(timeout);
                            clearTimeout(timeout2);
                        }
                        using = true;
                        notification.html('');
                        if (!notification.hasClass('hidden')) {
                            notification.addClass('hidden');
                        }
                        if (!notification.hasClass('removed')) {
                            notification.addClass('removed');
                        }
                        var approve_form = $('#approve_form_' + $(this).data('id'));
                        var spanText = $('#comment_checkmark_' + $(this).data('id'));
                        var commentCheckbox = $('.comment_checkbox_' + $(this).data('id'));
                        var inputs = approve_form.find('input, button');
                        var serializedData = approve_form.serialize();
                        inputs.prop('disabled', true);
                        request = $.ajax({
                            url: '<?php echo URL . '/AdminController/ItemCommentApproved'; ?>',
                            type: 'POST',
                            data: serializedData
                        });
                        request.done(function(response) {
                            if (spanText.text() == 'Açık') {
                                spanText.text('Kapalı');
                            } else {
                                spanText.text('Açık');
                            }
                            if (commentCheckbox.is(':checked')) {
                                commentCheckbox.prop('checked', false);
                            } else {
                                commentCheckbox.prop('checked', true);
                            }
                            notification.html(response);
                            if (notification.hasClass('hidden')) {
                                notification.removeClass('hidden');
                            }
                            if (notification.hasClass('removed')) {
                                notification.removeClass('removed');
                            }
                            timeout = setTimeout(() => {
                                notification.addClass('hidden');
                                timeout2 = setTimeout(() => {
                                    notification.addClass('removed');
                                }, 1500);
                            }, 5000);
                        });
                        request.always(function() {
                            inputs.prop('disabled', false);
                        });
                    });
                });
                var deletePopup = $('.delete-popup');
                var confirmBtns = $('.confirm-btns');
                var btnCancel = $('.btn-cancel');
                var deleteBtn = $('#btn_delete');
                var confirmTitle = $('.confirm-title');
                var deleteCommentBtns = $('.btn_comment_delete');
                deleteCommentBtns.each(function(deleteCommentBtn) {
                    $(this).click(function(e) {
                        e.preventDefault();
                        var createdForm = $('<form></form>');
                        createdForm.attr('id', 'delete-form');
                        createdForm.attr('class', 'delete-comment-form');
                        var input1 = $('<input></input>');
                        input1.attr('type', 'hidden');
                        input1.attr('name', 'comment_id');
                        input1.attr('value', $(this).data('id'));
                        createdForm.append(input1);
                        var input2 = $('<button></button>');
                        input2.attr('id', 'delete-comment-btn');
                        input2.attr('class', 'btn-popup btn-3 btn-delete');
                        input2.attr('title', 'Silme İşlemini Onayla');
                        input2.text('Sil');
                        createdForm.append(input2);
                        confirmBtns.append(createdForm);
                        var span = $('<span></span>');
                        span.attr('class', 'text-deleting disable');
                        span.text('Yorum Siliniyor...');
                        createdForm.append(span);
                        confirmTitle.text('Yorumu Silmek İstediğinizden Emin Misiniz?');
                        if (deletePopup.hasClass('disable')) {
                            deletePopup.removeClass('disable');
                        }
                        input2.click(function() {
                            input2.addClass('disable');
                            span.removeClass('disable');
                        });
                        $('#delete-comment-btn').click(function(e) {
                            e.preventDefault();
                            if (using) {
                                request.abort();
                                clearTimeout(timeout);
                                clearTimeout(timeout2);
                            }
                            using = true;
                            notification.html('');
                            if (!notification.hasClass('hidden')) {
                                notification.addClass('hidden');
                            }
                            if (!notification.hasClass('removed')) {
                                notification.addClass('removed');
                            }
                            var form = $('.delete-comment-form');
                            var inputs = form.find('input');
                            var serializedData = form.serialize();
                            inputs.prop('disabled', true);
                            request = $.ajax({
                                url: '<?php echo URL . '/AdminController/ItemCommentDelete'; ?>',
                                type: 'POST',
                                data: serializedData
                            });
                            request.done(function(response) {
                                var item_comment = $('#item_comment_' + inputs.attr('value'));
                                item_comment.remove();
                                var deleteForm = $('#delete-form');
                                deleteForm.remove();
                                var deletePopup = $('.delete-popup');
                                if (!deletePopup.hasClass('disable')) {
                                    deletePopup.addClass('disable');
                                }
                                var commentCount = $('#comment-count');
                                commentCount.text(parseInt(commentCount.text()) - 1);
                                <?php if (isset($data['page'])) : ?>
                                    var current_page = <?php echo $data['page'] - 1; ?>;
                                <?php else : ?>
                                    var current_page = 1;
                                <?php endif; ?>
                                var rows = $('.item-comment-row');
                                if (rows.length == 0) {
                                    <?php if (!empty($data['params_page_link']) && ($data['params_page_link'] != '?')) : ?>
                                        window.location.href = '<?php echo URL . '/AdminController/ItemComments/' . $item_url . $data['params_page_link'] . 'page='; ?>' + current_page;
                                    <?php else : ?>
                                        window.location.href = '<?php echo URL . '/AdminController/ItemComments/' . $item_url . '?page='; ?>' + current_page;
                                    <?php endif; ?>
                                }
                                notification.html(response);
                                if (notification.hasClass('hidden')) {
                                    notification.removeClass('hidden');
                                }
                                if (notification.hasClass('removed')) {
                                    notification.removeClass('removed');
                                }
                                timeout = setTimeout(() => {
                                    notification.addClass('hidden');
                                    timeout2 = setTimeout(() => {
                                        notification.addClass('removed');
                                    }, 1500);
                                }, 5000);
                            });
                            request.always(function() {
                                inputs.prop('disabled', false);
                            });
                        });
                    });
                });
            });
        <?php endif; ?>
        btnCancel.addEventListener('click', () => {
            let deleteForm = document.getElementById('delete-form');
            deleteForm.remove();
            if (!deletePopup.classList.contains('disable')) {
                deletePopup.classList.add('disable');
            }
        });
        deletePopup.addEventListener('mouseup', (e) => {
            if (e.target.classList == 'delete-popup') {
                let deleteForm = document.getElementById('delete-form');
                deleteForm.remove();
                if (!deletePopup.classList.contains('disable')) {
                    deletePopup.classList.add('disable');
                }
            }
        });
    </script>
<?php endif; ?>
</body>

</html>