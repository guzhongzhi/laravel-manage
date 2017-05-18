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

        $('.btn_map').click(
                function(){
                    if(this.className == 'btn_map J_btn_map'){
                        this.className = 'btn_map J_btn_map open';
                        $('.map').css('height','auto');
                    }else{
                        this.className = 'btn_map J_btn_map';
                        $('.map').css('height','0');
                    }

                }

        );

        $('.tab.j_tab a').click(
            function(){
                if(this.id == 'recommand_travel'){
                    $(this).attr('class', 'on');
                    $('#new_travel').attr('class', '');
                    $('.recommand_travel').show();
                    $('.new_travel').hide();
                }else{
                    $(this).attr('class', 'on');
                    $('#recommand_travel').attr('class', '');
                    $('.recommand_travel').hide();
                    $('.new_travel').show();
                }

            }
        );


        
        
    }
    
);