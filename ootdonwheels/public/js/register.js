var checksession = function(){
    $.get('services/common/checksession.php', function(response) {
        if(response.isSuccess) {
            $(location).attr('href','checkout.html');
        }
    },'json');
}

var handleFormValidation = function(){
    $.validate({
        form : '#frmNewCustomer,#frmLogin'
    });
        
}

var frmNewCustomer = function(){
    $('#frmNewCustomer').submit(function(event) {
        event.preventDefault();
        var obj = $("#frmNewCustomer").serializeArray();
        
        $.post('services/customer.php', obj, function(response) {
            if(response.isSuccess){
                $(location).attr('href','checkout.html');
            } else {
                var alert = "<div class='alert alert-danger fade in'>" +
                    "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" +
                    "<strong>Message!</strong>  <br/> Email Address already taken!" +
                "</div>";

                $("#newCustomerAlert").html(alert);
            }
        },'json');
    });
}

var cartcounter = function(){
    $.get('services/cartcounter.php', function(response) {
        if(response > 0) {
            $('#cartcounter').html(response);
            $('#cartcounter').removeClass('hidden');
        }
    });
}

var frmLogin = function(){
    $('#frmLogin').submit(function(event) {
        event.preventDefault();
        var obj = $("#frmLogin").serializeArray();
        
        $.post('services/customer.php', obj, function(response) {
            if(response > 0) {
                $(location).attr('href','checkout.html');
            } else {
                var alert = "<div class='alert alert-danger fade in'>" +
                    "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" +
                    "<strong>Message!</strong>  <br/> Incorrect Login Please try Again" +
                "</div>";

                $("#loginAlert").html(alert);
            }
        });
    });
}
var Register = function() {
    "use strict";
    
    return {
        init: function() {
            handleFormValidation(),
            frmNewCustomer(),
            checksession(),
            cartcounter(),
            frmLogin()
        }
    }
}()
