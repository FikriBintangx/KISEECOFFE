<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url('home'); ?>">KiiseCoffee</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url('home'); ?>">KC</a>
        </div>

        <!-- Divider -->
        <hr class="sidebar-divider neu-brutalism-divider">

        <ul class="sidebar-menu">
            <?php
            // SAFETY: Default values to prevent crash
            $role_id = 999; 
            $menu = [];
            
            $email = $this->session->userdata('email');
            
            // Only query if logged in
            if ($email) {
                // use role_id based on database (with fallback)
                $user_data = $this->db->get_where('user_data', ['email' => $email])->row_array();
                if($user_data) {
                    $role_id = $user_data['role_id'];
                    
                    $queryMenu = "SELECT `user_menu`.`id`, `menu`
                    FROM `user_menu`
                    JOIN `user_access_menu` ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                    WHERE `user_access_menu`.`role_id` = $role_id
                    ORDER BY `user_access_menu`.`menu_id` ASC";
    
                    $menu = $this->db->query($queryMenu)->result_array();
                }
            }
            ?>

            <?php
            foreach ($menu as $m) :
                if ($role_id == 1 && ($m['menu'] == 'User' || $m['menu'] == 'Transaksi')) continue;

            ?>
                <li class="menu-header">
                    <?= $m['menu']; ?>
                </li>

                <?php
                $menuId = $m['id'];
                $querySubMenu = "SELECT * FROM
                `user_sub_menu` JOIN `user_menu` ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                WHERE `user_sub_menu`.`menu_id` = $menuId";

                $subMenu = $this->db->query($querySubMenu)->result_array();
                ?>

                <?php
                foreach ($subMenu as $sm) :
                ?>
                    <?php if ($title == $sm['title']) : ?>
                        <li class="active">
                        <?php else : ?>
                        <li>
                        <?php endif; ?>
                        <?php 
                            // SOFT PASTEL COLORS FOR ICONS
                            $colors = ['#fdba74', '#86efac', '#93c5fd', '#f9a8d4', '#fde047', '#fca5a5'];
                            $randomColor = $colors[array_rand($colors)];
                        ?>
                        <a class="nav-link" href="<?= base_url($sm['url']); ?>">
                            <i class="<?= $sm['icon']; ?>" style="color: <?= $randomColor; ?>; font-size: 1.1em;"></i>
                            <span><?= $sm['title']; ?></span>
                        </a>
                        </li>
                    <?php endforeach; ?>

                    <!-- CUSTOM MENU FOR ADMIN UNDER 'ADMIN' HEADER -->
                    <?php if ($role_id == 1 && $m['menu'] == 'Admin') : ?>
                         <li class="<?= ($title == 'Profile Admin') ? 'active' : ''; ?>">
                            <a class="nav-link" href="<?= base_url('admin/profile'); ?>">
                                <i class="fas fa-user-circle"></i>
                                <span>Profile Saya</span>
                            </a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == 'change_password') ? 'active' : ''; ?>">
                            <a class="nav-link" href="<?= base_url('admin/profile'); ?>">
                                <i class="fas fa-key"></i>
                                <span>Ubah Password</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <hr class="sidebar-divider neu-brutalism-divider">
                <?php endforeach; ?>
        </ul>
    </aside>
</div>
