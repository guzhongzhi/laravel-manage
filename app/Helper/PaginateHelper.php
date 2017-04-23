<?php
namespace App\Helper;
class PaginateHelper {
    
    protected $modeName = "";
    protected $join = array();
    protected $paginate = null;
    protected $filter = null;
    
    public function __construct($modeName) {
        $this->modeName = $modeName;
    }
    
    public function getPaginate(array $filter) {
        $this->filter = $filter;
        $modelName = $this->modeName;
        $queryBuilder = $modelName::query();
        foreach($filter as $fieldName=>$field) {
            if(!isset($field["type"]) || !isset($field["value"]) || $fieldName == "_order") {
                continue;
            }
            if(isset($field["field_name"])) {
                $fieldName = $field["field_name"];
            }
            $operation = $field["type"];
            $operation = strtolower(trim($operation));
            $value = $field["value"];
            if(is_array($value)) {
                $operation = "in";
            }
            if($operation == "like") {
                $value = "%".$value."%";
            }
            $queryBuilder->where($fieldName, $operation, $value);
        }
        if(isset($filter["_order"])) {
            $fieldName = $filter["_order"]["field_name"];
            $direction = $filter["_order"]["value"];
            if($direction =="") {
                $direction = "DESC";
            }
            $queryBuilder->orderBy($fieldName,$direction);
        }
        /**
         * @var \Illuminate\Database\Eloquent\Collection $rows
         */
        return $this->paginate = $queryBuilder->paginate(15);
        
    }
    
}