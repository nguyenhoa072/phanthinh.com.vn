<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'supports';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'department', 'phone', 'account', 'type', 'created_at', 'updated_at', 'is_deleted'];

//    protected $hidden = ['deleted_at', 'is_deleted'];

    public static function getListAll($filter){

        $sql = self::select('supports.*');
//        $sql->where('supports.is_deleted', 0);

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('supports.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }
}
?>