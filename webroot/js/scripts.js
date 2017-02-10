/**
 * Created by Igor on 24.12.2016.
 */
$(document).ready(function(){

    $('li.button.faded').hide();

    var location = window.location.href;
    $('li a').each(function (key, value) {
        if(value == location){
            $(this).parents('li').addClass('active');
        }
    });

    $('.delete-book').click(function(e){
    	if(!confirm('Delete book?')){
    		e.preventDefault();
    		return false;
    	}
    	$(this).parent().parent().fadeOut();
    });

    $('.book-title').click(function(){
	    $(this).siblings('.book-description').slideToggle();
    });

    $('#login-form').submit(function(e){
    	console.log($(this).find('#password').val() == 'igor');
    	if($(this).find('#email').val() == '' ||
    		$(this).find('#password').val() == ''){
	    		e.preventDefault();
				alert('Fill all fields');
    	}
		
    });

    $('#pagination-button').click(function(e){
        e.preventDefault();
        $(this).hide();
        $('li.button.faded').fadeIn(1000);
    });

    $(window).on("unload", function(){
       alert('srgsg');
    });

   setTimeout(function(){
        $('#describe').fadeIn(1000);
    }, 15000);

    $('#describe input[type="button"]').click(function(){
        $('#describe').fadeOut();
    });

});
