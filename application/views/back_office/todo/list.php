<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location='<?= base_url(); ?>back_office/todo/delete/'+id;
return false;
}

function completed(id){
var sel="#complete_"+id;
$(sel).load('<?= base_url(); ?>back_office/home/complete_todo/'+id);	
}


$(function(){
$('#table-1').tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$('#result').load('<?= base_url(); ?>back_office/todo/sorted?'+ser);
}
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>

<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active">TO DO</li>
<li class="pull-right">
<a href="<?= base_url(); ?>back_office/todo/add" id="topLink">Create New To DO</a>
</li>
</ul> 
</div>
<div class="hundred pull-left">   
<div id="result"></div>   
<form action="<?= base_url(); ?>back_office/todo/delete_all" method="post" name="list_form" >    		
<table class="table table-striped" id="table-1">
<thead>
<? if($this->session->userdata('success_message')){ ?>
<tr><td colspan="5" align="center" style="text-align:center">
<div class="alert alert-success">
<?= $this->session->userdata('success_message'); ?>
</div>
</td>
<tr>
<? } ?>
<tr>
<th>&nbsp;</th>
<th>Title</th> 
<th>Deadline</th>
<th>Completed</th>
<th>Action</th>
</tr>
</thead>
<tfoot>
<tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="5"><div class="pull-right">
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a></div></td>
</tr>
</tfoot>
<tbody>
<? 
if(count($info) > 0){
$i=0;
foreach($info as $val){
$i++; 
($i%2==0)? $odd='' : $odd='odd';
?>
<tr class="<?= $odd; ?>" id="<?=$val["id_do"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_do"] ; ?>" /></td>
<td class="" width=""><b><?= $val["title"] ; ?></b></td>
<td class="" width="" >
<?=$val["Deadline"]; ?></td>
<td class="" width="" id="complete_<?=$val["id_do"]; ?>" >
<a class="cur" onclick="completed(<?=$val["id_do"]; ?>);">
<? if($val["completed"]==1){
echo "<span class='label label-success'>Completed</span>";
} else {
echo "<span class='label label-important'>Pending</span>";
}?>
</a></td>
<td class="row-nav"><a href="<?= base_url();?>back_office/todo/edit/<?= $val["id_do"]; ?>" class="table-edit-link" >
<i class="bx bx-edit" ></i> Edit</a>
<span class="hidden"> | </span>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_do"];?>'); }" class="table-delete-link cur" >
<i class="bx bx-trash" ></i> Delete</a>

</td>
</tr>
<? }  } else { echo '<tr class="odd"><td colspan="5" style="text-align:center;">No records available . </td></tr>'; } ?>
</tbody>
</table>  	
</form>
</div>


</div>  
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 