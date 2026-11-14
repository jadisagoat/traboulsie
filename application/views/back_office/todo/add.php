<script>
$(function(){
$('input[name=variable]:radio').change(function(){
var val=$(this).val();
if(val == "exist"){
$('#db_names').css('display','block');	
} else { $('#db_names').css('display','none'); }
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/todo">TO DO</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left control-group">     
<form action="<?= base_url(); ?>back_office/todo/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p  class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){
echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('title'=>'','description'=>'','Deadline'=>'');
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
<legend></legend><label class="field-title">Title <em>*</em>:</label> <label>
<input  type="text" class="input-xxlarge" name="title" value="<?= set_value('title',$info["title"]); ?>" />
<?= form_error('title','<span class="text-error">','</span>'); ?>
</label>
<label class="field-title">Description:</label>
<label>
<textarea class="ckeditor hundred" id="description" name="description" rows="7" cols="25" style="width:535px">
<?= set_value('description',$info["description"]); ?></textarea>
</label><label class="field-title">Deadline <em>*</em>:</label> <label>
<div data-date-format="mm/dd/yyyy" data-date="12-02-2012" class="input-append date">
<input type="text" value="<?= set_value('Deadline',$this->fct->date_out_formate($info["Deadline"])); ?>" name="Deadline" size="16" class="span2">
<span class="add-on"><i class="bx bx-grid-alt"></i></span>
</div>
<?= form_error('Deadline','<span class="text-error">','</span>'); ?>
</label>
</fieldset>
<p class="pull-left"><button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button></p>
</form>
</div>
</div>     
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 