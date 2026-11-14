<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fct extends CI_Model{
	
function write_file($path,$data){
if ( ! write_file($path, $data))
{
echo 'Unable to write the file';
}
else
{
//echo 'File written!';
}
}
    function search($wehereclause){
        $query = $this->db->query($wehereclause);
//echo $this->db->last_query();
        return $query->result_array();
    }
function getonecell($table,$select,$cond){
$this->db->where($cond);
$query=$this->db->get($table);
$res=$query->row_array();
if(count($res) >0)
return $res[$select];
}

function getonerow($table,$condition){
$this->db->where($condition);
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->row_array();
} 
}

function getonerecord($table,$condition){
$this->db->where('deleted',0);
$this->db->where($condition);
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->row_array();
} 
}

function getAll_1($table,$order){
//$this->db->where('deleted',0);
$this->db->order_by($order);
$query=$this->db->get($table);
return $query->result_array();
}
function getAll($table,$order){
$this->db->where('deleted',0);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}
function getAllHere($table,$order){
$this->db->where('deleted',0);
$this->db->where('id_locations',$this->session->userdata('country'));
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}
function getAllHere1($table,$order){
$this->db->where('deleted',0);
$this->db->where('active',1);
$this->db->where('id_locations',$this->session->userdata('country'));
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_orderdate($table){
$this->db->where('deleted',0); 
$this->db->order_by('created_date','DESC');
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_orderdate1($table){
$this->db->where('deleted',0); 
$this->db->where('active',1);
$this->db->where('id_locations',$this->session->userdata('country'));
$this->db->order_by('created_date','DESC');
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_orderdate2($table){
$this->db->where('deleted',0); 
$this->db->where('id_locations',$this->session->userdata('country'));
$this->db->order_by('created_date','DESC');
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_cond($table,$order,$cond){
$this->db->where('deleted',0);
$this->db->where($cond);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}


function getAll_limit($table,$order,$des,$limit){
$this->db->where('deleted',0);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order,$des); }
$query=$this->db->get($table,$limit);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_limit_cond($table,$order,$des,$limit,$cond){
$this->db->where('deleted',0);
$this->db->where($cond);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order,$des); }
$query=$this->db->get($table,$limit);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}


function getAll_001($table,$order){
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_desc($table,$order,$cond){
$this->db->where('deleted',0);
$this->db->where($cond);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order,'desc'); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getto_do_list(){
$yesterday = date("Y-m-d", time()-86400);
$q="SELECT * FROM `do_it` WHERE Deadline > '".$yesterday."' AND deleted = 0 ORDER BY  Deadline LIMIT 4";
$query=$this->db->query($q);
return $query->result_array();
}
function getto_do_list_completed(){
$yesterday = date("Y-m-d", time()-86400);
$q="SELECT * FROM `do_it` WHERE  deleted = 0  AND completed = 1 ORDER BY  Deadline LIMIT 4";
$query=$this->db->query($q);
return $query->result_array();
}

function module($id){
$this->db->where('id_module',$id);
$query=$this->db->get('module');
$res = $query->row_array();	
return $res["activate"];
}


function get_unreaded_emails(){
	$this->db->where('deleted',0); 
	$this->db->where('readed',0);
	$this->db->order_by('created_date','DESC');
	$query=$this->db->get('contactform');
	return $query->result_array();
}

// Delete Directory and all related
public static function deleteDirectory($dir){
if (!file_exists($dir)) return true;
if (!is_dir($dir)) return unlink($dir);
foreach (scandir($dir) as $item) {
if ($item == '.' || $item == '..') continue;
if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
}
return rmdir($dir);
}
// upload file 
// only real images are accepted: the extension and the content of the file are both checked
public function uploadImage($image,$path){
$config = array();
$config['allowed_types'] = 'jpg|jpeg|jpe|png|gif';
$config['upload_path'] ='./uploads/'.$path;
$config['max_size'] = '24048';
$this->load->library('upload',$config);

if(!$this->upload->do_upload($image,true)){
echo $this->upload_err = $this->upload->display_errors();
exit;
//return "";
}
else{
$path = $this->upload->data();
if(!$path['is_image'] || @getimagesize($path['full_path']) === FALSE){
@unlink($path['full_path']);
echo $this->upload_err = '<p>The file is not a valid image (jpg, png or gif).</p>';
exit;
}
return  $path['file_name'];
}
}
// create thumbs crop
public function createthumb($name,$table,$thumb_value){
$path = 'uploads/'.$table.'/';
// Create an array that holds the various image sizes
$configs = array();
$sumb_val=explode(",",$thumb_value);
foreach($sumb_val as $key => $value){
list($width,$height) = explode("x",$value);
$configs[] = array('source_image' => $name, 'new_image' => $value."/".$name, 'width' => $width, 'height' => $height, 'maintain_ratio' => FALSE);
}
$this->load->library('image_lib');
foreach ($configs as $config) {
$this->image_lib->thumb($config, FCPATH.''.$path);
}
}

// create thumbs resize
public function createthumb1($name,$table,$thumb_value){
$path = 'uploads/'.$table.'/';
// Create an array that holds the various image sizes
$configs = array();
$sumb_val=explode(",",$thumb_value);
foreach($sumb_val as $key => $value){
list($width,$height) = explode("x",$value);
$configs[] = array('source_image' => $name, 'new_image' => $value."/".$name, 'width' => $width, 'height' => $height, 'maintain_ratio' => TRUE);
}
$this->load->library('image_lib');
foreach ($configs as $config) {
$this->image_lib->thumb($config, FCPATH.''.$path);
}
}


public function date_in_formate($date ){
if($date !=""){
list($m,$d,$y)=explode('/',$date);
$date=$y."-".$m."-".$d;
} else {
$date = "0000-00-00";
}
return $date;	
}

public function date_out_formate($date){
if(!empty($date) && $date !="0000-00-00" ){
list($y,$m,$d)=explode('-',$date);
$date=$m."/".$d."/".$y;
} else {
$date ="";	
}
return $date;	
}

public function date_in_formate1($date){
if($date !=""){
list($d,$m,$y)=explode('/',$date);
$date=$y."-".$m."-".$d;
} else {
$date = "0000-00-00";
}
return $date;	
}

public function date_out_formate1($date){
if(!empty($date) && $date !="0000-00-00" ){
list($y,$m,$d)=explode('-',$date);
$date=$d."/".$m."/".$y;
} else {
$date ="";	
}
return $date;	
}

// Delete Related images when delete any items from the recycle Bin .
public function deleter_related_images($content_type,$id){
$tables=preg_replace('/[^a-z0-9_]/i','',str_replace(" ","_",$content_type));
$q1="SELECT * FROM `".$tables."` WHERE id_".$tables." = ".(int)$id;
$query1=$this->db->query($q1);
$row= $query1->row_array();
$q="SELECT name,thumb_val,thumb FROM `content_type_attr` WHERE id_content = (SELECT id_content FROM `content_type` WHERE name =".$this->db->escape($content_type).") AND (type = 4 OR type =5)";
$query=$this->db->query($q);
$related= $query->result_array();

foreach($related as $vv){
if($row[$vv["name"]] != ''){
if(file_exists('./uploads/'.$tables.'/'.$row[$vv["name"]])){
unlink('./uploads/'.$tables.'/'.$row[$vv["name"]]);  }
if($vv["thumb"] == 1){
$sumb_val0=explode(",",$vv["thumb_val"]);
foreach($sumb_val0 as $key => $value){
	if(file_exists('./uploads/'.$tables.'/'.$value.'/'.$row[$vv["name"]])){
unlink("./uploads/".$tables."/".$value."/".$row[$vv["name"]]); }								
} }  }

}
}




public function outputKey()  
{  
$ip = $_SERVER['REMOTE_ADDR'];
$uniqid = uniqid(mt_rand(), true); 
$formKey = md5($ip . $uniqid);
$this->session->set_userdata('formKey',$formKey);
echo  "<input type='hidden' name='form_key' id='form_key' value='".$formKey."' />";  
}  

public function check_token(){
$token = $this->session->userdata('formKey');
if($this->input->post('form_key') == $token){
$res = true;	
}
else { 
$res =  false;
}
$this->session->unset_userdata('formKey');
return $res;
}

function getcountry($id_locations){
$this->db->select('locations.*, countries.*');
$this->db->from('locations');
$this->db->join('countries','countries.id_countries = locations.id_countries');
$this->db->where('locations.id_locations' , $id_locations);
$this->db->where('locations.deleted' , 0);
$query=$this->db->get();
$res = $query->result_array();
return $res;
}


function getmaxsort($table,$array){
$this->db->select_max('sort_order');
$this->db->where_in('id_'.$table,$array);
$query=$this->db->get($table);
$res = $query->row_array();
return $res["sort_order"];
}

function get_first_row($table,$where=array()){
if(count($where) > 0)
$query = $this->db->get_where($table,$where);		
else
$query = $this->db->get($table);
if($query->num_rows() > 0)
{
	return $query->first_row();
} else { return array(); }	
}

function get_last_row($table,$where=array()){
if(count($where) > 0)
$query = $this->db->get_where($table,$where);		
else
$query = $this->db->get($table);
if($query->num_rows() > 0)
{
	return $query->last_row();
} else { return array(); }	
}

function union_tables($tabel1 , $table2){
$this->db->select('title, description, created_date');
$this->db->from($tabel1);
$subQuery1 = $this->db->_compile_select();
$this->db->_reset_select();	
$this->db->select('title, destination AS description, created_date');
$this->db->from($table2);
$subQuery2 = $this->db->_compile_select();
$this->db->_reset_select();
$query = $this->db->query($subQuery1." UNION ".$subQuery2);
return $query->result_array();
}

function another_way_for_union(){
$this->db->select('title, content, date');
$this->db->from('mytable1');
$query1 = $this->db->get()->result();

$this->db->select('title, content, date');
$this->db->from('mytable2');
$query2 = $this->db->get()->result();
// Merge both query results
$query = array_merge($query1, $query2);
}


function if_existings($table, $cond){
$this->db->where($cond);
$query = $this->db->get($table);
return ($query->num_rows() > 0)? true : false;
}


function soso(){
//$fields = $this->db->list_fields('maintenance');
$query = $this->db->query('SELECT deleted FROM maintenance'); 
$fields = $query->list_fields();
foreach ($fields as $field)
{
   echo $field."<br />";
} 
$fields = $this->db->field_data('maintenance');
foreach ($fields as $field)
{
   echo $field->name;
   echo $field->type;
   echo $field->max_length;
   echo $field->primary_key;
   echo "<br />";
} 	
}

function to_excel($query, $filename = 'exceloutput')
{
    $headers = ''; // just creating the var for field headers to append to below
    $data = ''; // just creating the var for field data to append to below

    $obj = &get_instance();
$query = $this->db->query($query);
    $fields = $query->field_data();
    if ($query->num_rows() == 0)
    {
        #echo '<p>The table appears to have no data.</p>';
        return 'empty';
    }
    else
    {
        # Headers array
        $headers_array = array();
        
        foreach ($fields as $field)
        {
            # Add to headers
            if (!in_array($field->name,$headers_array))
            {
                $headers .= $field->name . "\t";
                $headers_array[] = $field->name;
            }

        }

        foreach ($query->result() as $row)
        {
            $line = '';
            foreach ($row as $value)
            {
                if ((!isset($value)) or ($value == ""))
                {
                    $value = "\t";
                }
                else
                {
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                $line .= $value;
            }
            $data .= trim($line) . "\n";
        }

        $data = str_replace("\r", "", $data);

        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=$filename.xls");
        echo "$headers\n$data";
    }
} 



public function GetRestRequestOrder(){
$q= "SELECT * FROM request_order WHERE  deleted = 0 AND id_request_order NOT IN ( SELECT id_request_order FROM request_quotaion ) AND id_request_order NOT IN ( SELECT id_request_order FROM purchase_order ) AND id_locations = ".$this->session->userdata('country')." ORDER BY sort_order";
$query = $this->db->query($q);	
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

public function check_subqoutation($id){
$this->db->where('id_sb',$id);
$query = $this->db->get('request_quot_sb');
if($query->num_rows() == 0)
return false; 
else 
return true;
}

public function GetRestQoutation(){
$q= "SELECT * FROM request_quotaion WHERE  deleted = 0 AND id_request_quotaion NOT IN ( SELECT id_request_quotaion FROM purchase_order ) AND id_locations = ".$this->session->userdata('country')." ORDER BY sort_order";
$query = $this->db->query($q);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }	
}
 
 
public function select_currency(){
$this->db->select('countries.currency_code');
$this->db->from('countries');
$this->db->join('locations','locations.id_countries = countries.id_countries');
$this->db->group_by('locations.id_countries');
$this->db->order_by('locations.sort_order');
$query=$this->db->get();	
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

public function GetPurchaseOrder(){ 
$q= "SELECT * FROM purchase_order WHERE  deleted = 0 AND id_purchase_order NOT IN ( SELECT id_purchase_order FROM receving_po ) AND id_locations = ".$this->session->userdata('country')." ORDER BY sort_order";
$query = $this->db->query($q);
if($query->num_rows() > 0 ){
return $query->result_array();	
} else { return array(); }
}

public function check_subreceiving($id){
$this->db->where('id_purchase_order_sb',$id);
$query = $this->db->get('receving_po_sb');
if($query->num_rows() > 0)
return $query->row_array(); 
else 
return array();
}


function GetBirthdaysThisMonth(){
$month =  date('m');
$q= "SELECT *  FROM clients WHERE dob LIKE '%-".$month."-%' AND active = 1 AND deleted = 0";
$query = $this->db->query($q);
$result = $query->result_array();
if($query->num_rows() > 0 ){
	return $query->num_rows();
}else{
	 return '';
}
}


function getAll_calls($handled){
$this->db->where('deleted',0); 
$this->db->where('handled',$handled); 
$this->db->order_by('created_date','DESC');
$query=$this->db->get('call_center');
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_complains($solved){
$this->db->where('deleted',0); 
$this->db->where('solved',$solved); 
$this->db->order_by('created_date','DESC');
$query=$this->db->get('complains');
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function get_temp_list($id){
	$this->db->select('newsletter_recipients.*,newsletter_temp_list.id_temp_list');
	$this->db->from('newsletter_temp_list');
	$this->db->join('newsletter_recipients','newsletter_recipients.id_recipients = newsletter_temp_list.id_recipients');
	$this->db->where('newsletter_temp_list.id_template',$id);
	$this->db->where('newsletter_recipients.deleted',0);
	$query = $this->db->get();
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

public function upload_ajax($file,$path){
$actual_image_name='';
$valid_formats = array("jpg", "jpeg", "png", "gif");
$name = $_FILES[$file]['name'];
$size = $_FILES[$file]['size'];	
if(strlen($name))
{
$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
$txt = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($name, PATHINFO_FILENAME));
if(in_array($ext,$valid_formats) && @getimagesize($_FILES[$file]['tmp_name']) !== FALSE)
{
if($size<(1024*1024))
{
//$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
$actual_image_name = str_replace(" ", "_", $txt).time().".".$ext;
$tmp = $_FILES[$file]['tmp_name'];
if(move_uploaded_file($tmp, $path.$actual_image_name))
{
//mysql_query("UPDATE users SET profile_image='$actual_image_name' WHERE uid='$session_id'");

//echo "<img src='".$path.$actual_image_name."'  class='preview' width='50' height='50'>";
//echo "<img src='".base_url()."images/activate.png' />&nbsp;&nbsp;<span style='color:#5ac43f'>file was uploaded successfully</span>";
return $actual_image_name;
exit;
}
else
echo "<img src='".base_url()."images/icon-error.gif' />&nbsp;&nbsp;<span style='color:#A5000B'>failed</span>";
exit;
}
else
echo "<img src='".base_url()."images/icon-error.gif' />&nbsp;&nbsp;<span style='color:#A5000B'>Image file size max 1 MB</span>";	
exit;				
}
else
echo "<img src='".base_url()."images/icon-error.gif' />&nbsp;&nbsp;<span style='color:#A5000B'>Invalid file format..</span>";
exit;	
}

else{
echo "<img src='".base_url()."images/icon-error.gif' />&nbsp;&nbsp;<span style='color:#A5000B'>Please select image..!</span>";
exit;
}	
}


// home page texts as name => value: every field of config/home_page.php, filled from the home_page_text table
function get_home_page(){
$this->config->load('home_page');
$texts=array();
foreach($this->config->item('home_page_sections') as $fields){
foreach($fields as $name => $field) $texts[$name]='';
}
foreach($this->db->get('home_page_text')->result_array() as $row){
if(isset($texts[$row['name']])) $texts[$row['name']]=(string)$row['value'];
}
return $texts;
}

// videos (back_office/videos): the table and its menu entry are created on first use
function videos_table(){
if($this->db->table_exists('videos')) return;
$this->db->query("CREATE TABLE IF NOT EXISTS `videos` (
	`id_videos` int NOT NULL AUTO_INCREMENT,
	`deleted` int NOT NULL DEFAULT 0,
	`created_date` datetime DEFAULT NULL,
	`updated_date` datetime DEFAULT NULL,
	`deleted_date` datetime DEFAULT NULL,
	`sort_order` int NOT NULL DEFAULT 0,
	`status` int NOT NULL DEFAULT 0,
	`title` varchar(250) DEFAULT NULL,
	`video_url` varchar(500) DEFAULT NULL,
	`description` text,
	PRIMARY KEY (`id_videos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
if(!$this->db->where('name','videos')->count_all_results('content_type')){
	$now=date('Y-m-d H:i:s');
	$this->db->insert('content_type',array('name'=>'videos','url_name'=>'','icon'=>'','deleted'=>0,'created_date'=>$now,'updated_date'=>$now,'deleted_date'=>$now,'sort_order'=>9,'position'=>2,'gallery'=>0,'thumb_val_gal'=>'','resize_status'=>0,'used_name'=>'videos'));
}
}

// the videos shown on the website, each with its player link
function get_videos(){
$this->videos_table();
$videos=array();
foreach($this->getAll('videos','sort_order') as $row){
	$embed=$this->video_embed($row['video_url']);
	if($embed) $videos[]=array_merge($row,$embed);
}
return $videos;
}

// a Facebook or YouTube link -> array('src' => player address, 'vertical' => reel / short, 'source' => facebook|youtube), or false
function video_embed($url){
$url=trim((string)$url);
if(!preg_match('#^https?://#i',$url)) $url='https://'.$url;
$parts=parse_url($url);
if(!$parts || empty($parts['host'])) return false;
$host=strtolower(preg_replace('/^(www|m|web|mobile)\./i','',$parts['host']));
$path=isset($parts['path']) ? $parts['path'] : '';
if($host=='facebook.com' || $host=='fb.watch' || $host=='fb.com'){
	$vertical=(bool)preg_match('#/(reel|reels)/#i',$path);
	return array(
		'source'=>'facebook',
		'vertical'=>$vertical,
		'src'=>'https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=false&width='.($vertical ? 267 : 560).'&t=0',
	);
}
if($host=='youtube.com' || $host=='youtu.be' || $host=='youtube-nocookie.com'){
	$id='';
	if($host=='youtu.be') $id=trim($path,'/');
	elseif(preg_match('#^/(shorts|embed|live)/([^/?]+)#',$path,$m)) $id=$m[2];
	elseif(isset($parts['query'])){ parse_str($parts['query'],$q); if(!empty($q['v'])) $id=$q['v']; }
	if(!preg_match('/^[A-Za-z0-9_-]{6,20}$/',$id)) return false;
	return array(
		'source'=>'youtube',
		'vertical'=>strpos($path,'/shorts/')===0,
		'src'=>'https://www.youtube-nocookie.com/embed/'.$id.'?rel=0',
	);
}
return false;
}

function parent_rows($id = 0, $arr){
$q = "SELECT * FROM testing WHERE id_parent = ".(int)$id;
$query = $this->db->query($q);
if($query->num_rows() > 0){
$res = $query->result_array();
foreach($res as $val){
$arr[] = $val;
$arr = $this->parent_rows($val["id"],$arr);	
}
}
return $arr;
}

}