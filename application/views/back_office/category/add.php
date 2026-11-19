
<?php
$content_name="category";
$id_content="4";
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
} else { 
$write_priv=1;
}
if($write_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }	
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/category">category</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/category/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
}
foreach(array("h1_en","h1_ar","text_en","text_ar") as $page_field){ if(!isset($info[$page_field])) $info[$page_field] = ""; }
$page_value = function($name) use ($info){ return htmlspecialchars((string)set_value($name,$info[$name]),ENT_QUOTES,"UTF-8",false); };
?>
<? if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<? } ?>
<? if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error">
<?= $this->session->userdata("error_message"); ?>
</div>
<? } ?>
</p>
<fieldset>
<legend></legend>

<label class="field-title">Title&nbsp;<em>*</em>:
</label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?= set_value("title",$info["title"]); ?>" />
<?= form_error("title","<span class='text-error'>","</span>"); ?>
<br /><span style="font-size:12px;color:#888">Its Arabic name is written in Pages &amp; texts &gt; Arabic translations</span>
</label>
<legend>The page of the category on the website</legend>
<label class="field-title">Title of the page (English):</label>
<label><input type="text" class="input-xxlarge" name="h1_en" value="<?= $page_value("h1_en"); ?>" />
<br /><span style="font-size:12px;color:#888">Also the title shown in Google: Commercial fridges, display fridges and cold rooms in Lebanon</span></label>
<label class="field-title">Title of the page (Arabic):</label>
<label><input type="text" class="input-xxlarge" name="h1_ar" dir="rtl" value="<?= $page_value("h1_ar"); ?>" /></label>
<label class="field-title">Text under the title (English):</label>
<label><textarea class="input-xxlarge" rows="4" name="text_en"><?= $page_value("text_en"); ?></textarea>
<br /><span style="font-size:12px;color:#888">Two or three sentences about what the category contains. Also the description shown in Google</span></label>
<label class="field-title">Text under the title (Arabic):</label>
<label><textarea class="input-xxlarge" rows="4" name="text_ar" dir="rtl"><?= $page_value("text_ar"); ?></textarea></label>
</ol>
</fieldset>
<p class="align-right">
<button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button>
</p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>     
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> 