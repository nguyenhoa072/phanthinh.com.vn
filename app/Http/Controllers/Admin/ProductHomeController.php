<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductHome;
// use App\Models\ProductCategory;
use App\Models\ProductHomeImage;
use Illuminate\Http\Request;
use App\Http\Requests\ProductHomeRequest;
use App\Helpers\General;
use Log;
use Session;

class ProductHomeController extends Controller
{
    private $_data = array();
    private $_model;

    /* *
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_data['title'] = 'Sản phẩm trang chủ';
        $this->_data['controllerName'] = 'product-home';
        $this->_model = new ProductHome();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->_data['status'] = ['' => ''] + $this->_model->getStatusFilter();
        $this->_data['categories'] = ['' => ''] + $this->_model->getCategoryFilter();
        $this->_data['manufacturers'] = ['' => ''] + $this->_model->getManufacturerFilter();

        return view("admin.{$this->_data['controllerName']}.index", $this->_data);
    }

    public function search(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', 10),
            'sort' => $request->input('sort', 'product_categories.id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', 1),
            'category_id' => $request->input('category_id', ''),
            'manufacturer_id' => $request->input('manufacturer_id', ''),
        ];

        $data = $this->_model->getListAll($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->_data['menu_items'] = General::getMenuItems();
        $this->_data['orderOptions'] = General::getOrderOptions();
        return  view("admin.{$this->_data['controllerName']}.create", $this->_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductHomeRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        // $categoryModel = new ProductCategory();
        // $category = $categoryModel->find($data['category_id'])->toArray();
        // $data['manufacturer_id'] = $category['manufacturer_id'];

        if (empty($data['image_url'])) {
            $data['image_url'] = config('app.url');
        }

        $object = $this->_model->create($data);

        if ($object)
        {
            if ($object && isset($data['product_images'])) {
                $this->store_product_images($object->id, $data['product_images']);
            }

            if ($request->ajax() || $request->wantsJson()) {

                $request->session()->flash('error', 0);
                $request->session()->flash('message', 'Thêm mới '.$this->_data['title'].' thành công');

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Thêm mới '.$this->_data['title'].' thành công',
                    'act' => 'add',
                    'link_edit' => route('product-home.edit', ['id' => $object->id])
                ]);
            }

            return redirect()->route("{$this->_data['controllerName']}.index");
        }

        if ($request->ajax() || $request->wantsJson()) {

            $request->session()->flash('error', 1);
            $request->session()->flash('message', 'Thêm mới ' . $this->_data['title'] . ' không thành công');

            return response()->json([
                'rs' => 0,
                'msg' => 'Thêm mới ' . $this->_data['title'] . ' không thành công',
                'act' => 'add'
            ]);
        }

        return redirect("/admin/{$this->_data['controllerName']}/add");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $object = $this->_model->find($id)->toArray();
        $this->_data['id'] = $id;
        $this->_data['object'] = $object;
        $this->_data['menu_items'] = General::getMenuItems();
        $this->_data['orderOptions'] = General::getOrderOptions();
        $this->_data['product_images'] = ProductHomeImage::select(\DB::Raw('CONCAT(image_url, image) as image'), 'id')->where('article_id', $id)->pluck('image', 'id');
        return view("admin.{$this->_data['controllerName']}.create", $this->_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $object  = $this->_model->find($id);

        if (!$object)
        {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Lỗi không tồn tại',
                    'act' => 'edit'
                ]);
            }

            return redirect()->route("{$this->_data['controllerName']}.index");
        }

        $data = $request->all();

        if (empty($data['image_url'])) {
            $data['image_url'] = config('app.url');
        }

        unset($data['_token']);

        // $categoryModel = new ProductCategory();
        // $category = $categoryModel->find($data['category_id'])->toArray();
        // $data['manufacturer_id'] = $category['manufacturer_id'];

        $rs = $object->update($data);

        if ($rs && isset($data['product_images'])) {
            $this->store_product_images($id, $data['product_images']);
        }

        if ($request->ajax() || $request->wantsJson()) {

            $request->session()->flash('error', 0);
            $request->session()->flash('message', 'Chỉnh sửa '.$this->_data['title'].' thành công');

            return response()->json([
                'rs' => 1,
                'msg' => 'Chỉnh sửa '.$this->_data['title'].' thành công',
                'act' => 'edit',
                'link_edit' => route('product-home.edit', ['id' => $object->id])
            ]);
        }

        return redirect()->route("{$this->_data['controllerName']}.index");
    }

    public function store_product_images($article_id, $product_images) {
        if (isset($product_images['delete'])) {
            ProductHomeImage::where('article_id', $article_id)
                ->whereIn('id', $product_images['delete'])->delete();
            unset($product_images['delete']);
        }
        foreach ($product_images as $item) {
            if ($item['id']) continue;
            ProductHomeImage::create([
                'article_id' => $article_id,
                'image' => $item['image'],
                'image_url' => config('app.url')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $object  = $this->_model->find($id);

        if (!$object || !$id)
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Xóa '.$this->_data['title'].' không thành công',
            ]);
        }

        $object->is_deleted = 0;

        $object->save();

        return response()->json([
            'rs' => 1,
            'msg' => 'Xóa '.$this->_data['title'].' thành công',
        ]);
    }

    /**
     * Enter description here ...
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function ajaxActive(Request $request) {

        $ids = $request->all()['ids'];

        if (!empty($ids))
        {
            foreach ($ids as $id)
            {
                $object  = $this->_model->find($id);
                $object->status = 1;
                $object->save();
            }
            return response()->json([
                'rs' => 1,
                'msg' => 'Kích hoạt '.$this->_data['title'].' thành công',
                'act' => 'active'
            ]);
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Kích hoạt '.$this->_data['title'].' không thành công',
            'act' => 'active'
        ]);
    }

    /**
     * Enter description here ...
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function ajaxInactive(Request $request) {

        $ids = $request->all()['ids'];

        if (!empty($ids))
        {
            foreach ($ids as $id)
            {
                $object  = $this->_model->find($id);
                $object->status = 0;
                $object->save();
            }
            return response()->json([
                'rs' => 1,
                'msg' => 'Ngừng kích hoạt '.$this->_data['title'].' thành công',
                'act' => 'inactive'
            ]);
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Ngừng kích hoạt '.$this->_data['title'].' không thành công',
            'act' => 'inactive'
        ]);
    }

    /**
     * Enter description here ...
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function ajaxDelete(Request $request) {

        $ids = $request->all()['ids'];

        if (!empty($ids))
        {
            foreach ($ids as $id)
            {
                $object  = $this->_model->find($id);
                $object->is_deleted = 1;
                $object->save();
            }
            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa '.$this->_data['title'].' thành công',
                'act' => 'delete'
            ]);
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Xóa '.$this->_data['title'].' không thành công',
            'act' => 'delete'
        ]);
    }

    public function ajaxGetDistrictByProvince($province_id)
    {
        $res = [];

        if (!empty($province_id))
        {
            $res = General::getDistrictOptionsByProvince($province_id);
        }

        return response()->json($res);
    }

    public function ajaxGetWardByDistrict($district_id)
    {
        $res = [];

        if (!empty($district_id))
        {
            $res = General::getWardOptionsByDistrict($district_id);
        }

        return response()->json($res);
    }
}
