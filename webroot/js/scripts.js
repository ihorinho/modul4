/**
 * Created by Igor on 24.12.2016.
 */
$('document').ready(function(){
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
});
