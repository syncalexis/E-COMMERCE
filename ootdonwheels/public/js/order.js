var checksession = function(){
    $.get('services/common/checksession.php', function(response) {
        if(!response.isSuccess) {
            $(location).attr('href','index.html');
        } else {
            $('#tranMenu').removeClass('hidden');
            $('.caret').removeClass('hidden');
        }
    },'json');
}

var cartcounter = function(){
    $.get('services/cartcounter.php', function(response) {
        if(response > 0) {
            $('#cartcounter').html(response);
            $('#cartcounter').removeClass('hidden');
        }
    });
}

var  logout = function(){
    $('#logout').click(function(event) {
        $.get('services/common/destroysession.php', function(data) {
            $(location).attr('href','index.html');
        });
    });
}

var generateViewDetails = function(cartid){
    return '<button class="btn btn-primary" id="viewDetails'+ cartid +'"> <i class="fa fa-th-list" aria-hidden="true"></i> </button>'
}

var onClickViewDetails = function(cartid,formattedcartid,cartsession){
    var selector = '#viewDetails' + cartid;
    $(selector).click(function(event) {
        var option = {
            show: true,
            backdrop: 'static'
        }

        $('.modal-title').html('Order No.: '+formattedcartid)
        cartTable(cartsession);
        $('#orderDetailsModal').modal(option);
    });
}

var cartTable = function(cartsession){
    $.post('services/checkout.php', {cart: cartsession}, function(response) {
        if(response.isSuccess) {
            $('#cartTable > tbody:last-child').empty();
            var total = 0;
            $.each(response.cartlist, function(index, element) {
                total = total + element.total_amount;
                $('#cartTable > tbody:last-child').append('<tr> <td>'+ element.productname + '</td> <td>'+ element.productdesc + '</td> <td style="text-align: right">'+ element.order_qty + '</td> <td style="text-align: right">'+ element.formattedunit_price + '</td> <td style="text-align: right" id="dynamictotal'+element.tmp_cartid+'">'+ element.formattedtotal_amount + '</td> </tr>');

            });

            $('#cartTable > tbody:last-child').append('<tr> <td colspan="5" style="text-align: right" id="totalcart">'+ total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</td></tr>');
            
        } else {
            $('#cartTable > tbody:last-child').empty();
            $('#cartTable > tbody:last-child').append('<tr> <td colspan="6" style="text-align: center"> Empty Cart </td></tr>');
        }
    },'json');
}



var orderTable = function(){
    $.post('services/order.php',{action: 'getOrder'}, function(response) {
        if(response.isSuccess) {
            $('#orderTable > tbody:last-child').empty();
            $.each(response.orderlist, function(index, element) {

                $('#orderTable > tbody:last-child').append('<tr> <td style="text-align: center">'+ element.formattedcartid + '</td> <td style="text-align:center">'+ element.date_ordered + '</td> <td style="text-align:center">'+ generateViewDetails(element.cartid) + '</td> </tr>');
                onClickViewDetails(element.cartid,element.formattedcartid,element.cartsession);
            });


        } else {
            $('#orderTable > tbody:last-child').append('<tr> <td colspan="6" style="text-align: center"> No Order </td></tr>');
        }
    },'json');
}

var Order = function() {
    "use strict";
    
    return {
        init: function() {
            checksession()
            cartcounter(),
            logout(),
            orderTable()
        }
    }
}()
