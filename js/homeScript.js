(function(){
    
    //Problem: create function let user delete their account
    //Problem: if it can store other language file or not

        var order, FILEID, NUM, FILENAME, em = "";
    $(function(){
        
        setFile(em, em, em);

        document.addEventListener("click", function(evt){
            var mainContainer = document.getElementById('mainContainer');
            targetElement = evt.target;
            do{
                if(targetElement == mainContainer){
                    console.log("inside");
                    return;
                }
                targetElement = targetElement.parentNode;
            }while(targetElement);
            console.log("outside");
            setFile(em, em, em);
            $('.off-select').removeClass('on-select'); 
            clearFileInfo();
        });
       

            $('[data-toggle="tooltip"]').tooltip({
                delay: {
                    show: 800,
                    hide: 0
                }
            });


            $('#main').on('click', '#addButton', function(){
                $('#uploadModal').modal('show');
            });

            $('#main').on('click', '#shareButton, #shareButtonMobile', function(){
                $('#fileInfoMobile').addClass('fileInfoMobileClose');
                $('.overlayMobile').removeClass('active');
                var filename = getFILENAME();
                var num = getNUM();
                if (num == "" || num == undefined){
                    $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                }else{
                    $("#tobeShared").text(filename);
                    $('#shareModal').modal('show');
                }
            });

            $('#main').on('click', '#delButton, #delButtonMobile', function(){
                $('#fileInfoMobile').addClass('fileInfoMobileClose');
                $('.overlayMobile').removeClass('active');
                var filename = getFILENAME();
                var num = getNUM();

                if (num == ""){
                    $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                }else{
                    $("#tobeDeleted").text(filename);
                    $('#deleteModal').modal('show');
                }
               
            });

            $('#closeSidebar, .overlay, .overlayMobile').on('click', function(){
                $('#mySidebar').removeClass('active');
                $('.overlay').removeClass('active');
                $('.overlayMobile').removeClass('active');
                $('#fileInfoMobile').addClass('fileInfoMobileClose');
            });

            $('#openSidebar').on('click', function(){
                $('#mySidebar').addClass('active');
                $('.overlay').addClass('active');
            });

        

        
            //Automatic show the user's file when enter the user page
            var page = "mybox";
            var displayFile = "displayFileList";
            var sortType = "sortByDefault";
            $("#boxName").text("myBox@");
            displayFileList(displayFile, page, sortType);

            document.querySelector('#getFile').onchange = function(){
                document.querySelector('#getFileName').textContent = this.files[0].name;
            }
        
            //Problem: create function allow user to change file name before upload to the system
            
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
                                $("div.message1").fadeIn(300).delay(1500).fadeOut(400);
                            }else{
                              
                                $('#uploadModal').modal('hide');
                                $("#main").load(location.href + " #main");
                            
                                var displayFile = "displayFileList";
                                var page = "mybox";
                                var sortType = "sortByDefault"
                                displayFileList(displayFile, page, sortType);
                                
                            }
                        }
                    });
                    }
                
                }
            
            );

        
    
        }
    );




    //CHANGING Between mybox and sharebox at SideBar
    function displayBox(displayFile,page, sortType){
        $("#main").load(location.href + " #main");
        displayFileList(displayFile, page, sortType);
    }


    //Set file status 
    function setFile(num, fileID, filename){
        NUM = num;
        FILEID = fileID;
        FILENAME = filename;
    }

    //Get File status 
    function getNUM(){
        return NUM;
    }
    function getFILEID(){
        return FILEID;
    }
    function getFILENAME(){
        return FILENAME;
    }


    

    //Active ascending and descending order
    function changeOrder(type){
        
        if(order == "DESC"){
            sortType = type+"DESC";
            order = "ASC";
    
        }else{
            sortType = type+"ASC";
            order = "DESC";
        }
        return sortType;
    }

    // display the actual file list
    function displayFileList(displayFile, page, sortType){
        console.log(displayFile);
        console.log(page);
        console.log(sortType);
    
        $.ajax({
            url: 'fileInfo.php',
            type: 'GET',
            data: {displayFile: displayFile, sortType: sortType},
            dataType: 'html',
            success: function(fileList){
                $('#getFile').val('');
                $('#getFileName').text('');


                var arrowUp = "fas fa-arrow-up";
                var arrowDown = "fas fa-arrow-down";

                if(sortType == "sortByNameASC"){
                    $('#nameArrow').toggleClass(arrowUp);
                }else if(sortType == "sortByNameDESC"){
                    $('#nameArrow').toggleClass(arrowDown);
                }

                else if(sortType == "sortByTimeASC"){
                    $('#timeArrow').toggleClass(arrowUp);
                }else if(sortType == "sortByTimeDESC"){
                    $('#timeArrow').toggleClass(arrowDown);
                }

                else if(sortType == "sortByTypeASC"){
                    $('#typeArrow').toggleClass(arrowUp);
                }else if(sortType == "sortByTypeDESC"){
                    $('#typeArrow').toggleClass(arrowDown);
                }

                else if(sortType == "sortBySizeASC"){
                    $('#sizeArrow').toggleClass(arrowUp);
                }else if(sortType == "sortBySizeDESC"){
                    $('#sizeArrow').toggleClass(arrowDown);
                }

                else if(sortType == "sortByDefault"){
                    $('#nameArrow, #timeArrow, #sizeArrow, #typeArrow').toggleClass(arrowUp);
                
                }

                $('#mainContainer').append(fileList);
                
            
            
                $('#myBoxMiddle').on('click', '.off-select', function(){ 
                    $(this).siblings(".off-select").removeClass("on-select");
                    $(this).toggleClass("on-select");
                    $('#fileInfoMobile').removeClass('fileInfoMobileClose');  //toggle file infomation
                    hide = false;
                });
            
                
                $('#shareBoxMiddle').on('click', '.off-select', function(){ 
                    $(this).siblings(".off-select").removeClass("on-select");
                    $(this).toggleClass("on-select");
                    $('#fileInfoMobile').removeClass('fileInfoMobileClose');  //togle file information
                    hide = false;
                });

                
                $("#myBoxMiddle, #shareBoxMiddle").on('click','.row-file', function(){
                   

                    var rowID = $(this).prop("id");
                    var fileID = $(this).attr("value");
                    var num = rowID.slice(4);
                    var filename = document.getElementById("file_"+num).textContent;

                    setFile(num, fileID, filename);

                    var action = "";
    
                    
                    $('.overlayMobile').addClass('active');

                  
                    if(($('#'+rowID).hasClass('on-select'))){

                        if(fileID == "" || fileID == undefined){
                            action = "showFileInfoMyBox";
                            showFileInfo(filename, action);
                        } else{
                            action = "showFileInfoShareBox";
                            showFileInfo(fileID, action);
                        }
                    }else{
                        clearFileInfo();
                        setFile(em, em,em);
                    }

                    

                });


                $("#deleteModal").off('click', '#delete-btn');
                $('#deleteModal').on('click', '#delete-btn', function(){
                    var filename = getFILENAME();
                    var num = getNUM();
                    action = "deleteFile";
                    delFile(filename, action, num);

                });
        
                $('#shareModal').off('click', '#share-btn');
                $('#shareModal').on('click', '#share-btn', function(){
                        action = "shareFile";
                        var filename = getFILENAME();
                        checkUser = document.querySelector("#checkUser").value;
                        shareFile(filename, action, checkUser);
                    }
                );

                $('#main').off('click', '#previewButton, #previewButtonMobile');
                $("#previewButton,  #previewButtonMobile").on("click",
                    function(){
                        $('#fileInfoMobile').addClass('fileInfoMobileClose');
                        $('.overlayMobile').removeClass('active');
                        var fileID = getFILEID();
                        var filename = getFILENAME();
                        var num = getNUM();
                        if (num == "" || num == undefined){
                            $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                        }else{
                            $('#previewModal').modal('show');
                            if(fileID == "" || fileID == undefined){
                                action="previewFile";
                                previewFile(filename, action);
                            }else{
                                action="previewShareFile";
                                previewFile(fileID, action);
                            }
                        }
                       
                    }
                );

                     
                $('#main').off('click', '#downloadButton, #downloadButtonMobile');
                $('#main').on('click', '#downloadButton', function(){
                    var fileID = getFILEID();
                    var filename = getFILENAME();
                    var num = getNUM();
                    if (num == ""){
                        $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                    }else{

                        if(fileID == "" || fileID == undefined){
                            action = "downloadFile";
                            document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + filename;
                        } else{
                            action = "downloadShareFile";
                            document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + fileID;
                            
                        }
                    }
                    
        
                });

                $('#main').on('click', '#downloadButtonMobile', function(){
                    var fileID = getFILEID();
                    var filename = getFILENAME();
                    var num = getNUM();
                    if (num == ""){
                        $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                    }else{
                        if(fileID == "" || fileID == undefined){
                            action = "downloadFile";
                            document.getElementById("downloadButtonMobile").href = "downloadFile.php?action=" + action + "&path=" + filename;
                        } else{
                            action = "downloadShareFile";
                            document.getElementById("downloadButtonMobile").href = "downloadFile.php?action=" + action + "&path=" + fileID;
                            
                        }
                    }
                
        
                }
                );

                    
                //Click sidebar button to display the user's files (call function)
                $('#mySidebar').off('click','#gotoMyBox');
                $('#mySidebar').on('click', '#gotoMyBox',function(){
                    page = "mybox";
                    displayFile = "displayFileList";
                    sortType = "sortByDefault";           
                    displayBox(displayFile,page, sortType);
                
                });
                
                //Click sidebar button to display the shared files (call function)
                $('#mySidebar').off('click','#gotoShareBox');
                $('#mySidebar').on('click', '#gotoShareBox',function(){
                    displayFile = "displayShareFileList";
                    page = "sharebox";
                    sortType = "sortByDefault";
                    displayBox(displayFile,page, sortType);
                    
                });


                $('#sortFile').off('click', '#sortByName');
                $('#sortFile').on('click', '#sortByName', function(e){
                    e.preventDefault();
                    var type = "sortByName";
                    sortType = changeOrder(type);
                    displayBox(displayFile, page, sortType);
                });

                $('#sortFile').off('click', '#sortByTime');
                $('#sortFile').on('click', '#sortByTime', function(e){
                    e.preventDefault();
                    var type = "sortByTime";
                    sortType = changeOrder(type);
                    displayBox(displayFile, page, sortType);
                });

                $('#sortFile').off('click', '#sortByType');
                $('#sortFile').on('click', '#sortByType', function(e){
                    e.preventDefault();
                    var type = "sortByType";
                    sortType = changeOrder(type);
                    displayBox(displayFile, page, sortType);
                });

                $('#sortFile').off('click', '#sortBySize');
                $('#sortFile').on('click', '#sortBySize', function(e){
                    e.preventDefault();
                    var type = "sortBySize";
                    sortType = changeOrder(type);
                    displayBox(displayFile, page, sortType);
                });
                
                

            if(page == "sharebox"){
                var mybox = document.getElementById("myBoxRight");
                var myboxMobile = document.getElementById("myBoxRightMobile");
                var shareBox = document.getElementById("shareBoxRight");
                var shareBoxMobile = document.getElementById("shareBoxRightMobile");
                
                mybox.style.display = "none";
                myboxMobile.style.display = "none";
                shareBox.style.display = "flex";
                shareBoxMobile.style.display = "flex";
            
                $("#boxName").text("shareBox@");
            }else if(page == "mybox"){
                $("#boxName").text("myBox@");
            }

            

            }

        });

    }

    function clearFileInfo(){
        $('.name').empty();
        $('.type').empty();
        $('.size').empty();
        $('.timecreate').empty();
        $('.sharewith').empty();
        $('.shareby').empty();
        $('.icon').empty();

    }


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
                    $(".name").text(return_arr.filename);
                    $(".type").text(return_arr.filetype);
                    $(".size").text(return_arr.filesize);
                    $(".timecreate").text(return_arr.createtime);

                        var shareUser = return_arr.shared_users;
                        if(shareUser.trim() == ''){
                            $(".sharewith").text("");
                        }else{
                            $(".sharewith").text(shareUser.slice(0,-1));
                        }
                }else if(act == "showFileInfoShareBox"){
                    $(".name").text(return_arr.filename);
                    $(".type").text(return_arr.filetype);
                    $(".size").text(return_arr.filesize);
                    $(".timecreate").text(return_arr.createtime);
                    $(".shareby").text(return_arr.username);
                }
                
            }
        });
        
    }


    //DELETE FUNCTION
    function delFile(file, action, id){
        $.ajax({
            url: 'fileInfo.php',
            type: 'post',
            data: {file: file, action: action},
            dataType: 'JSON',
            success: function(status){
                if(status == "success"){
                    $("#row_"+id).remove();
                    $("#deleteModal").modal('hide');
                    $('.overlayMobile').removeClass('active');
                    $('#fileInfoMobile').addClass('fileInfoMobileClose');
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
                $("div.message2").fadeIn(300).delay(1500).fadeOut(400);
            }
            else{
                    $('#checkUser').val('');
                    $('#shareModal').modal('hide');
            }
        }
    });
    }

})();