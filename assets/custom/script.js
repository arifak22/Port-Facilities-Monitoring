var showErrorMsg = function(form, type, msg) {
    var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
        <span></span>\
    </div>');

    form.find('.alert').remove();
    alert.prependTo(form);
    alert.animateClass('fadeIn animated');
    alert.find('span').html(msg);
    form.html();
}

var alertError = function(error, type=1){
    alert('Maaf, terjadi kesalahan. (Error: ' + error + ')');
}

var resendToken = function(res){
    if(res.jwt_token){
        localStorage.setItem("jwt_token", res.jwt_token);
        if(confirm('Token Reseted, please reload!')){
            window.location.reload();  
        }
    }
}

