<div id="content">
<? $this->load->view('back_office/includes/left_box'); ?>
<div class="full-col">
<div class="box">      
<h4 class="white"><?= $title; ?></h4>
<div class="box-container">
<form action="<?= base_url(); ?>back_office/events/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<h3><?= $title; ?>:</h3>
<p>Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){
echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('title'=>'','description'=>'','date'=>'','image'=>'');
}
?>
<? if($this->session->userdata('success_message')){ ?>
<span class="form-confirm-inline">
<?= $this->session->userdata('success_message'); ?>
</span>
<? } ?>
<? if($this->session->userdata('error_message')){ ?>
<span class="form-error-inline">
<?= $this->session->userdata('error_message'); ?>
</span>
<? } ?>
</p>
<fieldset>
<legend>Fieldset Title</legend>
<ol>
<li class="even"><label class="field-title">Event Title <em>*</em>:</label> <label>
<input class="txtbox-middle" name="title" value="<?= set_value('title',$info["title"]); ?>" />
<?= form_error('title','<span class="form-error-inline">','</span>'); ?>
</label><span class="clearFix">&nbsp;</span></li>
<li>
<label class="field-title">Description:</label>
<label>
<textarea id="wysiwyg" name="description" rows="7" cols="25"><?= set_value('description',$info["description"]); ?></textarea>
</label>
<span class="clearFix">&nbsp;</span></li>    			
<li class="even"><label class="field-title">Date <em>*</em>:</label> <label>
<input class="txtbox-middle" id="calendar" name="date" value="<?= set_value('date',$this->fct->date_out_formate($info["date"])); ?>" />
<?= form_error('date','<span class="form-error-inline">','</span>'); ?> 
</label><span class="clearFix">&nbsp;</span></li>
<li class=""><label class="field-title">Image <em>*</em>:</label> <label>
<input type="file" class="" id="calendar" name="image" />
<span >
<?
if($info["image"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/events/<?= $info["image"]; ?>" class="blue gallery" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/events/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_events"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
</label>
<span class="clearFix">&nbsp;</span></li>			
</ol>
</fieldset>
<p class="align-right"><button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button></p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>
</div>   
<span class="clearFix">&nbsp;</span>     
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 