<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/videos/delete/"+id;
return false;
}
$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/videos/sorted?"+ser);
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
<li class="pull-right"><a href="<?= base_url(); ?>back_office/videos/add" id="topLink" title="Add video">Add video</a></li>
<?php } ?>
</ul>
</div>
<div class="hundred pull-left">
<p class="alert alert-info"><?= bo_t('videos_help', 'These videos play in the <b>Videos</b> section of the home page (design option 4). Drag the rows to change their order.<br>
On Facebook, open the video, click <b>…</b> or <b>Share</b> &rarr; <b>Copy link</b>, and paste that link in a new video. YouTube links work too.'); ?></p>
<div id="result"></div>
<form action="<?= base_url(); ?>back_office/videos/delete_all" method="post" name="list_form">
<table class="table table-striped" id="table-1">
<thead>
<?php if($this->session->userdata("success_message")){ ?>
<tr><td colspan="5" style="text-align:center"><div class="alert alert-success"><?= $this->session->userdata("success_message"); ?></div></td></tr>
<?php } ?>
<tr><td>&nbsp;</td><th>Title</th><th>Video</th><th>Type</th><th style="text-align:right;padding-right:80px;">Action</th></tr>
</thead>
<tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="4"><div class="pull-right">
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
	$embed = $this->fct->video_embed($val["video_url"]); ?>
<tr id="<?= (int)$val["id_videos"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= (int)$val["id_videos"]; ?>" /></td>
<td><?= $e($val["title"]); ?></td>
<td><a href="<?= $e($val["video_url"]); ?>" target="_blank" rel="noopener" class="blue">open video</a><?php if(!$embed){ ?> <small class="text-error">(this link can't be played on the site)</small><?php } ?></td>
<td><?= $embed ? bo_t(ucfirst($embed["source"]).($embed["vertical"] ? " reel" : "")) : "-"; ?></td>
<td style="text-align:right">
<?php if($can_write){ ?><a href="<?= base_url(); ?>back_office/videos/edit/<?= (int)$val["id_videos"]; ?>" class="table-edit-link" title="Edit"><i class="bx bx-edit"></i> Edit</a> <span class="hidden"> | </span><?php } ?>
<?php if($can_delete){ ?><a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?= (int)$val["id_videos"]; ?>'); }" class="table-delete-link cur" title="Delete"><i class="bx bx-trash"></i> Delete</a><?php } ?>
</td>
</tr>
<?php } }else{ ?>
<tr class="odd"><td colspan="5" style="text-align:center;">No videos yet. <?php if($can_write){ ?><a href="<?= base_url(); ?>back_office/videos/add">Add the first one</a>.<?php } ?></td></tr>
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
