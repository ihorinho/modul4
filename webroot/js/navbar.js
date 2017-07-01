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
});
