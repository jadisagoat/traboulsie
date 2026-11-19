<?php
// The form of one line of "Pages & texts" (controllers/back_office/records.php), built from the fields of config/site_records.php.
$e = function($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
if(!isset($info) || !is_array($info)) $info = array();
$url = base_url()."back_office/records/";
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10">
<div class="span10-fluid">
<ul class="breadcrumb">
<li><a href="<?= $url; ?>index/<?= $e($type); ?>"><?= $e($def["title"]); ?></a> <span class="divider">/</span></li>
<li class="active"><?= $e($title); ?></li>
</ul>
</div>
<div class="hundred pull-left">
<?php if(!empty($errors)){ ?>
<div class="alert alert-error alert-danger"><?php foreach($errors as $error){ ?><?= $e($error); ?><br /><?php } ?></div>
<?php } ?>
<form action="<?= $url; ?>submit/<?= $e($type); ?>" method="post" enctype="multipart/form-data" class="middle-forms">
<p class="alert alert-info">Fields marked <em>*</em> are required. An Arabic field left empty shows the English text on the Arabic page.</p>
<?php if(isset($id)){ ?><input type="hidden" name="id" value="<?= (int)$id; ?>" /><?php } ?>
<fieldset>
<?php foreach($def["fields"] as $name => $field){
	$value = isset($info[$name]) ? (string)$info[$name] : "";
	$hint = isset($field[2]) ? $field[2] : "";
	$rtl = in_array($field[1], array("ar", "artext")) ? ' dir="rtl"' : ''; ?>
<label class="field-title"><?= $e($field[0]); ?><?= in_array($name, $def["required"]) ? '&nbsp;<em>*</em>' : ''; ?>:</label>
<label>
<?php if($field[1] == "choice"){ ?>
<select class="form-select" name="<?= $e($name); ?>" style="max-width:420px">
<?php foreach($field[3] as $option => $option_label){ ?>
<option value="<?= $e($option); ?>"<?= $value === (string)$option ? ' selected="selected"' : ''; ?>><?= $e($option_label); ?></option>
<?php } ?>
</select>
<?php }elseif($field[1] == "file"){ ?>
<?php if($value !== ""){ ?>
<?php if(preg_match('/\.(mp4|webm)$/i', $value)){ ?>
<video src="<?= base_url().$e($value); ?>" muted loop autoplay playsinline style="height:150px;border-radius:8px;background:#111;vertical-align:middle"></video>
<?php }else{ ?>
<img src="<?= base_url().$e($value); ?>" alt="" style="height:150px;border-radius:8px;border:1px solid #ddd;vertical-align:middle" />
<?php } ?>
<span style="margin:0 12px"><input type="checkbox" name="remove_<?= $e($name); ?>" value="1" /> Remove it</span><br /><br />
<?php } ?>
<input type="file" name="<?= $e($name); ?>" accept=".mp4,.webm,.jpg,.jpeg,.png,.webp" />
<?php }elseif(in_array($field[1], array("textarea", "artext"))){ ?>
<textarea class="input-xxlarge" rows="<?= strpos($name, "body") === 0 ? 22 : (strlen($value) > 300 ? 9 : 4); ?>" name="<?= $e($name); ?>"<?= $rtl; ?>><?= $e($value); ?></textarea>
<?php }else{ ?>
<input type="text" class="input-xxlarge" name="<?= $e($name); ?>" value="<?= $e($value); ?>"<?= $rtl; ?><?= $field[1] == "date" ? ' placeholder="'.date("Y-m-d").'"' : ''; ?> />
<?php } ?>
<?php if($hint != ""){ ?><br /><span style="font-size:12px;color:#888"><?= $e($hint); ?></span><?php } ?>
</label>
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
