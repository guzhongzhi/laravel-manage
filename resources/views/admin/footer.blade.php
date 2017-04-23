<div class="row-fluid">
  <div id="footer" class="span12"> 
    @section('footer')
    2013 &copy; Matrix Admin. More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a> - Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a> 
    @show
  </div>
</div>

<div id="common-modal" class="modal hide in modal-large"  style="display: none">
  <div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h3 class="title"></h3>
  </div>
  <div class="modal-body">
    
  </div>
  <div class="modal-footer">
    <a data-dismiss="modal" class="btn btn-primary" href="javascript:submitCommonModal()">Submit</a> 
    <a data-dismiss="modal" class="btn btn-inverse" href="javascript:void(0)">Cancel</a> 
  
  </div>
</div>
<script language="javascript">

function loadAjaxModal(url) {
    $.getJSON(url,function(response) {
        $("#common-modal").modal();
        $("#common-modal .title").html(response.title);
        $("#common-modal .modal-body").html(response.content);
        $("#common-modal").css("left", (($(document).width() - $("#common-modal").width() ) /2) + "px");
    });
}

function submitCommonModal() {
    $("#common-modal form").submit();
}


</script>