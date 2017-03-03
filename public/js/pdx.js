// inject alert
function alertMessage(msg, type) {
    if (!type) {
        type = 'alert-success';
    }
    $('#top').prepend('<div class="ajax-fb alert ' +  type + ' fade in" role="alert"><span class="msg">'+msg+'</span></div>')
    setTimeout(function() {
        $(".ajax-fb").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 5000);
}
