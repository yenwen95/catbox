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
/*
        $('#uploadButton').click(
            function(){
               var formData = new FormData();
               var files = $('#getFile')[0].files;

               if(files.length > 0){
                   formData.append('getFile', files[0]);
               

                $.ajax({
                    url: 'fileUpload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(status){
                        if(status == "exist"){
                            console.log("ho");
                            var error = '<div class="form-row">'+
                                            '<div class="alert alert-danger"> File exists! </div>' +
                                        '</div>'; 
                            $('#uploadForm').append(error);  
                            
                        }else{
                            $('#uploadModal').modal('hide');
                            //does not show the new file after successfully upload, need to refresh
                        }
                    }
                });
                }
            }
        );

*/

        $('#uploadButton').click(
            function(){
            var action = "uploadFile";
            var formData = new FormData();
            var files = $('#getFile')[0].files;

            if(files.length > 0){
                formData.append('getFile', files[0]);
                formData.append('action',action);

                $.ajax({
                    url: 'fileInfo.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(status){
                        if(status == "exist"){
                            console.log(status);
                            var error = '<div class="form-row">'+
                                            '<div class="alert alert-danger"> File exists! </div>' +
                                        '</div>'; 
                            $('#uploadForm').append(error);  
                            
                        }else{
                            console.log(status);
                            $('#uploadModal').modal('hide');
                            //does not show the new file after successfully upload, need to refresh
                        }
                    }
                });
                }
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
        showFileInfo(FileID, action);
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

     
     $('#downloadButton').unbind("click");
     $("#downloadButton").click(
         function(){
            if(mybox === "flex"){
                action = "downloadFile";
                document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + filename;
            } else if(sharebox === "flex"){
                action = "downloadShareFile";
                document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + FileID;
                
            }

         }
     );
     
     $('#previewButton').unbind("click");
     $("#previewButton").one("click",
         function(){
             $('#previewModal').modal('show');
             if(mybox === "flex"){
                action="previewFile";
                previewFile(filename, action);
             }else if(sharebox === "flex"){
                action="previewShareFile";
                previewFile(FileID, action);
             }
         }
     );


}

 // UNDONE  -->  document.getElementById("showFileInfo").innerHTML = "<p>No File is selected...</p>";

//Preview File
function previewFile(file, action){
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {file: file, action: action},
        dataType: 'JSON',
        success: function(return_arr){
            var type = return_arr['filetype'];
            var path = return_arr['path'];
            var imgbox = document.getElementById("previewImg");
            var framebox = document.getElementById("previewFrame");
            imgbox.style.display = "none";
            framebox.style.display = "none";
            
            if(type == "jpg" || type == "png" ){  
                 //use <img>
                framebox.style.display = "none";
                imgbox.style.display = "block";
                $('#previewImg').attr("src", src="./file_dir/" + path);
                  //$('#previewImg').attr("src", src="https://catboxtest.000webhostapp.com/file_dir/" + path);
            }else{ 
                //use <iframe>
                imgbox.style.display = "none";
                framebox.style.display = "block";
                $("#previewFrame").attr("src", src="https://docs.google.com/viewer?url=https://calibre-ebook.com/downloads/demos/demo.docx&embedded=true");
               //$("#previewFrame").attr("src", src="https://docs.google.com/viewer?url=https://catboxtest.000webhostapp.com/file_dir/" + path + "&embedded=true");
            }
        }
    });
}


//Automatic showing the file info
function showFileInfo(file, action){
   
    //DEFAULT function --> SHOW FILE INFO
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {file: file, action: action},
        dataType: 'JSON',
        success: function(return_arr){
            var act = return_arr['action'];
            if(act == "showFileInfoMyBox"){
                $("#name").text(return_arr.filename);
                $("#type").text(return_arr.filetype);
                $("#size").text(return_arr.filesize);
                $("#timecreate").text(return_arr.createtime);
                    var shareUser = return_arr.shared_users;
                    if(shareUser.trim() == ''){
                        $("#sharewith").text("");
                    }else{
                        $("#sharewith").text(shareUser.slice(0,-1));
                    }
            }else if(act == "showFileInfoShareBox"){
                $("#name").text(return_arr.filename);
                $("#type").text(return_arr.filetype);
                $("#size").text(return_arr.filesize);
                $("#timecreate").text(return_arr.createtime);
                $("#shareby").text(return_arr.username);
            }
            
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
   });
}


