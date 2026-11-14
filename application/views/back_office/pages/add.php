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
<!-- end of div#left-col -->
<div class="span10" >

<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/pages">Pages</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
    
<div class="hundred pull-left control-group">
<form action="<?= base_url(); ?>back_office/pages/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){
echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('title'=>'','description'=>'');
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
<fieldset >
<legend>Fieldset Title</legend>

<label class="field-title">Page Title <em>*</em>:</label> <label>
<input type="text" class="input-xxlarge" name="title" value="<?= set_value('title',$info["title"]); ?>" />
<?= form_error('title','<span class="text-error">','</span>'); ?>
</label>
<label class="field-title">Description:</label>
<label>
<textarea class="ckeditor hundred" cols="100" id="description" name="description" rows="15">
<?= set_value('description',$info["description"]); ?></textarea>
</label>  						

</fieldset>
<p class="align-right"><button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button></p>
<span class="clearFix">&nbsp;</span>
</form>
</div>


</div>
    
</div> 
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 