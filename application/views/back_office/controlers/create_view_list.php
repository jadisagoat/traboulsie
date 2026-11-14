<?
$list_view='
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/'.$table_name.'/delete/"+id;
return false;
}


$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/'.$table_name.'/sorted?"+ser);
}
});
});
</script>';
$content_name = $table_name;
$cond1["name"]=str_replace("_"," ",$content_name);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$gallery_status=$this->fct->getonecell("content_type","gallery",$cond1);
$list_view.='
<?php
$content_name="'.$content_name.'";
$id_content="'.$id_content.'";
$sego =$this->uri->segment(4);
$gallery_status="'.$gallery_status.'";
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$read_priv=$this->fct->getonecell("priviledge","read_p",$cond);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
$delete_priv=$this->fct->getonecell("priviledge","delete_p",$cond);
} else { 
$read_priv = 1; 
$write_priv=1;
$delete_priv = 1;
}
if($read_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }	
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>

<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active"><?= $title; ?></li>
<? if($write_priv == 1){ ?>
<li class="pull-right">
<a href="<?= base_url(); ?>back_office/'.$table_name.'/add" id="topLink" title="Add '.$content_type.'">Add '.$content_type.'</a>
</li><? } ?>
</ul> 
</div>
<div class="hundred pull-left">   
<div id="result"></div>  
<form action="<?= base_url(); ?>back_office/'.$table_name.'/delete_all" method="post" name="list_form" >    		
<table class="table table-striped" id="table-1">
<thead>
<? if($this->session->userdata("success_message")){ ?>
<tr><td colspan="'.(count($attr)+2).'" align="center" style="text-align:center">
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
</td>
<tr>
<? } ?>
<tr>
<td>&nbsp;</td>';
foreach($attr as $val){
if($val["type"] != 4 && $val["type"] != 5 && $val["type"] != 7)
$list_view.='<th>
<a href="<?= base_url(); ?>back_office/'.$table_name.'/index/'.$val["name"].'" class="<? if($sego == "'.$val["name"].'") echo \'order_active\'; else echo \'order_link\'; ?>" >'.ucfirst($val["name"]).'</a></th>
';
else
$list_view.='<th>'.ucfirst($val["name"]).'</th>
';
}
$list_view.='<th style="text-align:right;padding-right:80px;">Action</th></tr></thead><tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="'.(count($attr)+2).'"><div class="pull-right">
<? if($delete_priv == 1){ ?>
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms[\'list_form\'].submit();" style="cursor:pointer;"><span>perform action</span></a></div> <? } ?></td>
</tr>
</tfoot>
<tbody>
<? 
if(count($info) > 0){
$i=0;
foreach($info as $val){
$i++; 
?>
<tr id="<?=$val["id_'.$table_name.'"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_'.$table_name.'"] ; ?>" /></td>';
foreach($attr as $v){
$list_view.='<td class="" width="">';
if($v["type"] == 7){
$cond001=array("id_content" => $v["foreign_key"]);
$content001 = $this->fct->getonecell("content_type","name",$cond001);
if(str_replace(" ","_",$content001) == $table_name)
$frn1 = "parent";
else
$frn1 = str_replace(" ","_",$content001);
$list_view.='<? if($val["id_'.$frn1.'"] != 0){
$cond=array("id_'.str_replace(" ","_",$content001).'" => $val["id_'.$frn1.'"]);
echo $this->fct->getonecell("'.str_replace(" ","_",$content001).'","title",$cond); } else { echo "<small>Not available</small>"; }   ?>
';
}
elseif($v["type"] == 6 ){ 
$list_view.='<? if($val["'.str_replace(" ","_",$v["name"]).'"] == 1)
echo "<span class=\' label label-success\' >Active</span>";
else
echo "<span class=\' label label-important \' >InActive</span>";   ?>
';
}
elseif($v["type"] == 4){ 
$list_view.='<? if($val["'.str_replace(" ","_",$v["name"]).'"] != ""){ ?>
<a href="<?= base_url(); ?>uploads/'.$table_name.'/<?=$val["'.str_replace(" ","_",$v["name"]).'"];?>" class="blue gallery" title="<?=$val["title"];?>" >
show image</a>&nbsp;&nbsp;&nbsp;';
$list_view.='<a class="cur" onclick="if(confirm(\'Are you sure you want to delete this image ?\')){ window.location=\' <?= base_url(); ?>back_office/'.$table_name.'/delete_image/'.str_replace(" ","_",$v["name"]).'/<?= $val["'.str_replace(" ","_",$v["name"]).'"];?>/<?=$val["id_'.$table_name.'"]; ?>\' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { 
echo "<small class=\'blue\'>No Image Available</small>"; } ?>
';
} 
elseif($v["type"] == 5){
$list_view.=' <? if( $val["'.str_replace(" ","_",$v["name"]).'"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/'.$table_name.'/<?=$val["'.str_replace(" ","_",$v["name"]).'"]; ?>" class="blue" title="<?=$val["title"];?>" >
show file</a>&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm(\'Are you sure you want to delete this file ?\')){ window.location=\' <?= base_url(); ?>back_office/'.$table_name.'/delete_image/'.str_replace(" ","_",$v["name"]).'/<?=$val["'.str_replace(" ","_",$v["name"]).'"];?>/<?=$val["id_'.$table_name.'"];?>\' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<small class=\'blue\'>No File Available</small>"; } ?>
';	
}
elseif($v["type"] == 1){
$list_view.='<small><? echo $this->fct->date_out_formate($val["'.str_replace(" ","_",$v["name"]).'"]);?></small>';	
}
else { 
$list_view.='<? echo $val["'.str_replace(" ","_",$v["name"]).'"]; ?>
';
}
$list_view.='</td>';

} 
$list_view.='<td style="text-align:right">';
if($gallery_status == 1){ 
$list_view.='<a href="<?= base_url(); ?>back_office/control/manage_gallery/'.$content_name.'/<?= $val["id_'.$table_name.'"] ?>" title="Add Photos">
<i class="bx bx-photo-album"></i> Manage Gallery</a>&nbsp;';
} 
$list_view.='<? if($write_priv == 1){ ?>
<a href="<?= base_url();?>back_office/'.$table_name.'/edit/<?= $val["id_'.$table_name.'"]; ?>" class="table-edit-link" title="Edite" >
<i class="bx bx-edit" ></i> Edit</a> <span class="hidden"> | </span>
<? } ?>
<? if($delete_priv == 1){ ?>
<a onclick="if(confirm(\'Are you sure you want delete this item ?\')){ delete_row(\'<?=$val["id_'.$table_name.'"];?>\'); }" class="table-delete-link cur" title="Delete" >
<i class="bx bx-trash" ></i> Delete</a>
<? } ?>
</td>
</tr>
<? }  } else { ?>
<tr class=\'odd\'><td colspan="'.(count($attr)+2).'" style=\'text-align:center;\'>No records available . </td></tr>
<?  } ?>
</tbody>
</table>  	
</form>
</div>
</div>
</div>   
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> ';