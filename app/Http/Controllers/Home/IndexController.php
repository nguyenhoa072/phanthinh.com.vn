<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
use App\Models\Article;
use App\Models\ProductHome;
use App\Models\ArticleImage;
use App\Models\ProductHomeImage;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\SlideShow;
use NunoMaduro\Collision\Provider;

class IndexController extends HomeController
{
	public function __construct()
	{
		parent::__construct();
	}

    public function index(Request $request)
    {
        $limit = $request->input('limit', 12);
        $this->data['limit'] = $limit;
        $this->data['product_home'] = ProductHome::getProductHome();
        

        return view('home.index', $this->data);
    }

    public function about(Request $request)
    {
        return view('home.about', $this->data);
    }

    public function contact(Request $request)
    {
        return view('home.contact', $this->data);
    }

    public function category_manufacturer(Request $request, $slug, $id)
    {
        $limit = $request->input('limit', 12);
        $this->data['limit'] = $limit;

//        $this->data['slides'] = SlideShow::getSlideShows();
        $this->data['manufacturerCategories'] = ProductCategory::getCategoryBymanufacturer($id);
        $this->data['manufacturer'] = Manufacturer::getManufacturerById($id);
        return view('home.category-manufacturer', $this->data);
    }

    public function products(Request $request)
    {
        $limit = $request->input('limit', 12);
        $this->data['limit'] = $limit;

        $rank_articles = Article::select('articles.*', \DB::Raw('@m_rank := IF(@current_m = manufacturer_id, @m_rank + 1, 1) AS m_rank'),
            \DB::Raw('@current_m := manufacturer_id'))
            ->whereRaw('is_deleted=0')
            ->orderBy('manufacturer_id', 'desc')
            ->orderBy('order', 'asc');

        $articles = \DB::table( \DB::raw("({$rank_articles->toSql()}) as sub") )->select('*')
            ->where('m_rank', '>', 0)->get();
        $rs = [];
        foreach ($articles as $item) {
            $rs[$item->manufacturer_id][] = $item;
        }
        $this->data['products'] = $rs;

        return view('home.products', $this->data);
    }

    public function product_category(Request $request, $slug, $id)
    {
        $limit = $request->input('limit', 12);
        $this->data['limit'] = $limit;

        $this->data['slides'] = SlideShow::getSlideShows();
        $this->data['objects'] = Article::getArticlesByCate($id, $limit);

        $category = ProductCategory::getCategoryById($id);
        if (!$category) {
            abort(404);
        }
        
        $this->data['category'] = $category;

        $this->data['manufacturer'] = Manufacturer::getManufacturerById($category['manufacturer_id']);
        
        return view('home.product-category', $this->data);
    }

    public function product_detail($slug, $id)
    {
        $detail_data = Product::getProductById($id);
        $this->data['product'] = $detail_data['product'];
        $this->data['images'] = $detail_data['images'];

        if ($detail_data['product']) {
            $products_same_code = Product::getProductsSameCode($detail_data['product']['code'], $detail_data['product']['id']);
            $this->data['products_same_code'] = $products_same_code;

            $product_ids = array_column($products_same_code,'id');
            $product_ids[] = $detail_data['product']['id'];

            $this->data['diffProducts'] = Product::getProductsSameCategory($detail_data['product']['category_id'], $product_ids);
        } else {
            $this->data['diffProducts'] = [];
        }

        return view('home.product-detail', $this->data);
    }

    public function article_detail($slug, $id)
    {
        $object = Article::getArticleById($id);
        if (!$object) {
            abort(404);
        }
        $this->data['object'] = $object;

        $images = ArticleImage::select(\DB::Raw('CONCAT(image_url, image) as image'))
            ->where('article_id', $id)->pluck('image')->toArray();
//        $images[] = $object['image_url'] . $object['image'];
        $this->data['images'] = $images;

        $products_same_code = Product::getProductsSameCode($object['product_code']);
        $this->data['products_same_code'] = $products_same_code;

        $this->data['objects_same_category'] = Article::getArticlesSameCategory($object['category_id'], $id);

        return view('home.article-detail', $this->data);
    }

    public function product_home_detail($slug, $id)
    {
        $object = ProductHome::getProductHomeById($id);
        if (!$object) {
            abort(404);
        }
        $this->data['object'] = $object;

        $images = ProductHomeImage::select(\DB::Raw('CONCAT(image_url, image) as image'))
            ->where('article_id', $id)->pluck('image')->toArray();
//        $images[] = $object['image_url'] . $object['image'];
        $this->data['images'] = $images;

        // $products_same_code = Product::getProductsSameCode($object['product_code']);
        // $this->data['products_same_code'] = $products_same_code;

        // $this->data['objects_same_category'] = Article::getArticlesSameCategory($object['category_id'], $id);

        $this->data['product_home_list'] = ProductHome::getProductHomeList($id);

        return view('home.product-home-detail', $this->data);

        
    }


    public function warehouse(Request $request)
    {
        return view('home.warehouse', $this->data);
    }

    public function ajax_warehouse(Request $request)
    {
        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        if (!$request->has('page')) {
            $offset = $request->input('offset', 0);
            $page = round($offset / $limit) + 1;
            $request->request->add(['page' => $page]);
        }

        $params = $request->all();
        $params['limit'] = $limit;

        $objects = Warehouse::getListAll($params);

        $objects['next_page'] = !$objects['last_page'] || $objects['current_page'] == $objects['last_page'] ? -1 : $objects['current_page']+1;
        $objects["params"] = $params;
        $objects['rows'] = $objects['data'];
        unset($objects['data']);
        return response()->json($objects);
    }

    public function search(Request $request)
    {
        $limit = $request->input('limit', 12);
        $this->data['limit'] = $limit;

        $tukhoa = $request->input('kw');

        $products = Article::getSearchArticles(['search' => $tukhoa, 'limit' => $limit]);

        $this->data['objects'] = $products;
        $this->data['tukhoa'] = $tukhoa;
        return view('home.search', $this->data);
    }
}
