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

// requirejs([], function(){
//  alert('hello');
// });

function SiteProcessor(){

    //RATING UP/DOWN
    this.changeRating = function(button, action){
        var commentsLoader = $('#comments-loader');
        commentsLoader.show();
        var $button = $(button);
        var $comment = $button.parent('p');
        var ratingField = $button.siblings('.comment-rating');
        var changeRatingQty = (action == "+") ? 1 : -1;
        var newRating = parseInt(ratingField.text()) + changeRatingQty;

        ratingField.text(newRating);
        $comment.children('.change-rating').fadeOut(500);
        var comment_id = parseInt($button.siblings('.comment-id').text());
        var newId = parseInt($('#new-id').text());
        $.get('/comments/rating-update?id=' + comment_id + '&rating=' + newRating + '&new_id=' + newId)
            .done(function(response){
                console.log(newId);
                if (response.status == 'success') {
                    $('#comments-block').replaceWith(response.html);
                } else {
                    alert(response.message);
                }
                setTimeout(
                    function(){
                        commentsLoader.hide();
                         $('#comments-block').show();
                    },
                    1000
                );
            }
        );

    }

    this.deleteComment = function(button, comment_id){
        if(confirm('Дійсно видалити коммент?')){
            $(button).parents('tr').fadeOut(500);
            $.get('/comments/delete?id=' + comment_id)
                .done(function(data){
                    console.log(data);
                }
            );
        }
        return false;
    }
}

var SiteProcessor = new SiteProcessor();

$(document).ready(function(){

    /* Довантаження новин в катеогрії скролом */
    var inProgress = false;
    var scrollAllowed = true;
    /* Используйте вариант $('#more').click(function() для того, чтобы дать пользователю возможность управлять процессом, кликая по кнопке "Дальше" под блоком статей (см. файл index.php) */
    $(window).scroll(function() {
    	if (!$('.news-container').length) {
    		return;
    	}

        /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
        if($(window).scrollTop() + $(window).height() >= $(document).height() && !inProgress && scrollAllowed) {
            inProgress = true;
            loadMoreNews();
            inProgress = false;
        }

    });

    /* Довантаження новин в катеогрії кнопкою */
    $("#load div").click(function(){ //Выполняем если по кнопке кликнули
        loadMoreNews();
    });

    function loadMoreNews(){
        var commentsLoader = $('#comments-loader');
        commentsLoader.css({'position' : 'absolute', 'top' :'40%', 'left' : '40%'});
        commentsLoader.show(); //Показуємо прелоадер
        var page = parseInt($('#page-counter').text());
        $('#page-counter').text(++page);
        var $url = window.location.href + '?ajaxPage=' + page;

        $.ajax({
            url: $url,
            type: "GET",
            data: {},
            cache: false,
            success: function(response){
                if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                    alert("Більше нема новин в цій категорії");
                }else{
                    if (response.status == 'success' && !response.message) {
                        setTimeout(
                            function(){
                                commentsLoader.hide();
                                $('.news-container').append(response.html);
                            },
                            1000
                        );
                    } else {
                        if (response.code == 001) {
                            $('#load').hide();
                            scrollAllowed = false;
                        }
                        commentsLoader.hide();
                        alert(response.message);
                    }
                }
            }
        });
    }
    /*===============================================================================*/

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

    //Робота з рекламними блоками
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

    //Відправка даних форми для редагування коментарів
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
//    $('.typeahead').on('typeahead:cursorchanged', function (e, datum) {
//
//    });
});