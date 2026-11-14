

<?php

$content_name="banner";

$id_content="5";

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

<li><a href="<?=base_url();?>back_office/banner">banner</a> <span class="divider">/</span></li>

<li class="active"><?= $title; ?></li>

</ul> 

</div>

<div class="hundred pull-left">    

<form action="<?= base_url(); ?>back_office/banner/submit" method="post" class="middle-forms" enctype="multipart/form-data">

<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>

<?

if(isset($id)){?>

<input type="hidden" name="id" value="<?= $id; ?>" /> 

<?

} else {

$info["title"] = "";

$info["image"] = "";

}

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

</label>

<label class="field-title">Image

</label>

<label>

<input type="file"  class="input-xxlarge"  name="image" />

<span >

<?

if($info["image"] != ""){ ?>	

<a href="<?= base_url(); ?>uploads/banner/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >

show image

</a>

&nbsp;&nbsp;&nbsp;

<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/banner/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_banner"] ?> ' } " >

<img src="<?= base_url(); ?>images/delete.png"  /></a>

<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>

</span>
<br /><span style="font-size:12px;color:red">width:1950px / height:960px</span>

<?= form_error("image","<span class='text-error'>","</span>"); ?>

</label>

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