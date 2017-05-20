/**
 * Created by Igor on 24.12.2016.
 */
// window.onbeforeunload = function (e) {
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
// }

$(document).ready(function(){

    $('li.button.faded').hide();

    var location = window.location.href;
    $('li a').each(function (key, value) {
        if(value == location){
            $(this).parents('li').addClass('active');
        }
    });

    $('.delete-book').click(function(e){
    	if(!confirm('Дійсно видалити новину?')){
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

   //Повідомлення про підписку
   setTimeout(function(){
        $('#describe').fadeIn(1000);
    }, 15000);

    $('#describe input[type="button"]').click(function(){
        $('#describe').fadeOut();
    });

    //Блок реклами + скидки
    var  price = 0;
    $(".adver").hover(function(){
        $(this).children('.adver-sale').fadeIn(2000);
        price =  parseInt($(this).children('.adver-price').html());
        $(this).children('.adver-price').html(parseInt(price - price * 0.1));
        $(this).children('.adver-price').css({'font-size': '70px', 'color': 'red'});
    }, function(){
        $(this).children('.adver-sale').hide();
        $(this).children('.adver-price').html(price);
        $(this).children('.adver-price').css({'font-size': '30px', 'color': '#000'});
        price = 0;
    });

//    Робота з рекламними блоками
    $('.adver-form').on('submit', function(e){
        e.preventDefault();
        $.post('/admin/edit/advert', $(this).serialize()).done(function(data){
            console.log(data);
        });
    });

    $('#add-advert-row').click(function(){
        console.log('gsg');
        document.location.href= '/admin/advert/add';
    });

// Відправка даних форми для редагування коментарів
    $('.comments-form').on('submit', function(e){
        e.preventDefault();
        $.post('/admin/comments/view', $(this).serialize())
            .done(function(data){
                alert(data);
            })
            .fail(function(data){
                alert(data);
            });
    });

    $('.waiting-comments-form').on('submit', function(e){
        e.preventDefault();
        $.post('/admin/comments/view', $(this).serialize())
            .done(function(data){
                alert(data);
            })
            .fail(function(data){
                alert(data);
            });
    });

    $('#comment-message').on('submit', function(e){
        e.preventDefault();
        $.post('/comments/add', $(this).serialize())
            .done(function(data){
                alert(data);
                $('.comment-message-container').fadeOut(1000);
            })
            .fail(function(data){
                alert(data);
            });
    });

// Відправка даних форми для конфігурування фону, меню
    $('#manage-menu-form').on('submit', function(e){
        e.preventDefault();
        $.post('/admin/manage/menu', $(this).serialize())
            .done(function(message){
                alert('Зміни успішно збережені');
            })
            .fail(function(message){
                alert(message);
            });
    });

//TYPEAHEAD SCRIPT SECTION
    // Defining the local dataset
    var tagString = 'фінанси, фінанси і політика, політика, релігія, культура, мистецтво, економіка, наука, ' +
        'живопис,  екологія, психологія, історія, бізнес, гроші, суспільство, війна, кримінал, бандитизм, спорт, ' +
        'футбол, бокс, баскетбол, теніс';
    var tags = tagString.split(', ');

    // Constructing the suggestion engine
    var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: tags
    });

    // Initializing the typeahead
    $('.typeahead').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing result */
        },
        {
            name: 'tags',
            source: tags
        });

    $('.typeahead').on('typeahead:selected', function (e, tag) {
        document.location.href = '/news/filter-tag/page/1?tag=' + tag;
    });
    $('.typeahead').keyup(function(event, data){
        if(event.keyCode == 13){
          var tag = $('.typeahead.tt-input').val();
            document.location.href = '/news/filter-tag/page/1?tag=' + tag
        }
    });

    //RATING UP - DOWN
    $('.rating-down').click(function(){
        var ratingField = $(this).siblings('.comment-rating');
        var rating = parseInt(ratingField.text());
        ratingField.text(--rating);
        var newRating = parseInt(ratingField.text());
        $(this).siblings('.rating-up').fadeOut(500);
        $(this).fadeOut(500);
        var comment_id = parseInt($(this).siblings('.comment-id').text());
        console.log(comment_id, newRating);
        $.get('/comments/rating-update?id=' + comment_id + '&rating=' + newRating)
            .done(function(data){
                console.log(data);
            })
    });

    $('.rating-up').click(function(){
        var ratingField = $(this).siblings('.comment-rating');
        var rating = ratingField.text();
        ratingField.text(++rating);
        var newRating = ratingField.text();
        $(this).siblings('.rating-down').fadeOut(500);
        $(this).fadeOut(500);
        var comment_id = $(this).siblings('.comment-id').text();
        $.get('/comments/rating-update?id=' + comment_id + '&rating=' + newRating)
            .done(function(data){
                console.log(data);
            });
    });
//    $('.typeahead').on('typeahead:cursorchanged', function (e, datum) {
//
//    });
// ______________________________________________________________
});

