<?php
// The list of one kind of content of "Pages & texts" (controllers/back_office/records.php): the two fields named in
// config/site_records.php, a search box, drag to sort, edit and delete.
$e = function($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
$url = base_url()."back_office/records/";
$cols = $def["list"];
$short = function($s){ $s = trim(preg_replace('/\s+/u', ' ', (string)$s)); return mb_strlen($s, 'UTF-8') > 90 ? mb_substr($s, 0, 90, 'UTF-8').'…' : $s; };
?>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
$("#result").load("<?= $url; ?>sorted/<?= $e($type); ?>?"+$.tableDnD.serialize());
}
});
// the search box hides the rows that do not contain what is typed
$("#recordSearch").on("input", function(){
var q=$(this).val().toLowerCase();
$("#table-1 tbody tr").each(function(){ $(this).toggle($(this).text().toLowerCase().indexOf(q) !== -1); });
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10">
<div class="span10-fluid">
<ul class="breadcrumb">
<li class="active"><?= $e($title); ?></li>
<li class="pull-right"><a href="<?= $url; ?>add/<?= $e($type); ?>" id="topLink">Add <?= $e($def["one"]); ?></a></li>
</ul>
</div>
<div class="hundred pull-left">
<p class="alert alert-info"><?= $def["help"]; ?></p>
<?php if(count($info) > 12){ ?><p><input type="search" id="recordSearch" class="input-xxlarge" placeholder="Search in this list…" /></p><?php } ?>
<div id="result"></div>
<form action="<?= $url; ?>delete_all/<?= $e($type); ?>" method="post" name="list_form">
<table class="table table-striped" id="table-1">
<thead>
<?php if($this->session->userdata("success_message")){ ?>
<tr><td colspan="4" style="text-align:center"><div class="alert alert-success"><?= $e($this->session->userdata("success_message")); ?></div></td></tr>
<?php } ?>
<tr><td>&nbsp;</td><?php foreach($cols as $col){ ?><th><?= $e($def["fields"][$col][0]); ?></th><?php } ?><th style="text-align:right;padding-right:80px;">Action</th></tr>
</thead>
<tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="3"><div class="pull-right">
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a>
</div></td>
</tr></tfoot>
<tbody>
<?php if(count($info)){ foreach($info as $val){ ?>
<tr id="<?= (int)$val["id"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= (int)$val["id"]; ?>" /></td>
<?php foreach($cols as $col){
	$field = $def["fields"][$col];
	$value = $field[1] == "choice" && isset($field[3][$val[$col]]) ? $field[3][$val[$col]] : $val[$col]; ?>
<td<?= in_array($field[1], array("ar", "artext")) ? ' dir="rtl"' : ''; ?>><?= $e($short($value)); ?></td>
<?php } ?>
<td style="text-align:right;white-space:nowrap">
<a href="<?= $url; ?>edit/<?= $e($type); ?>/<?= (int)$val["id"]; ?>" class="table-edit-link" title="Edit"><i class="bx bx-edit"></i> Edit</a> <span class="hidden"> | </span>
<a href="<?= $url; ?>delete/<?= $e($type); ?>/<?= (int)$val["id"]; ?>" onclick="return confirm('Are you sure you want delete this item ?');" class="table-delete-link cur" title="Delete"><i class="bx bx-trash"></i> Delete</a>
</td>
</tr>
<?php } }else{ ?>
<tr class="odd"><td colspan="4" style="text-align:center;">Nothing here yet. <a href="<?= $url; ?>add/<?= $e($type); ?>">Add the first one</a>.</td></tr>
<?php } ?>
</tbody>
</table>
</form>
</div>
</div>
</div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
