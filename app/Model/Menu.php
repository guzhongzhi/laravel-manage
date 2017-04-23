<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    protected $childs = array();
	protected $table = "menu";

    public function getIsActive($currentRequestUri) {
        if(strpos($currentRequestUri , $this->url) !== false) {
            return true;
        }
        return false;
    }


    public function appendChild(Menu $item) {
        $this->childs[] = $item;
        return $this;
    }

    public function setChilds(array $childs) {
        $this->childs = $childs;
        return $this;
    }

    public function getChilds() {
        return $this->childs;
    }

    public function hasChild() {
        return count($this->childs);
    }
}
