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

        $("#shareButton").click(
            function(){
                $('#shareModal').modal('show');
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

//SHOWING shareBox
function displayShareBox(){
    var mybox = document.getElementById("myBoxMiddle");
    var mybox2 = document.getElementById("myBoxRight");

    var shareBox = document.getElementById("shareBoxMiddle");
    var shareBox2 = document.getElementById("shareBoxRight");

    if((mybox.style.display === "none") || (shareBox.style.display === "block")){
        mybox.style.display = "block";
        mybox2.style.display = "flex";

        shareBox.style.display = "none";
        shareBox2.style.display = "none";
    }else{
        mybox.style.display = "none";
        mybox2.style.display = "none";

        shareBox.style.display = "block";
        shareBox2.style.display = "flex";
    }

}

//SHOWING SIDEBAR
function toggleNav(event){
    if(event.value == "open"){
        openNav();
        event.value = "close";
    }else if(event.value == "close"){
        closeNav();
        event.value = "open";
    }
}

function openNav(){
    document.getElementById("mySidebar").style.width = "150px";
    document.getElementById("main").style.marginLeft = "150px";
}

function closeNav(){
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
}

//When clicking the file
function getFileInfo(id, FileID){
    var action = "";
    var filename =  document.getElementById("file_"+id).textContent;

    var mybox = $("#myBoxRight").css("display");
    var sharebox = $("#shareBoxRight").css("display");

    if(mybox === "flex"){
        action = "showFileInfoMyBox";
        showFileInfo(filename, action);
    } else if(sharebox === "flex"){
        action = "showFileInfoShareBox";
        showFileInfoShareBox(FileID, action);
    }


     $("#delButton").unbind("click");
     $("#delButton").click(
         function(){
             action = "deleteFile";
            delFile(filename, action, id);
           
         }
     );

     $('#share-btn').unbind("click");
     $("#share-btn").click(
         function(){
             action = "shareFile";
             checkUser = document.querySelector("#checkUser").value;
             shareFile(filename, action, checkUser);
         }
     );

}

 // UNDONE  -->  document.getElementById("showFileInfo").innerHTML = "<p>No File is selected...</p>";

//Automatic showing the file info
function showFileInfo(filename, action){
   
       //DEFAULT function --> SHOW FILE INFO
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {filename: filename, action: action},
        dataType: 'JSON',
        success: function(return_arr){
            console.log(return_arr);
            $("#name").text(return_arr.filename);
            $("#type").text(return_arr.filetype);
            $("#size").text(return_arr.filesize);
            $("#timecreate").text(return_arr.createtime);
                var shareUser = return_arr.shared_users;
                if(shareUser == '0' || shareUser == 'NULL'){
                    $("#sharewith").text("");
                }else{
                    $("#sharewith").text(shareUser.slice(0,-1));
                
                }
        }
    });
    
}

function showFileInfoShareBox(fileID, action){
    
    //DEFAULT function --> SHOW FILE INFO FOR SHARE BOX
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {fileID: fileID, action: action},
        dataType: 'JSON',
        success: function(return_arr){
            console.log(return_arr);
            $("#name").text(return_arr.filename);
            $("#type").text(return_arr.filetype);
            $("#size").text(return_arr.filesize);
            $("#timecreate").text(return_arr.createtime);
            $("#shareby").text(return_arr.username);
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

//SHARE FUNCTION
function shareFile(filename, action, checkUser){
   
   // check user exits or not
   $.ajax({
       url: 'fileInfo.php',
       type: 'post',
       data: {filename: filename, action: action, checkUser: checkUser},
       dataType: 'JSON',
       success: function(isUserExist){
           if(isUserExist == "no"){
               var error = '<div class="row">'+
                            '<div class="alert alert-danger"> User does not exist! </div>' +
                         '</div>'; 
                $('#shareDiv').append(error);   
           }
           else{
                $('#shareModal').modal('hide');
           }
       }
   })
}
