<?php
namespace App\Helper;
class PaginateHelper {
    
    protected $modeName = "";
    protected $join = array();
    protected $paginate = null;
    protected $filter = null;
    
    public static function initSearchFieldData($searchData,$searchForm) {
        foreach($searchData as $key=>$value) {
            if($key == "_order") {
                $searchForm[$key] = $value;
                $searchForm[$key]["label"]="";
                $searchForm[$key]["input_type"]="hidden";
                continue;
            }
            $searchForm[$key]["value"] = $value;
        }
        return $searchForm;
    }
    
    public function __construct($modeName) {
        $this->modeName = $modeName;
    }
    
    public function getPaginate(array $filter) {
        $this->filter = $filter;
        $modelName = $this->modeName;
        $queryBuilder = $modelName::query();
        foreach($filter as $fieldName=>$field) {
            if(!isset($field["type"]) || !isset($field["value"]) || $fieldName == "_order" || $field["value"] === "") {
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
        if(isset($filter['_force_index'])){
            $indexTable = $filter["_force_index"]['table_name'];
            $indexName = $filter["_force_index"]['index_name'];
            $queryBuilder->from(\DB::raw("$indexTable FORCE INDEX ($indexName)"));;
        }


        /**
         * @var \Illuminate\Database\Eloquent\Collection $rows
         */
        $this->paginate = $queryBuilder->paginate(20);
        //print_r(get_class_methods($this->paginate));die();
        return $this->paginate;
    }
    
    
    
    public function getItemsNumber() {
        return $this->paginate->total;
    }
    
    public function getCurrentPage() {
        return $this->paginate->currentPage();
    }
    
    var $currentDisplayNumber = -1;
    function initPageNumberDisplay() {
        $this->currentDisplayNumber = -1;
    }
    
    public function hasNextPageToDisplay() {
        $this->currentDisplayNumber += 1;
        $statNumber = $this->getPageStartNumber() + $this->currentDisplayNumber;
        $endNumber = $this->getPageEndNumber();
        if( $statNumber <=  $endNumber) {
            return true;
            
        }
        return false;
    }
    
    public function getCurrentDisplayPageNumber() {
        return $this->getPageStartNumber() + $this->currentDisplayNumber;
    }
    
    public function getPageStartNumber() {
        return $this->getRangePageNumber(-5);
    }
    
    public function getTotalPage() {
        return $this->paginate->lastPage();
    }
    
    protected function getRangePageNumber($steep = 5) {
        $lastPage = $this->paginate->lastPage();
        $n = $this->paginate->currentPage();
        $n = $n + $steep;
        if($n <= 0) {
            $n = 1;
        }
        if($n > $lastPage) {
            $n = $lastPage;
        }
        return $n;
    }
    
    public function getPageEndNumber() {
        return $this->getRangePageNumber(5);
    }
    
    public function getFirstPageNumger() {
        return 1;
    }
    
    public function getPrevPageNumber() {
        return $this->getRangePageNumber(-1);
    }
    
    public function getNextPageNumber() {
        return $this->getRangePageNumber(1);
    }
    
    public function getLastPageNumber() {
        return $lastPage = $this->paginate->lastPage();
    }
    
    public function getSortByUrlByNumberAndFieldName($fieldName,$page = 1) {
        $filter = $this->filter;
        if(!isset($filter["_order"])) {
            $filter["_order"] = array(
                "field_name"=>$fieldName,
                "value"=>"ASC"
            );
        } else {
            $oldSortOrderField = $filter["_order"];
            $orderDirection = "asc";
            if($fieldName == $oldSortOrderField["field_name"]) {
                if(strtolower(trim($oldSortOrderField["value"])) == "asc") {
                    $orderDirection = "desc";
                } else {
                    $orderDirection = "asc";
                }
            }
            $filter["_order"] = array(
                "field_name"=>$fieldName,
                "value"=>$orderDirection,
            );
        }
        
        $pageUrl = "?page=".$page;
        foreach($filter as $name=>$field) {
            if($name == "_order") {
                $pageUrl .= "&filter[".$name."][field_name]=".$field["field_name"]."&filter[".$name."][value]=".$field['value'];
                continue;
            }
            if(!isset($field["value"])) {
                continue;
            }
            if(is_array($field["value"])) {
                if(empty($field["value"])) {
                    continue;
                }
                foreach($field["value"] as $value) {
                    $pageUrl .= "&filter[".$name."][]=".urlencode($value);
                }
            }else if($field["value"] != "") {
                $pageUrl .= "&filter[".$name."]=".urlencode($field["value"]);
            }
        }
        
        return $pageUrl;
    }
    
    public function getOrderedFieldClass($fieldName) {
        $filter = $this->filter;
        if(isset($filter["_order"])) {
            if($filter["_order"]["field_name"] == $fieldName) {
                return "filter-ordered-active-".$filter["_order"]["value"];
            }
        }
        return "filter-ordered";
    }
    
    public function getPageUrlByNumber($page) {
        $filter = $this->filter;
        $pageUrl = "?page=".$page;
        foreach($filter as $name=>$field) {
            if($name == "_order") {
                $pageUrl .= "&filter[".$name."][field_name]=".$field["field_name"]."&filter[".$name."][value]=".$field['value'];
                continue;
            }
            
            if(!isset($field["value"])) {
                continue;
            }
            if(is_array($field["value"])) {
                if(empty($field["value"])) {
                    continue;
                }
                foreach($field["value"] as $value) {
                    $pageUrl .= "&filter[".$name."][]=".urlencode($value);
                }
            }else if($field["value"] != "") {
                $pageUrl .= "&filter[".$name."]=".urlencode($field["value"]);
            }
        }
        
        return $pageUrl;
    }
}