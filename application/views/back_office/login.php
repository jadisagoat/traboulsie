<!DOCTYPE html>
<html lang="<?= bo_lang(); ?>">

<head>
    <?
    $cond = array('admin_id' => 2);
    $this->db->select('image,website_title,admin_name');
    $title_site = $this->fct->getonerow('admin', $cond);
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>favicon.ico">
    <title><?= $title_site["admin_name"]; ?> - Admin</title>
    <link href="<?= base_url(); ?>mpc/assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>mpc/assets/css/animate.css" rel="stylesheet">
    <link href="<?= base_url(); ?>mpc/assets/css/loading.css" rel="stylesheet">
    <link href="<?= base_url(); ?>mpc/assets/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>mpc/assets/css/colors/purple.css" id="theme" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>mpc/assets/css/login.css" />
    <?
    // Left panel photo: first banner image of this website
    $this->db->select('image');
    $this->db->where('image !=', '');
    $login_banner = $this->fct->getAll('banner', 'sort_order');
    $login_bg = (count($login_banner) > 0) ? base_url() . 'uploads/banner/' . $login_banner[0]['image'] : '';
    ?>
    <? if ($login_bg != '') { ?>
        <style>
            .new-login-register .lg-info-panel {
                background: url(<?= $login_bg; ?>) center center / cover no-repeat !important;
            }
        </style>
    <? } ?>
</head>

<body>
    <!-- language of the control panel -->
    <a href="<?= base_url(); ?>back_office/lang/set/<?= bo_is_ar() ? 'en' : 'ar'; ?>" class="login-lang" lang="<?= bo_is_ar() ? 'en' : 'ar'; ?>"><?= bo_is_ar() ? 'English' : 'العربية'; ?></a>
    <style>
        .login-lang{position:fixed;top:16px;right:20px;z-index:1000;padding:7px 16px;border-radius:6px;background:#fff;color:#5a8dee;font-weight:600;font-family:"IBM Plex Sans Arabic","Segoe UI",Tahoma,sans-serif;box-shadow:0 4px 14px rgba(0,0,0,.12);text-decoration:none}
        .login-lang:hover{background:#5a8dee;color:#fff}

        .new-login-box[dir=rtl] .form,.new-login-box[dir=rtl] label,.new-login-box[dir=rtl] .alert{text-align:right}
        .new-login-box[dir=rtl] label,.new-login-box[dir=rtl] button,.new-login-box[dir=rtl] .alert{font-family:"IBM Plex Sans Arabic","Segoe UI",Tahoma,sans-serif}
    </style>
    <?php if (bo_is_ar()) { ?><link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600&display=swap" rel="stylesheet"><?php } ?>
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" class="new-login-register">
        <div class="lg-info-panel">
            <div class="inner-panel">
                <div class="lg-content">
                    <br> <br>
                    <h2><?= $title_site["admin_name"]; ?></h2>
                    <p class="text-muted"><?= $title_site["website_title"]; ?></p>
                </div>
            </div>
        </div>
        <div class="new-login-box"<?= bo_is_ar() ? ' dir="rtl"' : ''; ?>>
            <div class="white-box">
                <img src="<?= base_url(); ?>uploads/website/<?= $title_site["image"]; ?>" class="logo" alt="" />

                <div class="right">
                    <svg viewBox="0 0 320 300">
                        <defs>
                            <linearGradient id="linearGradient" x1="13" y1="193.49992" x2="307" y2="193.49992" gradientUnits="userSpaceOnUse">
                                <stop style="stop-color:#707CD2;" offset="0"></stop>
                                <stop style="stop-color:#7051D2;" offset="1"></stop>
                            </linearGradient>
                        </defs>
                        <path d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143"></path>
                    </svg>

                    <div class="form">
                        <form action="<?= base_url(); ?>back_office/home/login_validate" method="post" name="login-form" style="margin-top:15px;" class="form-horizontal new-lg-form" id="loginform">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="username">Username</label>
                                    <input type="text" required="" name="username" id="username" value="<?= set_value('username'); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="password">Password</label>
                                    <input type="password" required="" name="pass" id="password">
                                </div>
                            </div>
                            <div class="form-group m-t-20 text-center m-b-0">
                                <div class="col-xs-12">
                                    <button type="submit" class="login__submit">Sign in</button>
                                </div>
                            </div>
                        </form>

                        <div style="width:98%;padding:10px 0 0 5px; text-align:center;">
                            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                            <?
                            if ($this->session->userdata('login_error')) {
                                echo '<div class="alert alert-danger">' . $this->session->userdata('login_error') . '</div>';
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= base_url() ?>mpc/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url(); ?>mpc/assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>mpc/assets/js/custom.min.js"></script>
    <script src="<?= base_url(); ?>mpc/assets/js/anime.min.js"></script>

    <script type="text/javascript">
        // Hide the loading spinner even if custom.min.js does not
        $(window).on('load', function() {
            $('.preloader').fadeOut();
        });

        var current = null;

        function moveLine(offset) {
            if (typeof anime === 'undefined') return;
            if (current) current.pause();
            current = anime({
                targets: 'path',
                strokeDashoffset: {
                    value: offset,
                    duration: 700,
                    easing: 'easeOutQuart'
                },
                strokeDasharray: {
                    value: '240 1386',
                    duration: 700,
                    easing: 'easeOutQuart'
                }
            });
        }
        document.querySelector('#username').addEventListener('focus', function() {
            moveLine(0);
        });
        document.querySelector('#password').addEventListener('focus', function() {
            moveLine(-336);
        });
    </script>
</body>

</html>
<? $this->session->unset_userdata('login_error'); ?>
