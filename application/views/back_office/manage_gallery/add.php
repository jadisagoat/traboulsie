<?
$content_name = $this->uri->segment(2);
$content_name1 = $this->uri->segment(4);
$cond1["name"]=str_replace("_"," ",$content_name1);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
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
<li class="active"><?= $title1; ?></li>
</ul> 
</div>
<div class="hundred pull-left">  
<form action="<?= base_url(); ?>back_office/control/submit_photos" method="post" class="middle-forms" enctype="multipart/form-data">
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
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
<?
for($i=0; $i <5; $i++){
?>
<div class="pull-left">
<b>Image</b>
<input type="file" class="input-large"  name="image<?=$i; ?>" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>Title</b> 
<input type="text" class="input-large"  name="title<?= $i; ?>"/>
</div>
<? } ?>
</fieldset>
<p class="pull-left">
<input type="hidden" name="content_type" value="<?= $content_type; ?>"  />
<input type="hidden" name="id_gallery" value="<?= $id_gallery; ?>"  />
<button type="submit" class="btn btn-primary btn-save"><i class="bx bx-save me-1"></i> Save Changes</button></p>
</form>
</div> 
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> 