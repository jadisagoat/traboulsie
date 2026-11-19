<?php
$e = function($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
if(!isset($info) || !is_array($info)) $info = array();
$info += array("title" => "", "title_ar" => "", "sector" => "", "place" => "", "place_ar" => "", "description" => "", "description_ar" => "", "photos" => "");
$photos = $this->fct->project_photos($info);
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10">
<div class="span10-fluid">
<ul class="breadcrumb">
<li><a href="<?= base_url(); ?>back_office/projects">projects</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">
<?php if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error alert-danger"><?= $e($this->session->userdata("error_message")); ?></div>
<?php } ?>
<form action="<?= base_url(); ?>back_office/projects/submit" method="post" enctype="multipart/form-data" class="middle-forms">
<p class="alert alert-info"><?= bo_t('projects_form_help', 'The English title, place and text show on the English page, the Arabic ones on the Arabic page. Fill in at least one title. The project shows on the website once it has at least one photo.'); ?></p>
<?php if(isset($id)){ ?><input type="hidden" name="id" value="<?= (int)$id; ?>" /><?php } ?>
<fieldset>
<label class="field-title">Title (English)&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-xxlarge" name="title" maxlength="250" placeholder="Kitchen of Al Saha restaurant" value="<?= $e(set_value("title", $info["title"])); ?>" />
<?= form_error("title", "<br /><span class='text-error'>", "</span>"); ?>
</label>

<label class="field-title">Title (Arabic):</label>
<label>
<input type="text" class="input-xxlarge" name="title_ar" maxlength="250" dir="rtl" value="<?= $e(set_value("title_ar", $info["title_ar"])); ?>" />
</label>

<label class="field-title">Sector&nbsp;<em>*</em>:</label>
<label>
<select name="sector" class="form-select" style="max-width:420px">
<option value="">—</option>
<?php foreach($sectors as $key => $name){ ?>
<option value="<?= $e($key); ?>"<?= set_value("sector", $info["sector"]) === $key ? " selected" : ""; ?>><?= $e(bo_t($name)); ?></option>
<?php } ?>
</select>
<?= form_error("sector", "<br /><span class='text-error'>", "</span>"); ?>
</label>

<label class="field-title">Place (English):</label>
<label>
<input type="text" class="input-xxlarge" name="place" maxlength="150" placeholder="Tyre" value="<?= $e(set_value("place", $info["place"])); ?>" />
</label>

<label class="field-title">Place (Arabic):</label>
<label>
<input type="text" class="input-xxlarge" name="place_ar" maxlength="150" dir="rtl" placeholder="صور" value="<?= $e(set_value("place_ar", $info["place_ar"])); ?>" />
</label>

<label class="field-title">Text about the project (English):</label>
<label>
<textarea class="input-xxlarge" rows="3" name="description" maxlength="2000"><?= $e(set_value("description", $info["description"])); ?></textarea>
</label>

<label class="field-title">Text about the project (Arabic):</label>
<label>
<textarea class="input-xxlarge" rows="3" name="description_ar" maxlength="2000" dir="rtl"><?= $e(set_value("description_ar", $info["description_ar"])); ?></textarea>
</label>

<?php if(count($photos)){ ?>
<label class="field-title">Photos of the project:</label>
<p style="font-size:12px;color:#888"><?= bo_t('projects_photos_help', 'Choose the cover photo, and tick the photos to remove.'); ?></p>
<div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:18px">
<?php foreach($photos as $i => $p){ ?>
<div style="width:150px;border:1px solid #ddd;border-radius:8px;overflow:hidden;background:#fafafa">
<a href="<?= base_url(); ?>uploads/projects/<?= $e($p); ?>" target="_blank" rel="noopener"><img src="<?= base_url(); ?>uploads/projects/thumb/<?= $e($p); ?>" alt="" style="display:block;width:150px;height:110px;object-fit:cover" /></a>
<div style="padding:6px 8px;font-size:12px;line-height:1.7">
<label style="display:block;margin:0"><input type="radio" name="cover_photo" value="<?= $e($p); ?>"<?= $i == 0 ? " checked" : ""; ?> /> <?= bo_t('Cover photo'); ?></label>
<label style="display:block;margin:0;color:#c0392b"><input type="checkbox" name="remove_photo[]" value="<?= $e($p); ?>" /> <?= bo_t('Remove'); ?></label>
</div>
</div>
<?php } ?>
</div>
<?php } ?>

<label class="field-title"><?= count($photos) ? "Add more photos" : "Photos"; ?>:</label>
<label>
<input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple />
<br /><span style="font-size:12px;color:#888"><?= bo_t('projects_upload_help', 'You can choose several photos at once (jpg, png or webp, up to 15 MB each). Large photos are made smaller automatically.'); ?></span>
</label>
</fieldset>
<p class="align-right">
<button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button>
</p>
</form>
</div>
</div>
</div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
<?php $this->session->unset_userdata("error_message"); ?>
