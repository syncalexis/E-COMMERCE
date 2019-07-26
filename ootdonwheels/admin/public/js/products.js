var handleFormValidation = function(){
    $.validate({
        form : '#frmAddProduct, #frmUploadPicture',
        modules : 'file'
    });
        
}

var addProductModal = function() {
    $('#addProduct').click(function(){
        var option = {
            show: true,
            backdrop: 'static'
        }

        $('#addProductModal').modal(option);
    });
};

var frmAddProduct = function(){
    $('#frmAddProduct').submit(function(e) {
        e.preventDefault();
        var obj = $("#frmAddProduct").serializeArray();

        $.post('services/products.php', obj, function(response) {
            if(response.isSuccess) {
                $('#categoriesid').val('');
                $('#prodname').val('');
                $('#proddesc').val('');
                $('#price').val('');
                $('#stocks').val('');
                $('#productTable > tbody:last-child').empty();
                getProductList();
                $('#addProductModal').modal('hide');
            } else {
                var alert = '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.msg + '</div>';
                $('#productAlert').html(alert);
            }
        },'json');
    });
}

var getProductList = function(){
    $.post('services/products.php', {action: 'getProductList'}, function(response) {
        if(response.isSuccess) {
            $.each(response.productlist, function(index, element) {
                $('#productTable > tbody:last-child').append('<tr> <td>'+
				element.productid + '</td> <td>'+ element.productname +
				'</td> <td>'+ element.productdesc + '</td> <td>'+ element.unit_price + '</td> <td>'+
				element.onhand_qty + '</td> <td>'+ element.productid + '</td><td>'+
				generateUploadModalBtn(element.productid) + '</td> <td>' + generateEditmodalBtn(element.productid)+'</td> <td>' +
				generateDeletemodalBtn(element.productid)+'</td> </tr>');
                onClickUploadModal(element.productid, element.productname);
				//editproductModal(element.productid, element.productname);
            });
        }
    },'json');
    
}
//wala pakong bootsrap modal dito kaya di pa nag fufunction yung button 
var generateDeletemodalBtn = function(productid){
	return '<button id="#DeleteModal'+productid+'" class="btn btn-primary"> <i class="fa fa-trash" aria-hidden="true"></i> </button>'
}
//wala pakong bootsrap modal dito kaya di pa nag fufunction yung button 
var generateEditmodalBtn = function(productid){
    return '<button id="EditModal'+productid+'" class="btn btn-primary"> <i class="fa fa-gear fa-spin" aria-hidden="true"></i> </button>'
 
}
var editproductModal = function(productid, productname){
var selector='#EditModal' + productid;
	$(selsector).click(function() {
		var option = {
			show: true,
			backdrop: 'static'
		}
		$('#productid').val(productid);
		$('#editModalheader').html('edit product: '+productname)
		$('#editProductModal').modal(option);

	});
}

var generateUploadModalBtn = function(productid){
    return '<button id="uploadModal'+productid+'" class="btn btn-primary"> <i class="fa fa-upload" aria-hidden="true"></i> </button>'   
}

var onClickUploadModal = function(productid, productname){
    var selector = '#uploadModal' + productid;
    $(selector).click(function() {
        var option = {
            show: true,
            backdrop: 'static'
        }

        $('#productid').val(productid);
        $('#uploadpictureheader').html('Upload Image: '+productname)

        $('#uploadImageModal').modal(option);
    });
}

var getCategoriesList = function(){
    $.get('services/products.php', {action: 'getCategoriesList'}, function(response) {
        if(response.isSuccess) {
            $("#categoriesid").append($("<option></option>").val('').html('choose one'));
            $.each(response.categorylist, function(index, element) {
                $("#categoriesid").append($("<option></option>").val(element.categoriesid).html(element.categorycode));
            });
        }
    },'json');
}

var frmUploadPicture = function() {
    $("#frmUploadPicture").submit(function(e) {
        e.preventDefault();
       
        var obj =  new FormData($("#frmUploadPicture")[0]);

        $.ajax({
            type: "POST",
            url: 'services/uploadpicture.php',
            dataType: 'json',
            data: obj,
            processData: false,
            contentType: false, 
            success: function(data) {
                if(data.isSuccess) {
                    $('#uploadImageModal').modal('hide');
                } else {
                    var alert = '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.msg + '</div>';
                    $('#uploadPicturelert').html(alert);    
                }
               
            }
        });
    });
}

var Products = function() {
    "use strict";
    
    return {
        init: function() {
            handleFormValidation(),
            addProductModal(),
            getCategoriesList(),
            frmAddProduct(),
            getProductList(),
            frmUploadPicture()
        }
    }
}()
