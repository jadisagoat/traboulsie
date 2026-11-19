<?php
// The template draws the sidebar once. Content pages also call this view
// (old layout); skip those extra calls so the menu is not drawn twice.
if (defined('BO_LEFT_BOX_RENDERED')) return;
define('BO_LEFT_BOX_RENDERED', 1);

$cond = array('position' => 2);
$menu_left = $this->fct->getAll_cond('content_type', 'sort_order', $cond);
$menu_left_seg1 = $this->uri->segment(2);
$menu_left_seg2 = $this->uri->segment(3);
$user_id = $this->session->userdata('user_id');

$cond = array('admin_id' => 2);
$title_site = $this->fct->getonerow('admin', $cond);

// Content types that are one page (no "List / New" sub menu)
$single_pages = array("home page", "about us", "category", "contact us");

// Menu icons (boxicons)
$icons = array(
    "home page"   => "bx-home-alt",
    "about us"    => "bx-info-circle",
    "banner"      => "bx-images",
    "contact us"  => "bx-phone",
    "category"    => "bx-category",
    "our clients" => "bx-group",
    "products"    => "bx-package",
    "videos"      => "bx-video",
    "projects"    => "bx-briefcase-alt-2",
);
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?php echo base_url(); ?>back_office/home/dashboard" class="app-brand-link">
            <span class="app-brand-logo">
                <img style="width:38px;" src="<?php echo base_url(); ?>uploads/website/<?php echo $title_site["image"]; ?>" alt="" />
            </span>
            <span class="app-brand-text menu-text fw-bold ms-2" style="font-size:1rem;text-transform:uppercase;"><?php echo $title_site["admin_name"]; ?></span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item <?php if ($menu_left_seg1 == "home") echo "active"; ?>">
            <a href="<?php echo base_url(); ?>back_office/home/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Website content</span></li>

        <?php
        foreach ($menu_left as $val) {
            if ($this->session->userdata('level') == 0) {
                $cond = array('id_user' => $user_id, 'id_content' => $val["id_content"]);
                $read_priv = $this->fct->getonecell('priviledge', 'read_p', $cond);
            } else {
                $read_priv = 1;
            }
            if ($read_priv != 1) continue;

            $name = $val["name"];
            $slug = str_replace(" ", "_", $name);
            $icon = isset($icons[$name]) ? $icons[$name] : "bx-file";
            $is_current = ($menu_left_seg1 == $slug);

            if (in_array($name, $single_pages)) { ?>
                <li class="menu-item <?php if ($is_current) echo "active"; ?>">
                    <a href="<?php echo base_url(); ?>back_office/<?php echo $slug; ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx <?php echo $icon; ?>"></i>
                        <div><?php echo $name; ?></div>
                    </a>
                </li>
            <?php } else { ?>
                <li class="menu-item <?php if ($is_current) echo "active open"; ?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx <?php echo $icon; ?>"></i>
                        <div><?php echo $name; ?></div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item <?php if ($is_current && $menu_left_seg2 != "add" && $menu_left_seg2 != "edit") echo "active"; ?>">
                            <a href="<?php echo base_url(); ?>back_office/<?php echo $slug; ?>" class="menu-link">
                                <div>List <?php echo $name; ?></div>
                            </a>
                        </li>
                        <li class="menu-item <?php if ($is_current && ($menu_left_seg2 == "add" || $menu_left_seg2 == "edit")) echo "active"; ?>">
                            <a href="<?php echo base_url(); ?>back_office/<?php echo $slug; ?>/add" class="menu-link">
                                <div>New <?php echo $name; ?></div>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php }
        } ?>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">System</span></li>

        <li class="menu-item <?php if ($menu_left_seg1 == "settings") echo "active"; ?>">
            <a href="<?php echo base_url(); ?>back_office/settings" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Settings</div>
            </a>
        </li>

        <?php
        $cond = array('rules' => 'RECYCLE BIN');
        $user_rule = $this->fct->getonecell('admin_rules', 'active', $cond);
        if (($user_rule == 1 && $this->session->userdata('level') != 0) || $this->session->userdata('level') == 1) { ?>
            <li class="menu-item <?php if ($menu_left_seg1 == "recycle") echo "active"; ?>">
                <a href="<?php echo base_url(); ?>back_office/recycle" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trash"></i>
                    <div>Recycle Bin</div>
                </a>
            </li>
        <?php } ?>

        <?php if ($this->session->userdata('level') == 1) { ?>
            <li class="menu-item <?php if ($menu_left_seg1 == "control" || $menu_left_seg1 == "install") echo "active open"; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-globe"></i>
                    <div>Super Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?php if ($menu_left_seg1 == "control" && $menu_left_seg2 != "admin_rules") echo "active"; ?>">
                        <a href="<?php echo base_url(); ?>back_office/control" class="menu-link">
                            <div>Manage content type</div>
                        </a>
                    </li>
                    <li class="menu-item <?php if ($menu_left_seg1 == "control" && $menu_left_seg2 == "admin_rules") echo "active"; ?>">
                        <a href="<?php echo base_url(); ?>back_office/control/admin_rules" class="menu-link">
                            <div>Admin Rules</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>

        <li class="menu-item">
            <a href="<?php echo base_url(); ?>back_office/home/logout" class="menu-link">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div>Log Out</div>
            </a>
        </li>
    </ul>
</aside>

<style>
    .menu-item.active.open>.menu-link.menu-toggle {
        color: #fff !important;
        background-color: #5a8dee;
    }

    .bg-menu-theme .menu-sub .menu-item.active>.menu-link:not(.menu-toggle) {
        color: #5a8dee !important;
        background-color: #fff !important;
        font-weight: 600;
    }

    /* Old layout leaves an empty narrow column where the menu used to be */
    .container-xxl .row-fluid>.span2 {
        display: none;
    }
</style>
