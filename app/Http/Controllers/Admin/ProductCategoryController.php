<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductCategory;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCategoryRequest;
use App\Helpers\General;

class ProductCategoryController extends Controller
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
        $this->_data['title'] = 'Nhóm sản phẩm';
        $this->_data['controllerName'] = 'product-category';
        $this->_model = new ProductCategory();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $parentOptions = array('' => '') + $this->_model->getParentOptions();
        $manufacturerOptions = array('' => '') + Manufacturer::getManufacturerOptions();
        $orderOptions = General::getOrderOptions();
        $this->_data['parentOptions'] = $parentOptions;
        $this->_data['manufacturerOptions'] = $manufacturerOptions;
        $this->_data['orderOptions'] = $orderOptions;
        return view("admin.{$this->_data['controllerName']}.create", $this->_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryRequest $request)
    {
        $data = $request->all();

        unset($data['_token']);

        if (empty($data['image_url'])) {
            $data['image_url'] = config('app.url');
        }

        $object = $this->_model->create($data);

        if ($object)
        {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Thêm mới '.$this->_data['title'].' thành công'
                ]);
            }

            return redirect()->route("{$this->_data['controllerName']}.index");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'rs' => 0,
                'msg' => 'Thêm mới ' . $this->_data['title'] . ' không thành công'
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

        $parentOptions = array('' => '') + $this->_model->getParentOptions();
        $manufacturerOptions = array('' => '') + Manufacturer::getManufacturerOptions();
        $orderOptions = General::getOrderOptions();
        $this->_data['parentOptions'] = $parentOptions;
        $this->_data['manufacturerOptions'] = $manufacturerOptions;
        $this->_data['orderOptions'] = $orderOptions;

        return view("admin.{$this->_data['controllerName']}.edit", $this->_data);
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
                    'msg' => 'Lỗi không tồn tại'
                ]);
            }

            return redirect()->route("{$this->_data['controllerName']}.index");
        }

        $data = $request->all();

        if (empty($data['image_url'])) {
            $data['image_url'] = config('app.url');
        }

        unset($data['_token']);

        $object->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Chỉnh sửa '.$this->_data['title'].' thành công'
            ]);
        }

        return redirect()->route("{$this->_data['controllerName']}.index");
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

        $object->is_deleted = 1;

        $object->save();

        return response()->json([
            'rs' => 1,
            'msg' => 'Xóa '.$this->_data['title'].' thành công',
        ]);
    }

    public function ajaxGetCategoryByManufacturer($manufacturer_id)
    {
        $res = [];

        if (!empty($manufacturer_id))
        {
            $res = ProductCategory::ajaxGetCategoryBymanufacturer($manufacturer_id);
        }

        return response()->json($res);
    }

}
