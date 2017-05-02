<style>
.search-form select{
    
    display:none;
    
}
</style>
<form class="search-form" id="search-form">
    
        @foreach($filter as $fieldName=>$field)
        
        @if($field["input_type"]=="hidden")
            <input type="hidden" id="search-form-{{$fieldName}}" value="{{isset($field["value"]) ? $field["value"] :""}}" name="filter[{{$fieldName}}]"  class="span11">
            @continue
        @endif
        
        <div class="control-group">
        <label class="control-label">{{$field["label"]}}</label>
        <div class="controls">
        
        @if($field["input_type"]=="text")
            <input type="text" id="search-form-{{$fieldName}}" value="{{isset($field["value"]) ? $field["value"] :""}}" name="filter[{{$fieldName}}]"  class="span11">
        @endif
        
        @if($field["input_type"]=="select")
            <select name="filter[{{$fieldName}}]" id="search-form-{{$fieldName}}">
                <option value="">  </option>
                @foreach($field["options"] as $value=>$label)
                <option value="{{$value}}" 
                @if(isset($field["value"]) && $value == $field["value"])
                    selected
                @endif > {{$label}} </option>
                @endforeach
            </select>
        @endif
        
        @if($field["input_type"]=="mutiselect")
            <select name="filter[{{$fieldName}}][]" id="search-form-{{$fieldName}}" multiple="multiple" class="select2-container select2-container-multi">
                @foreach($field["options"] as $value=>$label)
                <option value="{{$value}}" 
                @if(isset($field["value"]) && is_array($field["value"]) && in_array($value,$field["value"]))
                    selected
                @endif > {{$label}} </option>
                @endforeach
            </select>
        @endif
        
      </div>
    </div>
    @endforeach
    
    
    
    <div class="control-group control-group-bottons">
        <button type="submit" class="btn btn-primary"><i class="icon-zoom-in"></i> Search</button> 
        <button type="button" class="btn btn-inverse"><i class="icon-undo"></i> Reset</button>
    </div>
    <div style="clear:both"></div>
        
</form>


