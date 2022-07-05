<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHome extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_home';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'product_code', 'description', 'short_description', 'category_id', 'manufacturer_id', 'order',
        'image', 'image_url', 'price', 'amount', 'color', 'created_at', 'updated_at', 'is_deleted', 'is_home', 'status', 'link_static'];

//    protected $hidden = ['deleted_at', 'is_deleted'];

    public static function getProductHomeList($id) {

        $objects = self::select('id', 'product_code', 'name', 'image', 'image_url')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->where('id', '!=', $id)
            ->orderBy('order', 'asc')
            ->limit(12);

        return $objects->get()->toArray();
    }

    public static function getProductHome() {

        $objects = self::select('id', 'product_code', 'short_description', 'image', 'image_url', 'link_static', 'color')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('order', 'asc');

        return $objects->get()->toArray();
    }

    public static function getListAll($filter){

        $sql = self::select('product_home.*', 'manufacturers.name as manufacturer_name', 'product_categories.name as category_name')
                        ->leftJoin('manufacturers', 'manufacturers.id', '=', 'product_home.manufacturer_id')
                        ->leftJoin('product_categories', 'product_categories.id', '=', 'product_home.category_id');

        $sql->where('product_home.is_deleted', 0);

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('product_home.name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('manufacturers.name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('product_categories.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if(!empty($filter['category_id'])){
            $sql->where(['product_home.category_id' => $filter['category_id']]);
        }

        if(!empty($filter['manufacturer_id'])){
            $sql->where(['product_home.manufacturer_id' => $filter['manufacturer_id']]);
        }

        if (isset($filter['status'])) {
            $sql->where('product_home.status', $filter['status']);
        }

        $sql->orderBy($filter['sort']??'product_home.order', $filter['order']??'asc');

        // return $sql->paginate($filter['limit']??12)->toArray();
        if (!isset($filter['limit'])) $filter['limit'] = 12;
        $filter['page'] = $filter['offset'] / $filter['limit'] + 1;
        
        return $sql->paginate($filter['limit'], ['*'], 'page', $filter['page'])->toArray();
    }

    public static function getParentOptions(){

        $data = ProductCategory::select('id','name')->pluck('name','id');

        if(!empty($data))
        {
            return $data->toArray();
        }

        return  array();
    }

    public static function getProductHomesByCate($category_id, $limit){
        $data = ProductHome::select('product_home.id','product_home.name', 'product_home.image', 'product_categories.name as category_name')
                            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_home.category_id')
                            ->where('product_home.category_id', $category_id)
                            ->where('product_home.is_deleted', 0)
                            ->orderBy('product_home.order', 'asc');

        return $data->paginate($limit)->toArray();
    }

    public static function getProductHomeById($id){

        $data = [];

        $product = ProductHome::select('product_home.*', 'manufacturers.name as manufacturer_name', 'product_categories.name as category_name')
                            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'product_home.manufacturer_id')
                            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_home.category_id')
                            ->where('product_home.is_deleted', 0)
                            ->where('product_home.status', 1)
                            ->where('product_home.id', $id)->first();

        return $product ? $product->toArray() : [];
    }

    public static function getProductsSameCode($code, $except_ids=[]) {
        $query = ProductHome::select('*')
            ->where('code', $code)
            ->where('is_deleted', 0);

        if (is_array($except_ids)) {
            $query->whereNotIn('id', $except_ids);
        } else {
            $query->where('id', '!=', $except_ids);
        }

        $query->orderBy('product_home.order', 'asc');

        return $query->get()->toArray();
    }

    public static function getProductHomesSameCategory($category_id, $except_ids=[]) {
        $query = ProductHome::select('*')
            ->where('category_id', $category_id)
            ->where('is_deleted', 0);

        if ($except_ids) {
            if (is_array($except_ids)) {
                $query->whereNotIn('id', $except_ids);
            } else {
                $query->where('id', '!=', $except_ids);
            }
        }

        $query->orderBy('product_home.order', 'asc');

        return $query->get()->toArray();
    }

    public static function getAllProducts(){

        $data = [];

        $products = ProductHome::select('product_home.*', 'product_categories.name as category_name')
                            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_home.category_id')
                            ->where('product_home.amount', '>', 0)
                            ->orderBy('product_home.order', 'asc')
                            ->get();

        if(count($products))
        {
            foreach ($products as $item)
            {
                $data[] = $item->toArray();
            }
        }
        return $data;
    }

    public static function getSearchProductHomes($params) {

        $objects = ProductHome::select('product_home.*', 'product_categories.name as category_name')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_home.category_id')
            ->where('product_home.is_deleted', 0);

        if (isset($params['search'])) {
            $keyword = $params['search'];
            $keyword_new = str_replace(' ', '%', $params['search']);
            $objects->where(function ($query) use ($keyword, $keyword_new) {
                $query->where('product_home.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere(\DB::Raw("REPLACE(REPLACE(product_home.name, '-', ''), ' ', '')"), 'LIKE', '%' . $keyword . '%');
                $query->orWhere('product_home.name', 'LIKE', '%' . $keyword_new . '%');
                $query->orWhere('product_categories.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('product_categories.name', 'LIKE', '%' . $keyword_new . '%');
                $query->orWhere('product_home.description', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('product_home.description', 'LIKE', '%' . $keyword_new . '%');
                $query->orWhere(function ($query_products) use ($keyword, $keyword_new) {
                    $query_products->whereExists(function ($query_products_exists) use ($keyword, $keyword_new) {
                        $query_products_exists->select(\DB::raw(1))
                            ->from('products')
                            ->whereRaw('products.code = product_home.product_code')
                            ->where(function ($qpe) use ($keyword, $keyword_new) {
                                $qpe->where('products.name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere(\DB::Raw("REPLACE(REPLACE(products.name, '-', ''), ' ', '')"), 'LIKE', '%' . $keyword . '%')
                                ->orWhere('products.name', 'LIKE', '%' . $keyword_new . '%');
                            });
                    });
                });
            });
        }
//var_dump($objects->toSql()); die;
        $objects->orderBy($params['sort']??'product_home.order', $params['order']??'asc');

        return $objects->paginate($params['limit']??12)->toArray();
    }

    public static function getStatusFilter() {
        return array(
            '1' => 'Đang hoạt động',
            '0' => 'Không hoạt động',
        );
    }

    public static function getCategoryFilter() {
        $data = ProductCategory::select('id','name')->pluck('name','id');

        if(!empty($data))
        {
            return $data->toArray();
        }

        return  array();
    }

    public static function getManufacturerFilter() {
        $data = Manufacturer::select('id','name')->pluck('name','id');

        if(!empty($data))
        {
            return $data->toArray();
        }

        return  array();
    }
}
?>