<?php
use Jaykeren\ImageMoo\Facades\ImageMoo;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Aws\Laravel\AwsServiceProvider;
if (! function_exists('thumb_image')) {
    function thumb_image($path = "", $size = "", $type = "")
    {
       if($type == 'produk'){
			$server_thumbs   = env('MEDIA_PRODUK_THUMB');	
			$server_original = env('MEDIA_PRODUK');
		}else if($type == 'blog'){
			$server_thumbs   = env('MEDIA_BLOG_THUMB');
			$server_original = env('MEDIA_BLOG');
		}else if($type == 'img_ori'){
			$server_original = env('MEDIA_ORIGINAL_TEMP');
		}else if($type == 'img_ori_thumb'){
			$server_original = env('MEDIA_ORIGINAL_THUMB');
		}else if($type == 'icon_industri'){
			$server_original = env('MEDIA_ICON_INDUSTRI');
		}else if($type == 'advertiser'){
			$server_original = env('MEDIA_ADVERTISER');
		}else if($type == 'img_industri'){
			$server_original = env('MEDIA_INDUSTRI');
		}else if($type == 'images_negara'){
			$server_original = env('MEDIA_NEGARA');
		}else if($type == 'images_sismik2d'){
			$server_original = env('MEDIA_SISMIK2d');
		}else if($type == 'images_sismik3d'){
			$server_original = env('MEDIA_SISMIK3d');
		}else if($type == 'marketing'){
			$server_thumbs   = env('MEDIA_MARKETING_THUMB');
			$server_original = env('MEDIA_MARKETING');
		}else if($type == 'artikelmember'){
			$server_thumbs   = env('MEDIA_ARTICLE_MEMBER_THUMB');
			$server_original = env('MEDIA_ARTICLE_MEMBER');
		}else if($type == 'seismik2d'){
			$server_original = env('MEDIA_SISMIK2d');
		}else if($type == 'seismik3d'){
			$server_original = env('MEDIA_SISMIK3d');
		}else{
			$server_thumbs   = env('MEDIA_THUMBS');
			$server_original = env('MEDIA');
		}

      
		if($path != "") {
			$str_path = "";
			$exp_img = explode(".", $path);			
			
			if(isset($size) && $size != "") {
				
				if($size == "620x413shared1"){
					$size ="620x413";
					$size_true ="620x413shared1";
					if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1])){
						$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1]);
					}else{
						if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])){
							$name_array = explode("/", $exp_img[0]);
							$s3_file = Storage::disk('s3')->get($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
							$s3 = Storage::disk('public');
							$s3->put("./".$name_array[3]."_".$size."_thumb.".$exp_img[1], $s3_file);
						
							if(Storage::disk('local')->exists(env('PUBLIC_STORAGE').$name_array[3]."_".$size."_thumb.".$exp_img[1])){	
								$str_path_new = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
								$file_final_name_og = $exp_img[0].'_'.$size.'_shared1.'.$exp_img[1];
								$des_path_og =  $server_thumbs . $file_final_name_og;
								$generate =  ImageMoo::load(Storage::disk('local')->path(env('PUBLIC_STORAGE').$name_array[3]."_".$size."_thumb.".$exp_img[1]))
									->resize_crop('600' ,'315')
									->set_watermark_transparency(100)
									->load_watermark(env('PUBLIC_STORAGE')."logo.png")
									->watermark(3,0)
									->save(Storage::disk('local')->path(env('PUBLIC_STORAGE').$name_array[3]."_".$size."_shared1.".$exp_img[1]));
									Storage::disk('s3')->put($server_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1], fopen(Storage::disk('local')->path(env('PUBLIC_STORAGE').$name_array[3]."_".$size."_shared1.".$exp_img[1]),'r+'),'public');
									// dihapus
									$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1]);

							}else{
								$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
							}
						}else{
							$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
						}
						
					}
				}else if($size == "500x500shared"){
					$size ="500x500";
					if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_".$size."_shared.".$exp_img[1])){
						$str_path =Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_shared.".$exp_img[1]);
					}else{
						if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])){
							$str_path = $str_path =Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
						} else {
							if ($type == 'profile'){
								$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_picture_profile.jpg";
							} else if ($type == 'cover'){
								$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."cover-header".rand(2,4).".jpg";
							} else if ($type == "profil" || $type == "logo" || $type == "icon_industri" || $type == "img_industri" || $type == "opini" || $type == "advertiser") {
								if(file_exists($path_original.$path)) {
									$str_path = Storage::disk('s3')->url($server_original.$path);
								} else {
									if ($type == 'opini') {
										$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_square.jpg";
									}else{
										$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
									}
								}
							} else if ($type == "images_negara") {
								if(file_exists($server_original.$path)) {
									$str_path = Storage::disk('s3')->url($server_original.$path);
								} else {
									if ($type == 'opini') {
										$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_square.jpg";
									}else{
										$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
									}
								}
							} else {
								$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
							}
						}
					}
				}else{
					if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])){
						$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
					} else {
						
						if ($type == 'profile'){
							$str_path =	Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_picture_profile.jpg";
						} else if ($type == 'cover'){
							$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."cover-header".rand(2,4).".jpg";
						} else if ($type == "profil" || $type == "logo" || $type == "icon_industri" || $type == "img_industri" || $type == "opini" || $type == "advertiser") {
							if(file_exists($server_original.$path)) {
								$str_path = Storage::disk('s3')->url($server_original.$path);
							} else {
								if ($type == 'opini') {
									$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_square.jpg";
								}else{
									$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
								}
							}
						} else if ($type == "images_negara") {
							if(Storage::disk('s3')->exists($server_original.$path)){
								$str_path = Storage::disk('s3')->url($server_original.$path);
							} else {
								if ($type == 'opini') {
									$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_square.jpg";
								}else{
									$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
								}
							}
						} else {
							$str_path =  Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
						}
					}
				}
				
			} else {
				if ($type != "kuis") {
					
					if(Storage::disk('s3')->exists($server_original.$path)){
						$str_path = Storage::disk('s3')->url($server_original.$path);
					} else {
						if($type == 'profile'){
							return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_picture_profile.jpg";
	
						}else if($type == 'cover'){
							return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."cover-header".rand(2,4).".jpg";
							
						}else if($type == 'opini'){
							return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_square.jpg";
						}else if($type == 'img_ori_thumb'){
							$server_original = env('MEDIA_ORIGINAL_THUMB');
							if(Storage::disk('s3')->exists($server_original.$path)){
								$str_path = Storage::disk('s3')->url($server_original.$path);
							} else {
								$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
							}
						}else{
							$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
						}
					}
				} else {
					if(Storage::disk('s3')->exists($server_original.$path)){

						if (isset($type) && $type == "kuis") {
							$str_path =  Storage::disk('s3')->url($server_original.$path);
						}else{
							$str_path =  Storage::disk('s3')->url($server_thumbs.$path);
						}
						
					} else {
						$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
					}
				}
			}
			return $str_path;
		} else {
			if($type == 'profile'){
				return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_picture_profile.jpg";
			}else if($type == 'cover'){
				return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."cover-header".rand(2,4).".jpg");
			}else if($type == 'opini'){
				return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_square.jpg");
			}else{
				return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_news.jpg");
			}
		}
    }
}
if(!function_exists('thumb_onthefly_Article')){
	function thumb_onthefly_Article($path = "", $width = "0", $height = "0", $type = "crop"){
		$namafiles = explode('/', $path);
		$namafiles = end($namafiles);
		$dirpath = implode('/', explode('/', $path, -1));
		$extensionfiles = explode('.', $namafiles);
		$extensionfiles = end($extensionfiles);
		$namaaja = explode('.', $namafiles, -1);
		$namaaja = end($namaaja);
		$ratio = $width / $height;
		$newpathfiles = $width.'_'.$height.'_'.$namaaja.'.'.$extensionfiles;
		$pathfileslokal = $namaaja.'.'.$extensionfiles;
		if(Storage::disk('s3')->exists(env('MEDIA_OLDMEDIA').$namaaja.'.'.$extensionfiles)){
			if(Storage::disk('s3')->exists(env('MEDIA_OLDMEDIA_THUMB').$newpathfiles)){
				return Storage::disk('s3')->url(env('MEDIA_OLDMEDIA_THUMB').$newpathfiles);
			}else{
				$s3_file = Storage::disk('s3')->get(env('MEDIA_OLDMEDIA').$namaaja.'.'.$extensionfiles);
                $s3 = Storage::disk('public');
				$s3->put("./".$pathfileslokal, $s3_file);
				if(Storage::disk('local')->exists(env('PUBLIC_STORAGE').$pathfileslokal)){				
					if(file_exists(Storage::disk('local')->path(env('PUBLIC_STORAGE').$pathfileslokal))){
						$resizeObj = new resize(Storage::disk('local')->path(env('PUBLIC_STORAGE').$pathfileslokal)); 
						$doresize = $resizeObj -> resizeImage($width, $height, $type);
						$resizeObj -> saveImage(Storage::disk('local')->path(env('PUBLIC_STORAGE').$newpathfiles), 60);
						if(file_exists(Storage::disk('local')->path(env('PUBLIC_STORAGE').$newpathfiles))){
							Storage::disk('s3')->put(env('MEDIA_OLDMEDIA_THUMB').$newpathfiles, fopen(Storage::disk('local')->path(env('PUBLIC_STORAGE').$pathfileslokal),'r+'),'public');
							if(Storage::disk('s3')->exists(env('MEDIA_OLDMEDIA_THUMB').$newpathfiles)){
								return Storage::disk('s3')->url(env('MEDIA_OLDMEDIA_THUMB').$newpathfiles);
							}else{
								return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_news.jpg");
							}
						}else{
							return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_news.jpg");
						}
					}else{
						return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_news.jpg");
					}
					
				}else{
					return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_news.jpg");
				}
			}
			
		}else{
			return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')."no_image_news.jpg");
		}
	}
}
if ( ! function_exists('thumb_image_cover')) {
	function thumb_image_cover ($path = "", $size = "", $type = ""){
		## DEFINE THE IMAGE ##

		$server_thumbs   = env('MEDIA_THUMBS');
		$server_original = env('MEDIA');

		if($path != "") {
			$str_path = "";
			$exp_img = explode(".", $path);			
			
			$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
			return $str_path;
		} else {
			return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES'))."no_image_news.jpg";
		}
	}
}
if ( ! function_exists('thumb_image_analisis')) {
	function thumb_image_analisis($path = ""){
		$server_original =  env('MEDIA');

		$str_path = Storage::disk('s3')->url($server_original).$path;

		return $str_path;
	}
} 
if ( ! function_exists('thumb_image_profil')) {
	function thumb_image_profil($path = "",$size = ""){
		$server_thumbs   = env('MEDIA_THUMBS');
		$server_original = env('MEDIA');
		$str_path = "";
		$exp_img = explode(".", $path);			
		if(isset($size) && $size != "") {
			if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
				$str_path = Storage::disk('s3')->url($server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1]);
			}else{
				if (Storage::disk('s3')->exists($server_original.$path)) {
					$str_path = Storage::disk('s3')->url($server_original.$path);
				}else{
					$str_path = Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')).'images/user.png';	
				}
			}
		}else{
			$str_path = Storage::disk('s3')->url($server_original.$path);
		}

		return $str_path;
	}
}

if (! function_exists('generate_thumbnail')) {
    function generate_thumbnail($value)
    {	
		;
		 if (isset($value['imagescover']['path']) && $value['imagescover']['path'] !="") { // cek path didatabase 
			if(isset($value['imagescover']['is_old']) && $value['imagescover']['is_old'] == '1') { // is_old : image lama yg tdk ada di thumbnail 
				
					if (isset($value['id_category']) && $value['id_category'] == config('constants.category-id-opini')) { // jika kategori opini, crop 1 : 1 
						return  thumb_onthefly_Article($value['imagescover']['path'], 300, 300, 'crop');
					} else { 
						return thumb_onthefly_Article($value['imagescover']['path'], 300, 200, 'auto');
					} 
			
			} else { // bukan image lama 
				if (isset($value['imagescover']['tipe']) && $value['imagescover']['tipe'] == 3) { // image tipe 3 (cover infografik) 
					if (isset($value['image']['path']) && $value['image']['path'] !="") { // pakai image body 
						return thumb_image($value['image']['path'],"300x200");
					} elseif($value['tipe'] == 6 || $value['tipe'] == 8) { // tipe 6 (artikel tableu) 
							return  thumb_image_cover($value['imagescover']['path'],"300x200");
					} else { 

						return  thumb_image("") ;
					} 
				} else { // image biasa 
					if (isset($value['id_category']) && $value['id_category'] == config('constants.category-id-opini')) { // jika kategori opini, 1 : 1 
							if ($value['tipe'] == 4) {
								return  thumb_image($value['image']['path'],"620x413"); 
							}else{
									if(isset($value['og_image']['path']) && $value['og_image']['path'] != ''){ 
										return  thumb_image($value['og_image']['path'],"620x413");
								}else{ 
										if(isset($value['image']['path']) && $value['image']['path'] != ''){ 
											return  thumb_image_cover($value['image']['path'],"620x413");
										}else{ 
											return  thumb_image($value['imagescover']['path'],"300x200");
										} 
								} 
							} 
					} else { // image biasa 
						return  thumb_image($value['imagescover']['path'],"300x200") ;
					} 
				} 
			} 
			if(isset($value['tipe']) && $value['tipe'] == '3'){ // tipe 3 (artikel video) 
				
			} 
		} else { // jika tidak ada path (no image) 
			return thumb_image("") ;
		} 
	}
}
if ( ! function_exists('thumb_onthefly_wideimage')) {
	function thumb_onthefly_wideimage($path){
		$server_thumbs   = env('MEDIA_THUMBS');
		if ($path != "") {
			$str_path = "";		
			$exp_img = explode(".", $path);
				if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."_620x413_thumb.".$exp_img[1])) {
					if(Storage::disk('s3')->exists($server_thumbs.$exp_img[0]."__620x413__share.".$exp_img[1])){
						return Storage::disk('s3')->url($server_thumbs.$exp_img[0]."__620x413__share.".$exp_img[1]);
					}else{
						$name_array = explode("/", $exp_img[0]);
						$s3_file = Storage::disk('s3')->get($server_thumbs.$exp_img[0]."_620x413_thumb.".$exp_img[1]);
						$s3 = Storage::disk('public');
						$s3->put("./".$name_array[3]."_620x413_thumb.".$exp_img[1], $s3_file);
						if(Storage::disk('local')->exists(env('PUBLIC_STORAGE').$name_array[3]."_620x413_thumb.".$exp_img[1])){	
							$height = 600 * 21 / 40;
							$image = WideImage::load(Storage::disk('local')->path(env('PUBLIC_STORAGE').$name_array[3]."_620x413_thumb.".$exp_img[1]));
							$cropped = $image->crop(0, 0, 600, $height)->resize(600, 315);
					        $cropped->saveToFile(Storage::disk('local')->path(env('PUBLIC_STORAGE').$name_array[3]."__620x413__share.".$exp_img[1]), 100);
							Storage::disk('s3')->put($server_thumbs.$exp_img[0]."__620x413__share.".$exp_img[1], fopen(Storage::disk('local')->path(env('PUBLIC_STORAGE').$name_array[3]."__620x413__share.".$exp_img[1]),'r+'),'public');
		                	return Storage::disk('s3')->url($server_thumbs.$exp_img[0]."__620x413__share.".$exp_img[1]);
						}
					}
				}
			

		}else{
			return Storage::disk('s3')->url(env('FRONTEND_TEMPLATE_V1_IMAGES')).'images/'."no_image_news.jpg";	
		}
	}
}