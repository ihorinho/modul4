/**
 * Created by Igor on 24.12.2016.
 */
//window.onbeforeunload = function (e) {
//
////    var thisHostName = window.location.hostname;
////    var targetURL = e.target.pathname;
////    console.log(e);
////    var targethostName;
////    targetURL.replace(/http[s]?\:\/\/([\w-]+)\//,  function(match, host){
////        targethostName = host;
//
//        var message = "Are you sure ?";
//        var firefox = /Firefox[\/\s](\d+)/.test(navigator.userAgent);
//        if (firefox) {
//            //Add custom dialog
//            //Firefox does not accept window.showModalDialog(), window.alert(), window.confirm(), and window.prompt() furthermore
//            var dialog = document.createElement("div");
//            document.body.appendChild(dialog);
//            dialog.id = "dialog";
//            dialog.style.visibility = "hidden";
//            dialog.innerHTML = message;
//            var left = document.body.clientWidth / 2 - dialog.clientWidth / 2;
//            dialog.style.left = left + "px";
//            dialog.style.visibility = "visible";
//            var shadow = document.createElement("div");
//            document.body.appendChild(shadow);
//            shadow.id = "shadow";
//            //tip with setTimeout
//            setTimeout(function () {
//                document.body.removeChild(document.getElementById("dialog"));
//                document.body.removeChild(document.getElementById("shadow"));
//            }, 0);
//        }
//    return message;
//}

$(document).ready(function(){

//    alert(window.location.hostname);

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

