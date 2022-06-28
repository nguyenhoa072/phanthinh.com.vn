<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouses';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'category', 'amount'];

    public static function getListAll($filter){

        $sql = self::select('warehouses.*');

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $keyword_new = str_replace(' ', '-', $keyword);
                $query->where('warehouses.name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('warehouses.category', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('warehouses.name', 'LIKE', '%' . $keyword_new . '%')
                      ->orWhere('warehouses.category', 'LIKE', '%' . $keyword_new . '%');
            });
        }

        $sql->orderBy($filter['sort']??'name', $filter['order']??'asc');

        return $sql->paginate($filter['limit']??12)->toArray();
    }
}
?>