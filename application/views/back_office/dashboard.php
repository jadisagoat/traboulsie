<?php
$icons = array(
    "home page"   => "bx-home-alt",
    "about us"    => "bx-info-circle",
    "banner"      => "bx-images",
    "contact us"  => "bx-phone",
    "category"    => "bx-category",
    "our clients" => "bx-group",
    "products"    => "bx-package",
);
$colors = array("primary", "success", "info", "warning", "danger", "secondary");
$level = $this->session->userdata('level');
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-8">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Welcome <?php echo $this->session->userdata('user_name'); ?>!</h5>
                        <p class="mb-3">Manage the content of your website from the menu on the left.</p>
                        <a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bx bx-globe me-1"></i> View website</a>
                    </div>
                </div>
                <div class="col-sm-4 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <i class="bx bx-desktop text-primary" style="font-size:90px;opacity:.25;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h6 class="text-muted text-uppercase mb-3">Website content</h6>
<div class="row">
    <?php
    $i = 0;
    foreach ($content_type as $val) {
        if ($val["position"] == 0) continue;

        if ($level == 0) {
            $cond = array('id_user' => $this->session->userdata('user_id'), 'id_content' => $val["id_content"]);
            $read_priv = $this->fct->getonecell('priviledge', 'read_p', $cond);
        } else {
            $read_priv = 1;
        }
        if ($read_priv != 1) continue;

        $slug = str_replace(" ", "_", $val["name"]);
        $count = null;
        if ($this->db->table_exists($slug)) {
            $count = count($this->fct->getAll($slug, 'sort_order'));
        }
        $icon = isset($icons[$val["name"]]) ? $icons[$val["name"]] : "bx-file";
        $color = $colors[$i % count($colors)];
        $i++;
    ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <a href="<?php echo base_url(); ?>back_office/<?php echo $slug; ?>" class="card h-100 dash-card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-box bg-label-<?php echo $color; ?> me-3">
                        <i class="bx <?php echo $icon; ?>"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted" style="text-transform:capitalize;"><?php echo $val["name"]; ?></span>
                        <h4 class="mb-0">
                            <?php echo ($count === null) ? '<small class="text-muted fs-6">Edit page</small>' : ($count == 0 ? 'None' : $count); ?>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>

    <?php if ($level == 1 || $level == 2) { ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <a href="<?php echo base_url(); ?>back_office/settings" class="card h-100 dash-card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-box bg-label-dark me-3"><i class="bx bx-cog"></i></div>
                    <div>
                        <span class="d-block text-muted">Settings</span>
                        <h4 class="mb-0"><small class="text-muted fs-6">Website info</small></h4>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>
</div>

<style>
    .dash-card {
        color: inherit;
        transition: transform .15s, box-shadow .15s;
    }

    .dash-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(90, 141, 238, .25);
    }

    .avatar-box {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }
</style>
