<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/projects/delete/"+id;
return false;
}
$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/projects/sorted?"+ser);
}
});
});
</script>
<?php $e = function($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }; ?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>

<div class="span10">
<div class="span10-fluid">
<ul class="breadcrumb">
<li class="active"><?= $title; ?></li>
<?php if($can_write){ ?>
<li class="pull-right"><a href="<?= base_url(); ?>back_office/projects/add" id="topLink" title="Add project">Add project</a></li>
<?php } ?>
</ul>
</div>
<div class="hundred pull-left">
<p class="alert alert-info"><?= bo_t('projects_help', 'These are the completed projects shown in the <b>Our projects</b> section of the home page, with their photos, in English on the English page and in Arabic on the Arabic page. Drag the rows to change their order. The section appears on the website once one project has photos.'); ?></p>
<div id="result"></div>
<form action="<?= base_url(); ?>back_office/projects/delete_all" method="post" name="list_form">
<table class="table table-striped" id="table-1">
<thead>
<?php if($this->session->userdata("success_message")){ ?>
<tr><td colspan="6" style="text-align:center"><div class="alert alert-success"><?= $this->session->userdata("success_message"); ?></div></td></tr>
<?php } ?>
<tr><td>&nbsp;</td><th>Photo</th><th>Title</th><th>Sector</th><th>Photos</th><th style="text-align:right;padding-right:80px;">Action</th></tr>
</thead>
<tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="5"><div class="pull-right">
<?php if($can_delete){ ?>
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a>
<?php } ?>
</div></td>
</tr></tfoot>
<tbody>
<?php if(count($info)){ foreach($info as $val){
	$photos = $this->fct->project_photos($val); ?>
<tr id="<?= (int)$val["id_projects"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= (int)$val["id_projects"]; ?>" /></td>
<td><?php if(count($photos)){ ?><img src="<?= base_url(); ?>uploads/projects/thumb/<?= $e($photos[0]); ?>" alt="" style="width:80px;height:56px;object-fit:cover;border-radius:6px" /><?php }else{ ?><small class="text-error"><?= bo_t('No photos (not shown on the website)'); ?></small><?php } ?></td>
<td><?= $e($val["title"]); ?><?php if(!empty($val["title_ar"])){ ?><br /><span dir="rtl"><?= $e($val["title_ar"]); ?></span><?php } ?></td>
<td><?= isset($sectors[$val["sector"]]) ? $e(bo_t($sectors[$val["sector"]])) : "-"; ?></td>
<td><?= count($photos); ?></td>
<td style="text-align:right">
<?php if($can_write){ ?><a href="<?= base_url(); ?>back_office/projects/edit/<?= (int)$val["id_projects"]; ?>" class="table-edit-link" title="Edit"><i class="bx bx-edit"></i> Edit</a> <span class="hidden"> | </span><?php } ?>
<?php if($can_delete){ ?><a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?= (int)$val["id_projects"]; ?>'); }" class="table-delete-link cur" title="Delete"><i class="bx bx-trash"></i> Delete</a><?php } ?>
</td>
</tr>
<?php } }else{ ?>
<tr class="odd"><td colspan="6" style="text-align:center;">No projects yet. <?php if($can_write){ ?><a href="<?= base_url(); ?>back_office/projects/add">Add the first one</a>.<?php } ?></td></tr>
<?php } ?>
</tbody>
</table>
</form>
</div>
</div>
</div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
<?php $this->session->unset_userdata("error_message"); ?>
