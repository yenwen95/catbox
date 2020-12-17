$(function(){
    
        $('#loginButton').click(
            function(){
                $('#loginModal').modal('show');
            }
        );

        $('#mySidebar').on('click', '#gotoMyBox',function(){
            var displayFile = "displayFileList";
            displayBox(displayFile);
        });

        $('#mySidebar').on('click', '#gotoShareBox',function(){
            var displayFile = "displayShareFileList";
            displayBox(displayFile);
        });

        $('#main').on('click', '#addButton', function(){
            $('#uploadModal').modal('show');
        });

        $('#main').on('click', '#shareButton', function(){
            $('#shareModal').modal('show');
        });

        var displayFile = "displayFileList";
        var page = "mybox";
        displayFileList(displayFile, page);

        document.querySelector('#getFile').onchange = function(){
            document.querySelector('#getFileName').textContent = this.files[0].name;
        }
     
 

      $('#uploadModal').on('click', '#uploadButton', function(){
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
                            var error = '<div class="form-row">'+
                                            '<div class="alert alert-danger"> File exists! </div>' +
                                        '</div>'; 
                            $('#uploadForm').append(error); 
                        }else{
                            $('#uploadModal').modal('hide');
                            $("#main").load(location.href + " #main");
                            var displayFile = "displayFileList";
                            var page = "mybox";
                            displayFileList(displayFile, page);

                        }
                    }
                });
                }
            }
        );
     
  
    }
);


/*  FILE LISTING ORDER METHOD

Default = order by name (acs) a -> z

Name (dcs) z -> a

createdTime (acs) latest -> oldest

createdTime (decs) oldest -> latest

file type (group by file type, order file type name, acs) 

file type (group by file type, order file type name, decs)

file size (acs) smallest -> largest
 
file size (decs) largest -> smallest


*/

//show certain file type name



function displayFileList(displayFile, page){
    
    $.ajax({
        url: 'fileInfo.php',
        type: 'GET',
        data: {displayFile: displayFile},
        dataType: 'html',
        success: function(fileList){
            $('#mainContainer').append(fileList);

            $('#myBoxMiddle').on('click', '.off-select', function(){ 
                $(this).siblings(".off-select").removeClass("on-select");
                $(this).toggleClass("on-select");
            });

            $('#shareBoxMiddle').on('click', '.off-select', function(){ 
                $(this).siblings(".off-select").removeClass("on-select");
                $(this).toggleClass("on-select");
            });

           if(page == "sharebox"){
            var mybox = document.getElementById("myBoxRight");
            var shareBox = document.getElementById("shareBoxRight");
            mybox.style.display = "none";
            shareBox.style.display = "flex";
           }

        }

    });

}


//CHANGING Between mybox and sharebox at SideBar
function displayBox(displayFile){
    if(displayFile == "displayShareFileList"){
        $("#main").load(location.href + " #main");
        var page = "sharebox";
        displayFileList(displayFile, page);
    }else if(displayFile == "displayFileList"){
        $("#main").load(location.href + " #main");
        var page = "mybox";
        displayFileList(displayFile, page);
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


    if(FileID == "" || FileID == undefined){
        action = "showFileInfoMyBox";
        showFileInfo(filename, action);
    } else{
        action = "showFileInfoShareBox";
        showFileInfo(FileID, action);
    }


     $("#delButton").unbind("click");
     $('#main').on('click', '#delButton', function(){
             action = "deleteFile";
            delFile(filename, action, id);
           
         }
     );

     $('#share-btn').unbind("click");
     $('#shareModal').on('click', '#share-btn', function(){
             action = "shareFile";
             checkUser = document.querySelector("#checkUser").value;
             shareFile(filename, action, checkUser);
         }
     );

     
     $('#downloadButton').unbind("click");
     $('#main').on('click', '#downloadButton', function(){
            if(FileID == "" || FileID == undefined){
                action = "downloadFile";
                document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + filename;
            } else{
                action = "downloadShareFile";
                document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + FileID;
                
            }

         }
     );
     
     $('#previewButton').unbind("click");
     $("#previewButton").one("click",
         function(){
             $('#previewModal').modal('show');
             if(FileID == "" || FileID == undefined){
                action="previewFile";
                previewFile(filename, action);
             }else{
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


