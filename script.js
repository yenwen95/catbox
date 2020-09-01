$(document).ready(
    function(){
        //LOGIN MODAL
        
        $('#loginButton').click(
            function(){
                $('#loginModal').modal('show');
            }
        );

        //REGISTER MODAL
        $('#registerButton').click(
            function(){
                $('#registerModal').modal('show');
            }
        );
        
    }
);