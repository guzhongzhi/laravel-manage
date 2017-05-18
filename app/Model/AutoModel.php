<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class AutoModel extends Model {
    
    static $CACHE = array();

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        $this->fillable( $this->getAllFields() );
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }
    
    protected function getAllFields() {
        $key = "fillable_field_names";
        if(isset(self::$CACHE[$key])) {
            return self::$CACHE[$key];
        } else {
            $fields = array();
            $rows = DB::select("desc " . $this->table);
            foreach($rows as $row) {
                $fields[] = $row->Field;
            }
            return self::$CACHE[$key] = $fields;
        }
    }
}