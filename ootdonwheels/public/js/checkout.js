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

var handleFormValidation = function(){
    $.validate({
        form : '#frmNewCustomer, #frmPayNow'
    });
        
}

var onchangeOrderQty = function(tmp_cartid,unit_price,cartsession){
    var selector = '#changeqty'+tmp_cartid;
    $(selector).change(function(event) {
        var qty = $(selector).val();
        $.post('services/product.php', {action:'updateQty', tmp_cartid: tmp_cartid, qty: qty, cartsession:cartsession}, function(response) {
            if(response.isSuccess) {
                var dynamictotalselector = '#dynamictotal'+tmp_cartid;
                var newsubtotal = unit_price * qty;
                $(dynamictotalselector).html(newsubtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
                $('#totalcart').html((response.newtotal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));

            }
        },'json');
    });
}

var onclickDeleteBtn = function(tmp_cartid){
    var selector = '#remove'+tmp_cartid;
    $(selector).click(function(){

        $.post('services/product.php', {action: 'removeCartList', tmp_cartid: tmp_cartid}, function(response) {
            if(response.isSuccess){
                cartTable();
                
            }
        },'json');

    });
}

var generateRemoveButton = function(tmp_cartid){
    return  '<button type="button" class="btn btn-primary" id="remove'+tmp_cartid+'"> <i class="fa fa-trash-o" aria-hidden="true"></i> </button>';
}

var generateQtyTextbox = function(tmp_cartid,qty){
    return '<input type="number" min="1" step="1" style="max-width:5em" class="form-control" id="changeqty'+ tmp_cartid +'" value="'+ qty +'">';
}

var cartTable = function(){
    cartcounter();
    $.post('services/checkout.php', function(response) {
        if(response.isSuccess) {
            $('#cartTable > tbody:last-child').empty();
            var total = 0;
            $.each(response.cartlist, function(index, element) {
                total = total + element.total_amount;
                $('#cartTable > tbody:last-child').append('<tr> <td>'+ element.productname + '</td> <td>'+ element.productdesc + '</td> <td style="text-align: right">'+ generateQtyTextbox(element.tmp_cartid,element.order_qty) + '</td> <td style="text-align: right">'+ element.formattedunit_price + '</td> <td style="text-align: right" id="dynamictotal'+element.tmp_cartid+'">'+ element.formattedtotal_amount + '</td> <td style="text-align:center">'+ generateRemoveButton(element.tmp_cartid) + '</td> </tr>');
                onchangeOrderQty(element.tmp_cartid,element.unit_price,element.cartsession);
                onclickDeleteBtn(element.tmp_cartid);
            });

            $('#cartTable > tbody:last-child').append('<tr> <td colspan="5" style="text-align: right" id="totalcart">'+ total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</td></tr>');
            
        } else {
            $('#checkoutnow').addClass('hidden');
            $('#cartTable > tbody:last-child').append('<tr> <td colspan="6" style="text-align: center"> Empty Cart </td></tr>');
        }
    },'json');
}

var checkoutnow = function(){
    $('#checkoutnow').click(function(){
        $('#cartdetails').addClass('hidden');
        $('#paymentdetails').removeClass('hidden');
    });
}

var backtocart = function(){
    $('#backtocart').click(function(){
        $('#paymentdetails').addClass('hidden');
        $('#cartdetails').removeClass('hidden');
    });
}

var frmPayNow = function(){
    $('#frmPayNow').submit(function(event) {
        event.preventDefault();
        var obj = $("#frmPayNow").serializeArray();

        $.post('services/product.php', obj, function(response) {
            $(location).attr('href','myorder.html');
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

var  logout = function(){
    $('#logout').click(function(event) {
        $.get('services/common/destroysession.php', function(data) {
            $(location).attr('href','index.html');
        });
    });
}

var Checkout = function() {
    "use strict";
    
    return {
        init: function() {
            checksession()
            handleFormValidation(),
            cartTable(),
            checkoutnow(),
            backtocart(),
            frmPayNow(),
            cartcounter(),
            logout()
        }
    }
}()
