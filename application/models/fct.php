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

function getAll_orderdate($table){
$this->db->where('deleted',0); 
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


function getAll_001($table,$order){
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
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
if($this->db->table_exists('videos')){ $this->videos_ar_columns(); return; }
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
$this->videos_ar_columns();
if(!$this->db->where('name','videos')->count_all_results('content_type')){
	$now=date('Y-m-d H:i:s');
	$this->db->insert('content_type',array('name'=>'videos','url_name'=>'','icon'=>'','deleted'=>0,'created_date'=>$now,'updated_date'=>$now,'deleted_date'=>$now,'sort_order'=>9,'position'=>2,'gallery'=>0,'thumb_val_gal'=>'','resize_status'=>0,'used_name'=>'videos'));
}
}

// ---------- Completed projects (back_office/projects, "Our projects" section of the home page) ----------
// sector key => English name (the Arabic names are in config/site_lang.php and back_office_lang.php)
function project_sectors(){
return array(
	'restaurants'  => 'Restaurants',
	'bakeries'     => 'Bakeries & pastry shops',
	'butchers'     => 'Butcher shops',
	'supermarkets' => 'Supermarkets',
	'cafes'        => 'Cafés & coffee shops',
	'hotels'       => 'Hotels',
	'hospitals'    => 'Hospitals',
	'other'        => 'Other projects',
);
}

function projects_table(){
if(!$this->db->table_exists('projects')){
	$this->db->query("CREATE TABLE IF NOT EXISTS `projects` (
		`id_projects` int NOT NULL AUTO_INCREMENT,
		`deleted` int NOT NULL DEFAULT 0,
		`created_date` datetime DEFAULT NULL,
		`updated_date` datetime DEFAULT NULL,
		`deleted_date` datetime DEFAULT NULL,
		`sort_order` int NOT NULL DEFAULT 0,
		`status` int NOT NULL DEFAULT 0,
		`title` varchar(250) DEFAULT NULL,
		`title_ar` varchar(250) DEFAULT NULL,
		`sector` varchar(40) DEFAULT NULL,
		`place` varchar(150) DEFAULT NULL,
		`place_ar` varchar(150) DEFAULT NULL,
		`description` text,
		`description_ar` text,
		`photos` text,
		PRIMARY KEY (`id_projects`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}
if(!$this->db->where('name','projects')->count_all_results('content_type')){
	$now=date('Y-m-d H:i:s');
	$this->db->insert('content_type',array('name'=>'projects','url_name'=>'','icon'=>'','deleted'=>0,'created_date'=>$now,'updated_date'=>$now,'deleted_date'=>$now,'sort_order'=>8,'position'=>2,'gallery'=>0,'thumb_val_gal'=>'','resize_status'=>0,'used_name'=>'projects'));
}
foreach(array('uploads/projects','uploads/projects/thumb') as $dir){
	if(!is_dir(FCPATH.$dir)) @mkdir(FCPATH.$dir,0755,true);
}
}

// the photo file names of a project (the first one is the cover)
function project_photos($row){
$list=json_decode(isset($row['photos']) ? (string)$row['photos'] : '',true);
return is_array($list) ? array_values(array_filter($list,'is_string')) : array();
}

// one uploaded file ($_FILES-style array) -> a resized jpg/png in uploads/projects (max 1600 px)
// plus a 700 px thumbnail; returns the file name, or false when it is not a real image
function project_save_photo($file){
if(empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return false;
if($file['size'] > 15*1024*1024) return false;
$info=@getimagesize($file['tmp_name']);
if(!$info || !in_array($info[2],array(IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_WEBP),true)) return false;
switch($info[2]){
	case IMAGETYPE_JPEG: $img=@imagecreatefromjpeg($file['tmp_name']); break;
	case IMAGETYPE_PNG: $img=@imagecreatefrompng($file['tmp_name']); break;
	default: $img=function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file['tmp_name']) : false;
}
if(!$img) return false;
// phone photos: turn them the right way up
if($info[2]==IMAGETYPE_JPEG && function_exists('exif_read_data')){
	$exif=@exif_read_data($file['tmp_name']);
	$o=isset($exif['Orientation']) ? (int)$exif['Orientation'] : 1;
	if($o==3) $img=imagerotate($img,180,0); elseif($o==6) $img=imagerotate($img,-90,0); elseif($o==8) $img=imagerotate($img,90,0);
}
$name=date('Ymd').'_'.bin2hex(random_bytes(6)).'.jpg';
$ok=$this->project_resize($img,FCPATH.'uploads/projects/'.$name,1600)
	&& $this->project_resize($img,FCPATH.'uploads/projects/thumb/'.$name,700);
imagedestroy($img);
if(!$ok){ @unlink(FCPATH.'uploads/projects/'.$name); @unlink(FCPATH.'uploads/projects/thumb/'.$name); return false; }
return $name;
}

function project_resize($img,$dest,$max){
$w=imagesx($img); $h=imagesy($img);
$scale=min(1,$max/max($w,$h));
$nw=max(1,(int)round($w*$scale)); $nh=max(1,(int)round($h*$scale));
$out=imagecreatetruecolor($nw,$nh);
imagefill($out,0,0,imagecolorallocate($out,255,255,255));
imagecopyresampled($out,$img,0,0,0,0,$nw,$nh,$w,$h);
$ok=imagejpeg($out,$dest,84);
imagedestroy($out);
return $ok;
}

function project_delete_photo($name){
if(!preg_match('/^[A-Za-z0-9_.-]+$/',$name)) return;
@unlink(FCPATH.'uploads/projects/'.$name);
@unlink(FCPATH.'uploads/projects/thumb/'.$name);
}

// the projects shown on the website: only those with at least one photo
function get_projects(){
$this->projects_table();
$out=array();
foreach($this->getAll('projects','sort_order') as $row){
	$row['photo_list']=$this->project_photos($row);
	if(count($row['photo_list'])) $out[]=$row;
}
return $out;
}

// the Arabic title and text of each video (shown on the Arabic page)
function videos_ar_columns(){
if(!$this->db->field_exists('title_ar','videos')) $this->db->query("ALTER TABLE `videos` ADD `title_ar` varchar(250) DEFAULT NULL AFTER `title`");
if(!$this->db->field_exists('description_ar','videos')) $this->db->query("ALTER TABLE `videos` ADD `description_ar` text AFTER `description`");
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

}