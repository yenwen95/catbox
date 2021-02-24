$(function(){
    
    //Problem: open your box button need to ask user to login if users have not login yet

    $('#loginButton').click(
        function(){
            $('#loginModal').modal('show');
        }
    );

    }
);