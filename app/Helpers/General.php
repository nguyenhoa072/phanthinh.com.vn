<?php
namespace App\Helpers;

use App\Models\Article;
use App\Models\Partner;
use App\Models\ProductCategory;
use App\Models\Manufacturer;
use App\Models\Support;
use Illuminate\Support\Facades\Cache;

class General
{
    public static function get_link_menu($item, $locale='') {
        if ($item['type']=='page_link') {
            $tmp = $locale.'/'.$item['page_slug'];
        } elseif ($item['type']=='internal_link') {
            $tmp = $locale.$item['link'];
        } else {
            $tmp = $item['link'];
        }

        return url($tmp ? $tmp : '/');
    }

    public static function get_limit_options() {
        return [
            '10' => '10 dòng/Trang',
            '20' => '20 dòng/Trang',
            '30' => '30 dòng/Trang',
            '40' => '40 dòng/Trang',
            '50' => '50 dòng/Trang',
        ];
    }

    public static function get_menu_items($re_cache=false) {
        $key = 'MenuItems:All';

        $objects = Cache::get( $key );

        if ($re_cache || !$objects) {
            $objects = \App\Models\MenuItem::select('menu_items.*', 'p.slug as page_slug')
                ->leftjoin('pages as p','p.id', '=', 'menu_items.page_id')
                ->orderBy('lft', 'asc')->get()->toArray();

            Cache::forever($key, $objects);
        }

        return $objects;
    }

    public static function get_settings($name=null, $re_cache=false) {
        $key = 'Settings:All';

        $objects = Cache::get( $key );

        if ($re_cache || !$objects) {
            $objects = \App\Models\Setting::getAllSettings();

            Cache::forever($key, $objects);
        }

        if ($name) {
            return @$objects[$name];
        }

        return $objects;
    }

    public static function getCurrencies(){
        return  array(
            'VNĐ' => 'VNĐ',
            'USD' => 'USD',
        );
    }

    public static function format_show_date($date, $format='d-m-Y') {
        if (!$date || $date=='0000-00-00') return '';

        return date($format, strtotime($date));
    }

    static function get_controller_action() {
        $action = app('request')->route()->getAction();

        $route = isset($action['as']) ? $action['as'] : '';

        $controller = class_basename($action['controller']);

        list($controller, $action) = explode('@', $controller);

        if (!$route) {
            $route = strtolower( str_replace('Controller', '', $controller) ) .'.'. $action;
        }

        return array(
            'controller' => $controller,
            'action' => $action,
            'as' => $route
        );
    }

    static function get_data_fillable($model, $data) {
        $fillable = $model->getFillable();

        $rs = [];

        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $rs[$field] = $data[$field];
            }
        }

        return $rs;
    }

    public static function saveDocumentFile($filename, $path)
    {
        if (empty($filename)) return '';

        $root = rtrim(public_path(), '/') . '/';

        // file goc
        $path_file = realpath( $root ."uploads/tmp/". $filename );

        if( file_exists ($path_file) ) {

            // tao thu muc
            if (! is_dir ( $root . $path )) {
                mkdir ( $root . $path, 0777, true );
                if( chmod($root . $path, 0777) ) {
                    // more code
                    chmod($root . $path, 0755);
                }
            }

            // file dich
            $info = pathinfo($path_file);
            $filename = $path .time().'-'. str_slug(basename($path_file,'.'.$info['extension']), '-' ).'.'.$info['extension'];

            rename($path_file, $root . $filename );

            // xoa hinh tmp
            @unlink($path_file);
//            $filename = url($filename);

            return array('url' => url('/'), 'filename' => $filename);
        }

        return '';
    }

    public static function get_version_js_data($re_cache=false) {
        $key = 'get_version_js_data';

        $value = Cache::get( $key );

        if ($re_cache || !$value) {
            $value = time();

            Cache::forever($key, $value);
        }

        return $value;
    }

    public static function get_version_js($re_cache=true) {
        $key = 'get_version_js';

        $value = Cache::get( $key );

        if ($re_cache || !$value) {
            $value = time();

            Cache::forever($key, $value);
        }

        return $value;
    }
    public static function get_version_css($re_cache=true) {
        $key = 'get_version_css';

        $value = Cache::get( $key );

        if ($re_cache || !$value) {
            $value = time();

            Cache::forever($key, $value);
        }

        return $value;
    }

    public static function get_partners($re_cache=false)
    {
        $key = 'Partner:All';

        $objects = Cache::get( $key );

        if ($re_cache || !$objects) {
            $objects = Partner::where('is_deleted', 0)
                ->orderBy('position', 'asc')
                ->orderBy('updated_at', 'desc')
                ->get()->toArray();

            Cache::forever($key, $objects);
        }

        return $objects;
    }

    public static function get_supports($re_cache=false)
    {
        $key = 'Support:All';

        $objects = Cache::get( $key );

        if ($re_cache || !$objects) {
            $objects = Support::where('is_deleted', 0)
                ->orderBy('updated_at', 'desc')
                ->get()->toArray();

            Cache::forever($key, $objects);
        }

        return $objects;
    }

    public static function getOrderOptions(){
       $orderArr = [];
       for($i = 1; $i<=2000; $i++)
       {
           $orderArr[$i] = $i;
       }
       return  $orderArr;
    }

    public static function getManufacturer()
    {
        $objects = Manufacturer::where('is_deleted', 0)->pluck('name', 'id');

        return $objects ? $objects->toArray() : [];
    }

    public static function getProductCategories()
    {
        $objects = ProductCategory::where('is_deleted', 0)
            ->orderBy('order', 'asc')->get()->toArray();

        $rs = [];

        foreach ($objects as $item) {
            $rs[$item['manufacturer_id']][] = $item;
        }

        return $rs;
    }
    public static function getMenuItems()
    {
        $objects = Manufacturer::where('is_deleted',0)->get();
        $data = array();
        foreach ($objects as $object)
        {
            $object = $object->toArray();

            $sub_items = self::checkCategory($object['id']);

            if($sub_items['rs'] == true)
            {
                $object['is_has_sub'] = 1;
            }
            else
            {
                $object['is_has_sub'] = 0;
            }

            $object['sub_menu_items'] = $sub_items['data'];

            $data[] = $object;
        }

        return $data;
    }
    public static function getArticles()
    {
        $objects = Article::where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('order', 'asc')->get()->toArray();

        $rs = [];

        foreach ($objects as $item) {
            $rs[$item['category_id']][] = $item;
        }

        return $rs;
    }

    public static function checkCategory($manufacturer_id)
    {
        $objects = ProductCategory::where('is_deleted',0)->where('manufacturer_id', $manufacturer_id)->get();
        $data = [];
        if(count($objects))
        {
            $data['rs'] = true;
            foreach ($objects as $item)
            {
                $data['data'][] = $item->toArray();
            }
        }
        else
        {
            $data['rs'] = false;
            $data['data'] = [];
        }
        return $data;
    }

    public static function getProductSubMenuItems()
    {
        $objects = ProductCategory::where('is_deleted',0)->get();
        $data = array();
        foreach ($objects as $object)
        {
            $data[] = $object->toArray();
        }
        return $data;
    }

    public static function toSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}