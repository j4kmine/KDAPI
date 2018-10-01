<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\ContentModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class ContentController extends Controller{
    public $success_status = 200;
    public function index(Request $request){
        $input = $request->json();
        $page = 0 ;
        $redis_query = "_CONTENT_";
        $page = $request->query('page');
        $content=ContentModel::select('*')->orderBy('date_publish', 'desc');
        if($input->get('keyword')){
            $content->where("title", "LIKE","%{$input->get('keyword')}%");
        }
        if(isset($page) && $page != 0){
            $content->skip($page * 10);
        }
        if($input->get('id_category')){
            $content->where("id_category", "=","{$input->get('id_category')}");
        }
        if($input->get('id_topics')){
            $content->where("id_topics", "=","{$input->get('id_topics')}");
        }
        if($input->get('id_editor')){
            $content->where("id_editor", "=","{$input->get('id_editor')}");
        }
        if($input->get('id_penulis')){
            $content->where("id_penulis", "=","{$input->get('id_penulis')}");
        }
        if($input->get('id_reporter')){
            $content->where("id_reporter", "=","{$input->get('id_reporter')}");
        }
        if($input->get('arr_exclude')){
            $arr_exclude = json_decode($input->get('arr_exclude'));
            $str_exclude = implode(",", $arr_exclude);
            $content->where("id", "NOT IN", ($str_exclude) );
        }   
        if($input->get('id_subtitle')){
            $content->where("id_subtitle", "=","{$input->get('id_subtitle')}");
        }
        if($input->get('id_translator')){
            $content->where("id_translator", "=","{$input->get('id_translator')}");
        }
        if($input->get('slug')){
            $content->where("slug", "=","{$input->get('slug')}");
        }
        if($input->get('tipe')){
            $content->where("tipe", "=","{$input->get('tipe')}");
        }
        if($input->get('status')){
            $content->where("status", "=","{$input->get('status')}");
        }
        if($input->get('language')){
            $content->where("language", "=","{$input->get('language')}");
        }
        if($input->get('headline')){
            $content->where("headline", "=","{$input->get('headline')}");
        }
        if($input->get('is_breaking_news')){
            $content->where("is_breaking_news", "=","{$input->get('is_breaking_news')}");
        }
        if($input->get('position')){
            $content->where("position", "=","{$input->get('position')}");
        }
        if($input->get('is_old')){
            $content->where("is_old", "=","{$input->get('is_old')}");
        }
        if($input->get('tipe_foto')){
            $content->where("tipe_foto", "=","{$input->get('tipe_foto')}");
        }
        if($input->get('is_adv')){
            $content->where("is_adv", "=","{$input->get('is_adv')}");
        }
        if($input->get('adv_zone')){
            $content->whereRaw('FIND_IN_SET(?,adv_zone)', $input->get('adv_zone'));
        }
        if($input->get('adv_date_start')){
            $content->where("adv_date_start", ">=","{$input->get('adv_date_start')}");
        }
        if($input->get('adv_date_end')){
            $content->where("adv_date_end", "<=","{$input->get('adv_date_end')}");
        }
        if($input->get('date_publish')){
            $content->where("date_publish", ">=","{$input->get('date_publish')}");
        }
        if($input->get('is_microsite')){
            $content->where("is_microsite", "=","{$input->get('is_microsite')}");
        }
        if($input->get('position_breaking_news')){
            $content->where("position_breaking_news", "=","{$input->get('position_breaking_news')}");
        }
        if($input->get('id_naskah')){
            $content->where("id_naskah", "=","{$input->get('id_naskah')}");
        }
        if($input->get('post_socmed')){
            $content->where("post_socmed", "=","{$input->get('post_socmed')}");
        }
        
        if(env("REDIS_ACTIVE") == true){
            $redis_query= "_Content_". str_replace(' ', '',$input->get('keyword'))."_"
            .$input->get('id_category')."_".$input->get('id_topics')."_"
            .$input->get('id_editor')."_".$input->get('id_penulis')."_"
            .$input->get('id_reporter')."_".$input->get('id_subtitle')."_"
            .$input->get('id_translator')."_".$input->get('slug')."_"
            .$input->get('tipe')."_".$input->get('status')."_"
            .$input->get('language')."_".$input->get('headline')."_"
            .$input->get('is_breaking_news')."_".$input->get('position')."_"
            .$input->get('tipe')."_".$input->get('is_old')."_"
            .$input->get('tipe_foto')."_".$input->get('is_adv')."_"
            .$input->get('adv_zone')."_".$input->get('is_adv')."_"
            .$input->get('is_microsite')."_".$input->get('position_breaking_news')."_"
            .$input->get('id_naskah')."_".$input->get('post_socmed')."_"
            .$page;
            $redis = app()->make('redis');
            $datafromRedis = $redis->get($redis_query);
            if($datafromRedis != '' && count($datafromRedis)>0){
                $content = $datafromRedis;
                $temp = json_decode($content);
                return response()->json(['data'=>$temp],$this->success_status);
            }else{
                $content = $content->paginate(10);
                $content = $this->__getCategory($content);
                $content = $this->__getTopics($content);
                $content = $this->__getFoto($content);
                $content = $this->__getImages($content);
                $content = $this->__getImageCover($content);
                $redis->set($redis_query,$content->toJson());
                $redis->expire($redis_query, env("REDIS_TIMEOUT"));
            }
        }else{
            $content = $content->paginate(10);
            $content = $this->__getCategory($content);
            $content = $this->__getTopics($content);
            $content = $this->__getFoto($content);
            $content = $this->__getImages($content);
            $content = $this->__getImageCover($content);
        }
       
        return response()->json(['data'=>$content],$this->success_status);
    }

    private function __getImageCover($content)
    {
        if(isset($content) && count($content)>0){
            foreach ($content as $key => $value) {
                if($value->id_image_cover != ''){
                    $tempdata = '';
                    $tempdata =  DB::select("
                    SELECT 
                    id
                    ,title
                    ,path 
                    ,description
                    ,src
                    ,is_old
                    ,tipe
                    ,path_video
                    FROM images WHERE id  = '".$value->id_image_cover."' 
                 ");
                 if(isset($tempdata[0]) && $tempdata[0] != ''){
                     $content[$key]['imagescover'] = generate_thumbnail(json_decode(json_encode($tempdata[0]), True));
                 }
                     
                }else{
                    $content[$key]['imagescover'] = 0;
                }
            }
     }
     return $content;
    }
    private function __getImages($content)
    {
        if(isset($content) && count($content)>0){
            foreach ($content as $key => $value) {
                if($value->id_image != ''){
                    $tempdata = '';
                    $tempdata =  DB::select("
                    SELECT 
                    id
                    ,title
                    ,path 
                    ,description
                    ,src
                    ,is_old
                    ,tipe
                    ,path_video
                    FROM images WHERE id  = '".$value->id_image."' 
                 ");
                 if(isset($tempdata[0]) && $tempdata[0] != ''){
                     $content[$key]['image'] =  generate_thumbnail(json_decode(json_encode($tempdata[0]), True));
                 }
                     
                }else{
                    $content[$key]['image'] = 0;
                }
            }
     }
     return $content;
    }
    private function __getCategory($content){
        if(isset($content) && count($content)>0){
           	foreach ($content as $key => $value) {
           		if($value->id_category != ''){
           			$tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id,
                       name,
                       slug,
                       date_created,
                       date_modified,
                       creator,
                       modifier,
                       language,
                       tipe,
                       is_top_menu,
                       is_microsite,
                       id_advertiser
                       FROM category WHERE id  = '".$value->id_category."' 
            		");
            		if(isset($tempdata[0]->name) && $tempdata[0]->name != ''){
            			$content[$key]['category'] = $tempdata[0]->name;
            		}
           		 	
           		}else{
           			$content[$key]['category'] = 0;
           		}
           	}
        }
        return $content;
    }
    private function __getTopics($content){
        if(isset($content) && count($content)>0){
           	foreach ($content as $key => $value) {
           		if($value->id_topics != ''){
           			$tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id,
                       name,
                       slug,
                       date_created,
                       date_modified,
                       creator,
                       modifier,
                       language
                       FROM topics WHERE id  = '".$value->id_topics."' 
            		");
            		if(isset($tempdata[0]->name) && $tempdata[0]->name != ''){
            			$content[$key]['topics'] = $tempdata[0]->name;
            		}
           		 	
           		}else{
           			$content[$key]['topics'] = 0;
           		}
           	}
        }
        return $content;
    }
    private function __getDetailFoto($content){
        if(isset($content) && count($content)>0){
            foreach ($content as $key => $value) {
                if($value->id_image != 0){
                    $tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id
                       ,title
                       ,path 
                       ,description
                       ,src
                       ,is_old
                       ,tipe
                       ,path_video
                       FROM images WHERE id  = '".$value->id_image."' 
            		");
            		if(isset($tempdata) && count($tempdata)>0){
                        return $tempdata;
            		}
                }
            }
        }
    }
    private function __getFoto($content){
        if(isset($content) && count($content)>0){
           	foreach ($content as $key => $value) {
           		if($value->id != ''){
           			$tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id,
                       id_content,
                       id_images,
                       caption,
                       position
                      FROM rel_content_images WHERE id_content  = '".$value->id."' 
            		");
            		if(isset($tempdata) && count($tempdata)>0){
                        $content[$key]['foto'] = $this->__getDetailFoto($tempdata);
            		}
           		 	
           		}else{
           			$content[$key]['foto'] = 0;
           		}
           	}
        }
        return $content;
    }
}
    