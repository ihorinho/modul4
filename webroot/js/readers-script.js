/**
 * Created by ihorinho on 2/9/17.
 */
$('document').ready(function(){
    var readNow = Math.floor(Math.random() * 6);
    var readTotal = parseInt($('#total-read').text());
    $('#reading-now').text(readNow);

    setInterval(function(){
        readTotal += readNow
        $('#total-read').text(readTotal);
        readNow = Math.floor(Math.random() * 6);
        $('#reading-now').text(readNow);
    }, 3000);
});