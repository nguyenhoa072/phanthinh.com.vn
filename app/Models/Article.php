<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'product_code', 'description', 'short_description', 'category_id', 'manufacturer_id', 'order',
        'image', 'image_url', 'price', 'amount', 'created_at', 'updated_at', 'is_deleted', 'status'];

//    protected $hidden = ['deleted_at', 'is_deleted'];

    public static function getHomeArticlesNew() {

        $objects = self::select('id', 'name', 'image', 'image_url')
            ->where('is_deleted', 0)
            ->orderBy('order', 'asc')
            ->limit(12);

        return $objects->get()->toArray();
    }

    // public static function getHomeArticlesList() {

    //     $objects = self::select('id', 'name', 'short_description', 'image', 'image_url')
    //         ->where('is_deleted', 0)
    //         ->where('is_home', 1)
    //         ->orderBy('order', 'asc');

    //     return $objects->get()->toArray();
    // }

    public static function getListAll($filter){

        $sql = self::select('articles.*', 'manufacturers.name as manufacturer_name', 'product_categories.name as category_name')
                        ->leftJoin('manufacturers', 'manufacturers.id', '=', 'articles.manufacturer_id')
                        ->leftJoin('product_categories', 'product_categories.id', '=', 'articles.category_id');

        $sql->where('articles.is_deleted', 0);

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('articles.name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('manufacturers.name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('product_categories.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if(!empty($filter['category_id'])){
            $sql->where(['articles.category_id' => $filter['category_id']]);
        }

        if(!empty($filter['manufacturer_id'])){
            $sql->where(['articles.manufacturer_id' => $filter['manufacturer_id']]);
        }

        if (isset($filter['status'])) {
            $sql->where('articles.status', $filter['status']);
        }

        $sql->orderBy($filter['sort']??'articles.order', $filter['order']??'asc');
        
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

    public static function getArticlesByCate($category_id, $limit){
        $data = Article::select('articles.id','articles.name', 'articles.image', 'product_categories.name as category_name')
                            ->leftJoin('product_categories', 'product_categories.id', '=', 'articles.category_id')
                            ->where('articles.category_id', $category_id)
                            ->where('articles.is_deleted', 0)
                            ->where('articles.status', 1)
                            ->orderBy('articles.order', 'asc');

        return $data->paginate($limit)->toArray();
    }

    public static function getArticleById($id){

        $data = [];

        $product = Article::select('articles.*', 'manufacturers.name as manufacturer_name', 'product_categories.name as category_name')
                            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'articles.manufacturer_id')
                            ->leftJoin('product_categories', 'product_categories.id', '=', 'articles.category_id')
                            ->where('articles.is_deleted', 0)
                            ->where('articles.status', 1)
                            ->where('articles.id', $id)->first();

        return $product ? $product->toArray() : [];
    }

    public static function getProductsSameCode($code, $except_ids=[]) {
        $query = Article::select('*')
            ->where('code', $code)
            ->where('is_deleted', 0);

        if (is_array($except_ids)) {
            $query->whereNotIn('id', $except_ids);
        } else {
            $query->where('id', '!=', $except_ids);
        }

        $query->orderBy('articles.order', 'asc');

        return $query->get()->toArray();
    }

    public static function getArticlesSameCategory($category_id, $except_ids=[]) {
        $query = Article::select('*')
            ->where('category_id', $category_id)
            ->where('is_deleted', 0);

        if ($except_ids) {
            if (is_array($except_ids)) {
                $query->whereNotIn('id', $except_ids);
            } else {
                $query->where('id', '!=', $except_ids);
            }
        }

        $query->orderBy('articles.order', 'asc');

        return $query->get()->toArray();
    }

    public static function getAllProducts(){

        $data = [];

        $products = Article::select('articles.*', 'product_categories.name as category_name')
                            ->leftJoin('product_categories', 'product_categories.id', '=', 'articles.category_id')
                            ->where('articles.amount', '>', 0)
                            ->orderBy('articles.order', 'asc')
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

    public static function getSearchArticles($params) {

        $objects = Article::select('articles.*', 'product_categories.name as category_name')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'articles.category_id')
            ->where('articles.is_deleted', 0);

        if (isset($params['search'])) {
            $keyword = $params['search'];
            $keyword_new = str_replace(' ', '%', $params['search']);
            $objects->where(function ($query) use ($keyword, $keyword_new) {
                $query->where('articles.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere(\DB::Raw("REPLACE(REPLACE(articles.name, '-', ''), ' ', '')"), 'LIKE', '%' . $keyword . '%');
                $query->orWhere('articles.name', 'LIKE', '%' . $keyword_new . '%');
                $query->orWhere('product_categories.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('product_categories.name', 'LIKE', '%' . $keyword_new . '%');
                $query->orWhere('articles.description', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('articles.description', 'LIKE', '%' . $keyword_new . '%');
                $query->orWhere(function ($query_products) use ($keyword, $keyword_new) {
                    $query_products->whereExists(function ($query_products_exists) use ($keyword, $keyword_new) {
                        $query_products_exists->select(\DB::raw(1))
                            ->from('products')
                            ->whereRaw('products.code = articles.product_code')
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
        $objects->orderBy($params['sort']??'articles.order', $params['order']??'asc');

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