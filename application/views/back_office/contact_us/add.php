
<?php
$content_name="contact_us";
$id_content="6";
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
<li><a href="<?=base_url();?>back_office/contact_us">contact us</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/contact_us/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
    $info["facebook"]="";
$info["instagram"]="";
$info["locations"]="";
$info["title"] = "";
$info["description"] = "";
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

    <label class="field-title">Facebook&nbsp;<em>*</em>:
    </label>
    <label>
        <input type="text" class="input-xxlarge" name="facebook" value="<?= set_value("facebook",$info["facebook"]); ?>" />
        <?= form_error("facebook","<span class='text-error'>","</span>"); ?>
    </label>

<label class="field-title">Instagram:
</label>
<label>
<input type="text" class="input-xxlarge" name="instagram" value="<?= set_value("instagram",$info["instagram"]); ?>" />
<?= form_error("instagram","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title">Locations:
</label>
<label>
<textarea class="input-xxlarge" rows="3" name="locations"><?= set_value("locations",$info["locations"]); ?></textarea>
<br /><span style="font-size:12px;color:red">One location per line, e.g. Beirut - Khalde</span>
<?= form_error("locations","<span class='text-error'>","</span>"); ?>
</label>


<label class="field-title">Description
</label>
<label>

<textarea class="ckeditor" id="description" rows="15" cols="100" name="description" >
<?= set_value("description",$info["description"]); ?>
</textarea>

<?= form_error("description","<span class='text-error'>","</span>"); ?>
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