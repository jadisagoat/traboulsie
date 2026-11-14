<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active"><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">
<form action="<?= base_url(); ?>back_office/home_page/submit" method="post" class="middle-forms">
<p class="alert alert-info">All the texts of the home page, in the order they appear on the website. Leave a field empty to hide it on the website.<br />
Images are managed in Banner, About Us and Products.</p>
<? if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<? } ?>
<? foreach($sections as $legend => $fields){ ?>
<fieldset>
<legend><?= $legend; ?></legend>
<? foreach($fields as $name => $field){
$value=htmlspecialchars($info[$name],ENT_QUOTES,"UTF-8");
$hint=isset($field[2]) ? $field[2] : "";
if($field[1] == "title") $hint="Each line here is a separate line on the website (on large screens).<br />Put words between [ ] to show them in orange, e.g. &amp; [kitchen equipment],"; ?>
<label class="field-title"><?= $field[0]; ?>:</label>
<label>
<? if($field[1] == "choice"){ ?>
<select class="input-xxlarge" name="<?= $name; ?>">
<? foreach($field[3] as $option => $option_label){ ?>
<option value="<?= htmlspecialchars($option,ENT_QUOTES,"UTF-8"); ?>"<?= (string)$info[$name] === (string)$option ? ' selected="selected"' : ''; ?>><?= $option_label; ?></option>
<? } ?>
</select>
<? } elseif($field[1] == "text" || $field[1] == "ar"){ ?>
<input type="text" class="input-xxlarge" name="<?= $name; ?>" value="<?= $value; ?>"<?= $field[1] == "ar" ? ' dir="rtl"' : ''; ?> />
<? } else { ?>
<textarea class="input-xxlarge" rows="<?= $field[1] == "title" ? 3 : 4; ?>" name="<?= $name; ?>"><?= $value; ?></textarea>
<? } ?>
<? if($hint != ""){ ?><br /><span style="font-size:12px;color:#888"><?= $hint; ?></span><? } ?>
</label>
<? } ?>
<p class="align-right">
<button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button>
</p>
</fieldset>
<? } ?>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>
</div>
<? $this->session->unset_userdata("success_message"); ?>
