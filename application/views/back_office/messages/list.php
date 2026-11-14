<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location='<?= base_url(); ?>back_office/messages/delete/'+id;
return false;
}


$(function(){
$('#table-1').tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$('#result').load('<?= base_url(); ?>back_office/messages/sorted?'+ser);
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
<li class="active"><?= $title; ?></li>
<li class="pull-right">
<a href="<?=base_url(); ?>back_office/messages"  class="btn btn-inverse btn_mrg">All Messages</a>
<a href="<?=base_url(); ?>back_office/messages/unread" class="btn btn-success btn_mrg">Unread Messages</a>
<a href="<?=base_url(); ?>back_office/messages/opened" class="btn btn-danger btn_mrg" >Archived Messages</a>
</li>
</ul> 
</div>
<div class="hundred pull-left">   
<div id="result"></div>    
<form action="<?= base_url(); ?>back_office/messages/delete_all" method="post" name="list_form" >    		
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
<th>Subject</th>
<th>E-mail</th>
<th>Date</th>
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
<tr  id="<?=$val["id"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id"] ; ?>" /></td>
<td class="" width="">
<span class="icon32 icon-color icon-envelope-closed <? if($val["readed"] == 0) echo "icon-envelope-closed"; else echo "icon-envelope-opened"; ?>" style="margin-top:-7px"></span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
<?= word_limiter(strip_tags($val["phone"]),10); ?></td>
<td class="" width="" >
<a href="mailto:<?=$val["email"]; ?>" style="color:#09C;" ><?=$val["email"]; ?></a></td>
<td class="" width="" style="color:#09C;">
<?= $val["created_date"]; ?>
</td>
<td class="row-nav"><a href="<?= base_url();?>back_office/messages/read/<?= $val["id"]; ?>" class="table-open-link cur" >
<i class=" icon-book" ></i> Read</a> <span class="hidden"> | </span>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id"];?>'); }" class="table-delete-link cur" >
<i class="bx bx-trash" ></i> Delete</a></td>
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