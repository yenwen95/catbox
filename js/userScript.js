(function(){


    $('#closeSidebar, .overlay, .overlayMobile').on('click', function(){
        $('#mySidebar').removeClass('active');
        $('.overlay').removeClass('active');
        $('.overlayMobile').removeClass('active');
    });

    $('#openSidebar').on('click', function(){
        $('#mySidebar').addClass('active');
        $('.overlay').addClass('active');
    });


})();