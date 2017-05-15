$(
    function(){
        $('.lary_1').each(
            function(){
                this.src = $(this).attr('data-original');
            }
        );
        
        $('#only_text').click(
            function(){
                if($(this).attr('class') == 'fl'){
                    $(this).attr('title', '图文模式');
                    $(this).attr('class', 'fl clicked');
                    $(this).html('<i></i>图文模式');
                    $('.img').hide();
                }else{
                    $(this).attr('title', '只看文字');
                    $(this).attr('class', 'fl');
                    $(this).html('<i></i>只看文字');
                    $('.img').show();
                }
                
               
            }
        );
        
        $('#TitleLike').click(
            function(){
                var newId = $(this).attr('data-likeid');
                if($(this).attr('class') == 'link_like'){
                    $.get('/travel/like', {newId:newId}, function(msg){
                        $('#like_'+newId).html(msg);
                        $('#TitleLike').attr('class', 'link_like click_like');
                    });
                }else{
                    //do nothing
                }
            }
        );
        
       

       
    }
    
);