var checksession = function(){
    $.get('services/common/checksession.php', function(response) {
        if(response.isSuccess) {
            $('#loginMenu').addClass('hidden');
            $('#tranMenu').removeClass('hidden');
            $('.caret').removeClass('hidden');
        }
    },'json');
}

var generateCategory = function(){
    $.post('services/category.php', {action: 'getcategory'}, function(response) {
        i = 0;
        $.each(response.categorylist, function(index, element) {
            //if(i == 0) {
           //     $("#categorytabs").append('<li class="active"><a data-toggle="tab" href="javascript:;" href="#' + element.categoriesid + '">' + element.categorycode + '</a></li>');
           // } else {
                $("#categorytabs").append('<li><a data-toggle="tab" href="#" id="cat'+ element.categoriesid + '">' + element.categorycode + '</a></li>');
            //}
           // i++;

            onClickCategoryTabs(element.categoriesid)
        });
    },'json');
}

var getProductperCategory = function(categoriesid){
    $.post('services/product.php', {action: 'getProductperCategory', categoriesid: categoriesid }, function(response) {

        if(response.productlist) {
            $.each(response.productlist, function(index, element) {
                getProductContainer(element.productid,element.productname,element.productdesc,element.unit_price,element.onhand_qty,element.image);
            });
        }
        
            
    },'json');
}

var onClickCategoryTabs = function(categoriesid){
    var selector = '#cat'+categoriesid;
    $(selector).click(function(event) {
        $('#main-content').empty();
        getProductperCategory(categoriesid);
        
    });
}

/*var getProductImage = function(productid) {
    
    $.get('services/product.php', {action: 'getProdImage',productid: productid} ,function(data){
        return data;
        if(data != '0') {
            return '<img src="public/img/no-image.png" class="img-responsive" style=" display: block;margin: 0 auto;">';
        } else {
            return '<img src="public/img/no-image.png" class="img-responsive" style=" display: block;margin: 0 auto;">';        
        }
    });
    
} */

var getProductContainer = function(productid,productname,productdesc,price,stocks,image){

    var content = '<div class="col-md-3">' +
        '<div class="panel panel-primary">' +
            '<div class="panel-heading" id="prod' + productid + '"> ' + productname + ' </div>'+

            '<div class="panel-body">' +
                '<div class="row">' +
                    '<div class="col-md-12">' +
                        '<img src="public/upload/products/'+ image +'" class="img-responsive" style=" display: block;margin: 0 auto;">' +
                        '<p> Price: ' + price + '</p>' +
                        '<p> Stocks: ' + stocks + '</p>' +
                        '<p style="text-align:center"> <button class="btn btn-primary" id="btnAddtoCart' + productid + '"> Add to Cart </button> </p>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>'+
    '</div>';


    var modalCheckout = ' <div id="optionModal' + productid +'" class="modal fade" role="dialog"> ' +
        '<div class="modal-dialog"> ' +

            '<div class="modal-content"> ' +
                '<div class="modal-header"> ' +
                    '<button type="button" class="close" data-dismiss="modal">&times;</button> ' +
                    '<h4 class="modal-title">  ' + productname + ' </h4> ' +
                '</div> ' +
                    
                '<div class="modal-body"> ' +
                    '<div class="row">' +
                        '<div class="col-md-6">' +
                            '<img src="public/upload/products/'+ image +'" class="img-responsive" style=" display: block;margin: 0 auto;">' +
                            '<p> Price: ' + price + '</p>' +

                            '<p style="text-align:center"> <a href="register.html" class="btn btn-primary"> Proceed to Checkout? </a> </p>' +
                        '</div> ' +

                        '<div class="col-md-6">' +
                            ' <p style="text-align:center"> <button type="button" class="btn btn-primary" data-dismiss="modal">Continue Shopping</button>  </p> '+
                        '</div> ' + 
                    '</div> ' + 
                '</div> ' +
                    
            '</div> ' +
        '</div>  ' +
    '</div>';

    $('#main-content').append(content);
    $('#main-content').append(modalCheckout);
    onClickAddtoCart(productid);
    optionModal(productid);
}

var onClickAddtoCart = function(productid){

    var selector = '#btnAddtoCart'+productid;
    $(selector).click(function(event) {
        
        $.get('services/cart.php', { productid : productid , qty: 1}, function(data){
            cartcounter();
        });

        
            
    });


}

var optionModal = function(productid){
    var selector = '#btnAddtoCart'+productid;
    var optionModalSelector = '#optionModal'+productid;
    $(selector).click(function(event) {
        var option = {
            show: true,
            backdrop: 'static'
        }

        $(optionModalSelector).modal(option);
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

var Index = function() {
    "use strict";
    
    return {
        init: function() {
            checksession(),
            generateCategory(),
            cartcounter(),
            logout()
        }
    }
}()
