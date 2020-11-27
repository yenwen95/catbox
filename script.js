$(function(){
        //LOGIN MODAL
        
        $('#loginButton').click(
            function(){
                $('#loginModal').modal('show');
            }
        );
         
        $('#addButton').click(
            function () {
                $('#uploadModal').modal('show');
            }
        );

       document.querySelector('#getFile').onchange = function(){
           document.querySelector('#getFileName').textContent = this.files[0].name;
       }
    
       $("div.off-select").click(
           function(){
               $("div.off-select").not(this).removeClass('on-select');
                $(this).toggleClass("on-select");
       });

      


    }
);
//When clicking the file
function getFileInfo(id){
    var action = "";
    var filename =  document.getElementById("file_"+id).textContent;
    action = "showFileInfo";
    showFileInfo(filename, action);

     $("#delButton").unbind("click");

     $("#delButton").click(
         function(){
             action = "deleteFile";
            delFile(filename, action, id);
           
         }
     );
}


//Automatic showing the file info
function showFileInfo(filename, action){
    
       // UNDONE  -->  document.getElementById("showFileInfo").innerHTML = "<p>No File is selected...</p>";
       //DEFAULT function --> SHOW FILE INFO
       $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {filename: filename, action: action},
        dataType: 'JSON',
        success: function(return_arr){
            $("#name").text(return_arr.filename);
            $("#type").text(return_arr.filetype);
            $("#size").text(return_arr.filesize);
            $("#timecreate").text(return_arr.createtime);
        } 
        });
    
}

//DELETE FUNCTION
function delFile(filename, action, id){
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {filename: filename, action: action},
        dataType: 'JSON',
        success: function(status){
            if(status == "success"){
                $("#row_"+id).remove();
            }else{
                console.log("fail");
            }
        }

    });
}