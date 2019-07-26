var settingsClick = function() {
    $('#settings').click(function(){
        $('#main-content').fadeOut('fast', function() {
            $.get('views/settings.html', function(data) {
                $('#main-content').html(data);
                $('#main-content').fadeIn('fast');
            });   
        });
    });
};

var productmanagementClick = function() {
    $('#productmanagement').click(function(){
        $('#main-content').fadeOut('fast', function() {
            $.get('views/products.html', function(data) {
                $('#main-content').html(data);
                $('#main-content').fadeIn('fast');
            });   
        });
    });
};
var Index = function() {
    "use strict";
    
    return {
        init: function() {
            settingsClick(),
            productmanagementClick()
        }
    }
}()
