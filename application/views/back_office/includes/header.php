<?php
$cond = array('admin_id' => 2);
$title_site = $this->fct->getonerow('admin', $cond);
$seg = $this->uri->segment(2);
$user_name = $this->session->userdata('user_name');
?>
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <? if ($seg != "home") { ?>
                <a href="javascript:history.go(-1)" class="nav-btn" title="Back"><i class="bx bx-arrow-back"></i></a>
            <? } ?>

            <a href="<?php echo base_url(); ?>back_office/home/dashboard" class="nav-site-title">
                <?php echo $title_site["admin_name"]; ?>
            </a>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Language of the control panel -->
                <li class="nav-item me-2">
                    <?php if (bo_is_ar()) { ?>
                        <a class="nav-link lang-switch" href="<?php echo base_url(); ?>back_office/lang/set/en" lang="en" dir="ltr"><i class="bx bx-globe"></i>English</a>
                    <?php } else { ?>
                        <a class="nav-link lang-switch" href="<?php echo base_url(); ?>back_office/lang/set/ar" lang="ar" dir="rtl"><i class="bx bx-globe"></i><span class="ar">العربية</span></a>
                    <?php } ?>
                </li>

                <!-- View website -->
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?php echo base_url(); ?>" target="_blank" title="View website">
                        <i class="bx bx-sm bx-globe"></i>
                    </a>
                </li>

                <!-- Light / dark -->
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class='bx bx-sm bx-desktop'></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                <span class="align-middle"><i class='bx bx-sun me-2'></i>Light</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                <span class="align-middle"><i class="bx bx-moon me-2"></i>Dark</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User menu -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block" style="text-transform:capitalize;"><?php echo $user_name; ?></span>
                                        <small class="text-muted"><b>Last Login:</b> <?php echo $this->session->userdata('login_date'); ?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url(); ?>back_office/settings">
                                <i class="bx bx-cog me-2"></i>
                                <span class="align-middle">Settings</span>
                            </a>
                        </li>
                        <?
                        $cond = array('rules' => 'RECYCLE BIN');
                        $user_rule = $this->fct->getonecell('admin_rules', 'active', $cond);
                        if (($user_rule == 1 && $this->session->userdata('level') != 0) || $this->session->userdata('level') == 1) {
                        ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo base_url(); ?>back_office/recycle">
                                    <i class="bx bx-trash me-2"></i>
                                    <span class="align-middle">Recycle Bin</span>
                                </a>
                            </li>
                        <? } ?>
                        <? if ($this->session->userdata('level') == 1) { ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo base_url(); ?>back_office/control">
                                    <i class="bx bx-customize me-2"></i>
                                    <span class="align-middle">Manage content type</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo base_url(); ?>back_office/control/admin_rules">
                                    <i class="bx bx-shield-quarter me-2"></i>
                                    <span class="align-middle">Admin Rules</span>
                                </a>
                            </li>
                        <? } ?>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url(); ?>back_office/home/logout">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .nav-btn {
        margin: 0 10px 0 0;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        padding: 0;
        background-color: transparent;
        color: inherit;
        border: 1px solid rgba(255, 255, 255, .45);
        border-radius: 5px;
        font-size: 20px;
        line-height: 1;
    }

    .nav-btn i {
        display: block;
        line-height: 1;
        margin: 0;
    }

    .nav-btn:hover {
        background-color: #5a8dee;
        border-color: #5a8dee;
        color: #fff;
    }

    .nav-site-title {
        font-weight: bold;
        font-size: 20px;
        color: inherit;
    }

    .avatar {
        text-transform: capitalize;
        background-color: #5a8dee;
        position: relative;
        width: 2.375rem;
        height: 2.375rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
    }
</style>
