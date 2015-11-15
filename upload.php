<?php
error_reporting(0);
$assets = "assets/images";
$change="";
$abc="";


 define ("MAX_SIZE","400");
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

 $errors=0;
	/** upload function **/


	if(isset($_POST['upload'])){

   	$image =$_FILES["file"]["name"];
  	$uploadedfile = $_FILES['file']['tmp_name'];
       
	   
	if ($image){
	   	
	   		$filename = stripslashes($_FILES['file']['name']);
	   		$extension = getExtension($filename);
	   		$extension = strtolower($extension);
	  		
	  		
	   if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
	   			$change='<p>Unknown Image extension</p>';
	   			$errors=1;
	   		}else{

			   $size=filesize($_FILES['file']['tmp_name']); 
			  if ($size > MAX_SIZE*1024){
			  	$change='<p>You have exceeded the size limit! </p>';
			  	$errors=1;
			  }
		 
		 
			}
	}else{
		$change = "<p>no file uploaded</p>";
		$errors = 1;
	}

	if($errors == 0){ 
		$change .= resizeImg($assets.date("/Y/m")."/bonus",470,$_FILES['file'],$extension);
		$change .= resizeImg($assets.date("/Y/m")."/bonus/respansive",240,$_FILES['file'],$extension); 
	}

		echo '<div class="result">'.$change.'</div>'; 

}

function resizeImg($folder,$newwidth,$file,$extension){ 
	$dir = explode('/', $folder);
	$url = "";
	foreach ($dir as $key => $value) {
		$url .= $value.'/';
		if (!file_exists($url)) {mkdir($url,0777); }
	}
    
	$filename = $folder.'/'.md5($file['name']).'-'.$newwidth.'.'.$extension;
	if (file_exists($filename)) {
		$filename = $folder.'/'.md5($file['name']).rand().'-'.$newwidth.'.'.$extension;
	}
	/* img extension */
	if($extension=="jpg" || $extension=="jpeg" ){
		  $uploadedfile = $file['tmp_name'];
		  $src = imagecreatefromjpeg($uploadedfile);

	  }else if($extension=="png"){
		  $uploadedfile = $file['tmp_name'];
		  $src = imagecreatefrompng($uploadedfile);

	  }else{
	  		$src = imagecreatefromgif($uploadedfile);
	  }

	list($width,$height)=getimagesize($uploadedfile);

	
	  $newheight=($height/$width)*$newwidth;
	  $tmp=imagecreatetruecolor($newwidth,$newheight);
	  imagealphablending($tmp, false);
	  imagesavealpha($tmp, true);
	  imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

	if ($extension == "png") {
	  imagepng($tmp,$filename); 
	}else{
	    imagejpeg($tmp,$filename,100);  
	}

	$data = "<img src='".$filename."' class='pic' title='".$newwidth."' />";
	  imagedestroy($src);
	  imagedestroy($tmp);
	  return $data;

}