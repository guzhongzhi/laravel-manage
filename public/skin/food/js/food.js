$(
    function(){        
        $('#TitleFoodLike').click(
            function(){
                var newId = $(this).attr('data-likeid');
                if($(this).attr('class') == 'link_like'){
                    $.get('/food/like', {newId:newId}, function(msg){
                        $('#food_like_'+newId).html(msg);
                        $('#TitleFoodLike').attr('class', 'link_like click_like');
                    });
                }else{
                    //do nothing
                }
            }
        ); 
        
        $('#TitleStoreLike').click(
            function(){
                var newId = $(this).attr('data-likeid');
                if($(this).attr('class') == 'link_like'){
                    $.get('/store/like', {newId:newId}, function(msg){
                        $('#store_like_'+newId).html(msg);
                        $('#TitleStoreLike').attr('class', 'link_like click_like');
                    });
                }else{
                    //do nothing
                }
            }
        ); 
        
        
    }
    
);