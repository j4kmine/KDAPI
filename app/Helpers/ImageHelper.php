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
        if($type == 'article'){
			$path_thumbs     = config('constants.media-path-article-thumb');
			$server_thumbs   = config('constants.media-server-article-thumb');
			$path_original   = config('constants.media-path-article');
			$server_original = config('constants.media-server-article');
		}else if($type == 'produk'){
			$path_thumbs     = config('constants.media-path-produk-thumb');
			$server_thumbs   = config('constants.media-server-produk-thumb');
			$path_original   = config('constants.media-path-produk');
			$server_original = config('constants.media-server-produk');
		}else if($type == 'blog'){
			$path_thumbs     = config('constants.media-path-blog-thumb');
			$server_thumbs   = config('constants.media-server-blog-thumb');
			$path_original   = config('constants.media-path-blog');
			$server_original = config('constants.media-server-blog');
		}else if($type == 'img_ori'){
			$path_original   = config('constants.media-path-images-original');
			$server_original = config('constants.media-server-images-original');
		}else if($type == 'img_ori_thumb'){
			$path_original   = config('constants.media-path-images-orithumb');
			$server_original = config('constants.media-server-images-orithumb');
		}else if($type == 'icon_industri'){
			$path_original   = config('constants.media-path-icons-industri');
			$server_original = config('constants.media-server-icons-industri');
		}else if($type == 'advertiser'){
			$path_original   = config('constants.media-path-advertiser');
			$server_original = config('constants.media-server-advertiser');
		}else if($type == 'img_industri'){
			$path_original   = config('constants.media-path-images-industri');
			$server_original = config('constants.media-server-images-industri');
		}else if($type == 'images_negara'){
			$path_original   = config('constants.media-path-images-negara');
			$server_original = config('constants.media-server-images-negara');
		}else if($type == 'images_sismik2d'){
			$path_original   = config('constants.media-path-images-sismik2d');
			$server_original = config('constants.media-server-images-sismik2d');
		}else if($type == 'images_sismik3d'){
			$path_original   = config('constants.media-path-images-sismik3d');
			$server_original = config('constants.media-server-images-sismik3d');
		}else if($type == 'marketing'){
			$path_thumbs     = config('constants.media-path-marketing-images-thumb');
			$server_thumbs   = config('constants.media-server-marketing-images-thumb');
			$path_original   = config('constants.media-path-marketing-images');
			$server_original = config('constants.media-server-marketing-images');
		}else if($type == 'artikelmember'){
			$path_thumbs     = config('constants.media-path-images-artikelmember-thumb');
			$server_thumbs   = config('constants.media-server-images-artikelmember-thumb');
			$path_original   = config('constants.media-path-images-artikelmember');
			$server_original = config('constants.media-server-images-artikelmember');
		}else if($type == 'seismik2d'){
			$path_original   = config('constants.media-path-images-sismik2d');
			$server_original = config('constants.media-server-images-sismik2d');
		}else if($type == 'seismik3d'){
			$path_original   = config('constants.media-path-images-sismik3d');
			$server_original = config('constants.media-server-images-sismik3d');
		}else{
			$path_thumbs     = config('constants.media-path-images-thumb');
			$server_thumbs   = config('constants.media-server-images-thumb');
			$path_original   = config('constants.media-path-images');
			$server_original = config('constants.media-server-images');
		}

      
		if($path != "") {
			$str_path = "";
			$exp_img = explode(".", $path);			
			
			if(isset($size) && $size != "") {
				if($size == "620x413shared1"){
					$size ="620x413";
					$size_true ="620x413shared1";
					if(file_exists($path_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1])) {
						$str_path = $server_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1];
					}else{
						// echo $path_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1];
						if($size_true == "620x413shared1"){
							if(file_exists($path_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
								$str_path_new = $path_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
								$file_final_name_og = $exp_img[0].'_'.$size.'_shared1.'.$exp_img[1];
								$des_path_og =  $path_thumbs . $file_final_name_og;
								$logo = 'logo.png';
								$generate = $CI->image_moo
									->load($str_path_new)
									->resize_crop('600' ,'315')
									->set_watermark_transparency(1)
									->load_watermark(config('constants.media-path-sample').$logo)
									->watermark(3,0)
									->save($des_path_og);
								if(file_exists($des_path_og)){
									$str_path = $server_thumbs.$exp_img[0]."_".$size."_shared1.".$exp_img[1];
								}else{
									$str_path = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
								}
							}
						}else if(file_exists($path_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
							$str_path = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
						} else {
							if ($type == 'profile'){
								$str_path = config('constants.frontend_template_v2').'images/'."no_picture_profile.jpg";
							} else if ($type == 'cover'){
								$str_path = config('constants.frontend_template_v2').'images/'."cover-header".rand(2,4).".jpg";
							} else if ($type == "profil" || $type == "logo" || $type == "icon_industri" || $type == "img_industri" || $type == "opini" || $type == "advertiser") {
								if(file_exists($path_original.$path)) {
									$str_path = $server_original.$path;
								} else {
									if ($type == 'opini') {
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
									}else{
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
									}
								}
							} else if ($type == "images_negara") {
								if(file_exists($path_original.$path)) {
									$str_path = $server_original.$path;
								} else {
									if ($type == 'opini') {
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
									}else{
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
									}
								}
							} else {
								$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
							}
						}
					}
				}else if($size == "500x500shared"){
					$size ="500x500";
					if(file_exists($path_thumbs.$exp_img[0]."_".$size."_shared.".$exp_img[1])) {
						$str_path = $server_thumbs.$exp_img[0]."_".$size."_shared.".$exp_img[1];
					}else{
						if(file_exists($path_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
							$str_path = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
						} else {
							if ($type == 'profile'){
								$str_path = config('constants.frontend_template_v2').'images/'."no_picture_profile.jpg";
							} else if ($type == 'cover'){
								$str_path = config('constants.frontend_template_v2').'images/'."cover-header".rand(2,4).".jpg";
							} else if ($type == "profil" || $type == "logo" || $type == "icon_industri" || $type == "img_industri" || $type == "opini" || $type == "advertiser") {
								if(file_exists($path_original.$path)) {
									$str_path = $server_original.$path;
								} else {
									if ($type == 'opini') {
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
									}else{
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
									}
								}
							} else if ($type == "images_negara") {
								if(file_exists($path_original.$path)) {
									$str_path = $server_original.$path;
								} else {
									if ($type == 'opini') {
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
									}else{
										$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
									}
								}
							} else {
								$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
							}
						}
					}
				}else{
					if(file_exists($path_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
						
						if (env('USE_GOOGLECLOUD_THUMBNAIL')== true && $type == "") {
							if (env('USE_WEBP') == true) {
								if (file_exists(config('constants.media-path-images-thumb-webp').$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
									$str_path = config('constants.url_production_googlestorage_thumb_webp').$exp_img[0]."_".$size."_thumb.".$exp_img[1];
								}else{
									$str_path = config('constants.url_production_googlestorage_thumb').$exp_img[0]."_".$size."_thumb.".$exp_img[1];		
								}
							}else{
								$str_path = config('constants.url_production_googlestorage_thumb').$exp_img[0]."_".$size."_thumb.".$exp_img[1];	
							}
						}else{
							if (env('USE_WEBP') == true) {
								if (file_exists(config('constant.media-path-images-thumb-webp').$exp_img[0]."_".$size."_thumb.".$exp_img[1])) {
									$server_thumbs = config('constants.media-server-images-thumb-webp');
									$str_path = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
								}else{
									$str_path = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
								}
							}else{
								$str_path = $server_thumbs.$exp_img[0]."_".$size."_thumb.".$exp_img[1];
							}
						}

					} else {
						if ($type == 'profile'){
							$str_path = config('constants.frontend_template_v2').'images/'."no_picture_profile.jpg";
						} else if ($type == 'cover'){
							$str_path = config('constants.frontend_template_v2').'images/'."cover-header".rand(2,4).".jpg";
						} else if ($type == "profil" || $type == "logo" || $type == "icon_industri" || $type == "img_industri" || $type == "opini" || $type == "advertiser") {
							if(file_exists($path_original.$path)) {
								$str_path = $server_original.$path;
							} else {
								if ($type == 'opini') {
									$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
								}else{
									$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
								}
							}
						} else if ($type == "images_negara") {
							if(file_exists($path_original.$path)) {
								$str_path = $server_original.$path;
							} else {
								if ($type == 'opini') {
									$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
								}else{
									$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
								}
							}
						} else {
							$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
						}
					}
				}
				
			} else {
				if ($type != "kuis") {
					if(file_exists($path_original.$path)) {
						if (env('USE_GOOGLECLOUD_THUMBNAIL')== true && $type == 'img_ori_thumb'){
							$str_path = config('constants.url_production_googlestorage_thumb_ori').$path;	
						}else{
							$str_path = $server_original.$path;	
						}
					} else {
						if($type == 'profile'){
							$str_path = config('constants.frontend_template_v2').'images/'."no_picture_profile.jpg";
						}else if($type == 'cover'){
							$str_path = config('constants.frontend_template_v2').'images/'."cover-header".rand(2,4).".jpg";
						}else if($type == 'opini'){
							$str_path = config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
						}else if($type == 'img_ori_thumb'){
							$path_original   = config('constants.media-path-images-original');
							$server_original = config('constants.media-server-images-original');
							if(file_exists($path_original.$path)) {
								$str_path = $server_original.$path;
							} else {
								$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
							}
						}else{
							$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
						}
					}
				} else {
					if(file_exists($path_thumbs.$path)) {

						if (isset($type) && $type == "kuis" && file_exists($path_original.$path)) {
							$str_path = $server_original.$path;
						}else{
							$str_path = $server_thumbs.$path;
						}
						
					} else {
						$str_path = config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
					}
				}
			}
			return $str_path;
		} else {
			if($type == 'profile'){
				return config('constants.frontend_template_v2').'images/'."no_picture_profile.jpg";
			}else if($type == 'cover'){
				return config('constants.frontend_template_v2').'images/'."cover-header".rand(2,4).".jpg";
			}else if($type == 'opini'){
				return config('constants.frontend_template_v2').'images/'."no_image_square.jpg";
			}else{
				return config('constants.frontend_template_v2').'images/'."no_image_news.jpg";
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
if (! function_exists('generate_thumbnail')) {
    function generate_thumbnail($value)
    {
		 if (isset($value['imagecover']['path']) && $value['imagecover']['path'] !="") { // cek path didatabase 
			if(isset($value['imagecover']['is_old']) && $value['imagecover']['is_old'] == '1') { // is_old : image lama yg tdk ada di thumbnail 
				if(file_exists(config('constants.media-path-oldmedia').$value['imagecover']['path'])) { // cek file image, (ada) buat thumbnail dgn onthefly 
					if (isset($value['id_category']) && $value['id_category'] == config('constants.category-id-opini')) { // jika kategori opini, crop 1 : 1 
						return  thumb_onthefly_Article($value['imagecover']['path'], 300, 300, 'crop');
					} else { 
						return thumb_onthefly_Article($value['imagecover']['path'], 300, 200, 'auto');
					} 
				} else { 
					return thumb_image("");
				}
			} else { // bukan image lama 
				if (isset($value['imagecover']['tipe']) && $value['imagecover']['tipe'] == 3) { // image tipe 3 (cover infografik) 
					if (isset($value['image']['path']) && $value['image']['path'] !="") { // pakai image body 
						return thumb_image($value['image']['path'],"300x200");
					} elseif($value['tipe'] == 6 || $value['tipe'] == 8) { // tipe 6 (artikel tableu) 
							return  thumb_image_cover($value['imagecover']['path'],"300x200");
					} else { 
						return  thumb_image("") ;
					} 
				} else { // image biasa 
					if (isset($value['id_category']) && $value['id_category'] == $this->webconfig['category-id-opini']) { // jika kategori opini, 1 : 1 
							if ($value['tipe'] == 4) {
								return  thumb_image($value['image']['path'],"620x413"); 
							}else{
									if(isset($value['og_image']['path']) && $value['og_image']['path'] != ''){ 
										return  thumb_image($value['og_image']['path'],"620x413");
								}else{ 
										if(isset($value['image']['path']) && $value['image']['path'] != ''){ 
											return  thumb_image_cover($value['image']['path'],"620x413");
										}else{ 
											return  thumb_image($value['imagecover']['path'],"300x200");
										} 
								} 
							} 
					} else { // image biasa 
						return  thumb_image($value['imagecover']['path'],"300x200") ;
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
