$(document).ready(function() {
    $('.addnews').click(function(){
    	$(this).hide();
    	$('.addnewsform').show();
    });
    $('.chnews').click(function(){
    	var $id = $(this).attr('rel');
    	$('#addnewsform-fid').val($id);
    	$.ajax({
    		url: '/site/news',	
    		type: 'POST',
    		dataType: 'json',
    		data: {id: $id,active:'update'},
    		success: function(data){
    			//console.log(data.id);
    			$('#updnews-form').find('img').attr('src',data.photo);
                $('#updnews-form').find('#updnewsform-utitle').val(data.title); 
                $('#updnews-form').find('#updnewsform-utext').val(data.text); 
                $('#updnewsform-fph').val(data.photo);
                $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));  
    		}
    	});
    });
    $('.delnews').click(function(){
    	var id = $(this).attr('rel');
    	$.ajax({
    		url: '/site/news',	
    		type: 'POST',
    		dataType: 'json',
    		data: {id: id,active:'delete'},
    		success: function(data){
    			$('.news'+id).remove();
    		}
    	});
    });
});