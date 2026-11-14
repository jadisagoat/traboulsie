<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location='<?= base_url(); ?>back_office/events/delete/'+id;
return false;
}


$(function(){
$('#table-1').tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$('#result').load('<?= base_url(); ?>back_office/events/sorted?'+ser);
}
});
});
</script>
<div id="content">
<div id="content-top">
<h2>Events</h2>
<a href="<?= base_url(); ?>back_office/events/add" id="topLink">Create New Event</a> 
<span class="clearFix">&nbsp;</span>
</div>
<!-- end of div#content-top -->
<? $this->load->view('back_office/includes/left_box'); ?>
<!-- end of div#left-col -->

<div class="full-col">
<div id="result"></div>
<div class="box">      
<h4 class="white" id="rami"><?= $title; ?></h4>
<div class="box-container">
<form action="<?= base_url(); ?>back_office/events/delete_all" method="post" name="list_form" >    		
<table class="table-long" id="table-1">
<thead>
<? if($this->session->userdata('success_message')){ ?>
<tr><td colspan="5" align="center" style="text-align:center">
<span class="form-confirm-inline">
<?= $this->session->userdata('success_message'); ?>
</span>
</td>
<tr>
<? } ?>
<tr>
<td>&nbsp;</td>
<td>Event Title</td>
<td>Date</td>
<td>Image</td>
<td>Action</td>
</tr>
</thead>
<tfoot>

<tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="5"><div class="align-right">
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="button" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a></div></td>
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
<tr class="<?= $odd; ?>" id="<?=$val["id_events"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_events"] ; ?>" /></td>
<td class="" width=""><?= $val["title"] ; ?></td>
<td class="" width="" style="color:#74d24c;">
<?=$val["date"]; ?></td>
<td class="" width="" style="color:#74d24c;">
<?
if(  $val["image"] != ""){
?>	
<a href="<?= base_url(); ?>uploads/events/<?= $val["image"] ?>" class="blue gallery" title="<?= $val["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/events/delete_image/image/<?= $val["image"]; ?>/<?= $val["id_events"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?></td>
<td class="row-nav"><a href="<?= base_url();?>back_office/events/edit/<?= $val["id_events"]; ?>" class="table-edit-link cur cur" >Edit</a> <span class="hidden"> | </span>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_events"];?>'); }" class="table-delete-link cur" >Delete</a></td>
</tr>
<? }  } else { echo '<tr class="odd"><td colspan="5" style="text-align:center;">No records available . </td></tr>'; } ?>
</tbody>
</table>  	
</form>
</div>
</div><!-- end of div.box -->


</div><!-- end of div#mid-col -->

<!-- end of div#right-col -->     
<span class="clearFix">&nbsp;</span>     
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 