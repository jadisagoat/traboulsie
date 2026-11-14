<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>

<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/newsletter">List Emails</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">  
     

<form action="<?= base_url(); ?>back_office/newsletter/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){
echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('email'=>'');
}
?>
<? if($this->session->userdata('success_message')){ ?>
<div class="alert alert-success">
<?= $this->session->userdata('success_message'); ?>
</div>
<? } ?>
<? if($this->session->userdata('error_message')){ ?>
<div class="alert alert-error">
<?= $this->session->userdata('error_message'); ?>
</div>
<? } ?>
</p>
<fieldset>
<legend></legend>
<label class="field-title">E-mail <em>*</em>:</label> <label>
<div class="input-prepend">
<span class="add-on"><i class="bx bx-envelope"></i></span>
<input type="text" class="input-xxlarge" name="email" value="<?= set_value('email',$info["email"]); ?>" /></div>
<?= form_error('email','<span class="text-error">','</span>'); ?>
</label>
</fieldset>
<p class="pull-left"><button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button></p>
</form>

</div>
</div>    
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 