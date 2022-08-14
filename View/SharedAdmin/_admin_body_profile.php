                <div class="btn-header user-profile">
                    <img class="user-profile-image" src="<?php echo URL; ?>assets/images/users/<?php echo $web_data['authed_user']['id'].'/'.$web_data['authed_user']['profile_image']; ?>" alt="<?php echo ucwords($web_data['authed_user']['first_name']).' '.ucwords($web_data['authed_user']['last_name']); ?>">
                    <span class="user-name"><?php echo ucwords($web_data['authed_user']['first_name']).' '.ucwords($web_data['authed_user']['last_name']); ?></span>
                    <i class="fas fa-chevron-down dropdown-profile-icon"></i>
                    <ul class="dropdown-profile-menu">
                        <li><a class="dropdown-profile-link" href="<?php echo URL; ?>AdminController/Profile">Profil</a></li>
                        <li><a class="dropdown-profile-link" href="<?php echo URL; ?>AdminController/Settings">Ayarlar</a></li>
                        <li><a class="dropdown-profile-link" href="<?php echo URL; ?>AccountController/LogOut">Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<main>