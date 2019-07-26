var handleFormValidation = function(){
    $.validate({
        form : '#frmAddCategory'
    });
        
}

var addCategoryModal = function() {
    $('#addCategory').click(function(){
        var option = {
            show: true,
            backdrop: 'static'
        }

        $('#addCategoryModal').modal(option);
    });
};

var frmAddCategory = function(){
    $('#frmAddCategory').submit(function(e) {
        e.preventDefault();
        var obj = $("#frmAddCategory").serializeArray();

        $.post('services/categories.php', obj, function(response) {
            if(response.isSuccess) {
                $('#categorycode').val('');
                $('#categorydesc').val('');
                $('#categoryTable > tbody:last-child').empty();
                getCategoriesList();
                $('#addCategoryModal').modal('hide');
            } else {
                var alert = '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.msg + '</div>';
                $('#categoryAlert').html(alert);
            }
        },'json');
    });
}

var getCategoriesList = function(){
    $.post('services/categories.php', {action: 'getCategoriesList'}, function(response) {
        if(response.isSuccess) {
            $.each(response.categorylist, function(index, element) {
                $('#categoryTable > tbody:last-child').append('<tr> <td>'+ element.categorycode + '</td> <td>'+ element.categorydesc + '</td>  </tr>');
            });
        }
    },'json');
    
}

var Settings = function() {
    "use strict";
    
    return {
        init: function() {
            handleFormValidation(),
            addCategoryModal(),
            frmAddCategory(),
            getCategoriesList()
        }
    }
}()
