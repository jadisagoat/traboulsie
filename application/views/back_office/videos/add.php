<?php
$e = function($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
if(!isset($info) || !is_array($info)) $info = array();
$info += array("title" => "", "title_ar" => "", "video_url" => "", "description" => "", "description_ar" => "");
$embed = $this->fct->video_embed(set_value("video_url", $info["video_url"]));
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10">
<div class="span10-fluid">
<ul class="breadcrumb">
<li><a href="<?= base_url(); ?>back_office/videos">videos</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">
<form action="<?= base_url(); ?>back_office/videos/submit" method="post" class="middle-forms">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em></p>
<?php if(isset($id)){ ?><input type="hidden" name="id" value="<?= (int)$id; ?>" /><?php } ?>
<fieldset>
<label class="field-title">Title (English)&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-xxlarge" name="title" maxlength="250" value="<?= $e(set_value("title", $info["title"])); ?>" />
<br /><span style="font-size:12px;color:#888"><?= bo_t('videos_lang_help', 'The English title and text show on the English page, the Arabic ones on the Arabic page. Fill in at least one title.'); ?></span>
<?= form_error("title", "<br /><span class='text-error'>", "</span>"); ?>
</label>

<label class="field-title">Title (Arabic):</label>
<label>
<input type="text" class="input-xxlarge" name="title_ar" maxlength="250" dir="rtl" value="<?= $e(set_value("title_ar", $info["title_ar"])); ?>" />
<?= form_error("title_ar", "<br /><span class='text-error'>", "</span>"); ?>
</label>

<label class="field-title">Video link&nbsp;<em>*</em>:</label>
<label>
<input type="url" class="input-xxlarge" name="video_url" maxlength="500" placeholder="https://www.facebook.com/traboulsi.est/videos/..." value="<?= $e(set_value("video_url", $info["video_url"])); ?>" />
<br /><span style="font-size:12px;color:#888"><?= bo_t('videos_link_help', 'On Facebook: open the video &rarr; <b>…</b> or <b>Share</b> &rarr; <b>Copy link</b>. Reels and YouTube links work too.'); ?></span>
<?= form_error("video_url", "<br /><span class='text-error'>", "</span>"); ?>
</label>

<label class="field-title">Text under the video (English):</label>
<label>
<textarea class="input-xxlarge" rows="3" name="description" maxlength="1000"><?= $e(set_value("description", $info["description"])); ?></textarea>
</label>

<label class="field-title">Text under the video (Arabic):</label>
<label>
<textarea class="input-xxlarge" rows="3" name="description_ar" maxlength="1000" dir="rtl"><?= $e(set_value("description_ar", $info["description_ar"])); ?></textarea>
</label>

<?php if($embed){ ?>
<label class="field-title">Preview:</label>
<div style="max-width:<?= $embed["vertical"] ? 300 : 560; ?>px;aspect-ratio:<?= $embed["vertical"] ? "9/16" : "16/9"; ?>;background:#000;margin-bottom:20px">
<iframe src="<?= $e($embed["src"]); ?>" style="width:100%;height:100%;border:0" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
</div>
<?php } ?>
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
