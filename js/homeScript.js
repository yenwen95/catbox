(function(){
    
    //Problem: create function let user delete their account
    //Problem: if it can store other language file or not
    //Problem: click "no" or "close" button should reset the selected file, so that it no need to click twice to reselect for mobile view
    //Problem: create function allow user to change file name before upload to the system
    

        var order, FILEID, NUM, FILENAME, em = "";
        var idleTime = 0;
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

            $('#main').on('click', '#removeFromVaultButton, #removeFromVaultButtonMobile', function(){
                $('#fileInfoMobile').addClass('fileInfoMobileClose');
                $('.overlayMobile').removeClass('active');
                var filename = getFILENAME();
                var num = getNUM();

                if (num == ""){
                    $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                }else{
                    $("#tobeRemoved").text(filename);
                    $('#removefromvaultModal').modal('show');
                }
               
            });

            
            
            $('#main').on('click', '#addToVaultButton, #addToVaultButtonMobile', function(){
                $('#fileInfoMobile').addClass('fileInfoMobileClose');
                $('.overlayMobile').removeClass('active');
                var filename = getFILENAME();
                var num = getNUM();
                if (num == ""){
                    $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                }else{
                    $("#tobeAddedToVault").text(filename);
                    $('#addtovaultModal').modal('show');
                }
            });

            $('#main').on('click', '#emptybinButton', function(){
                $('#emptybinModal').modal('show');
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
            $("#boxName").text("--MyFiles");
            displayFileList(displayFile, page, sortType);

            document.querySelector('#getFile').onchange = function(){
                document.querySelector('#getFileName').textContent = this.files[0].name;
            }
        
            $('#uploadModal').on('click', '#uploadButton', function(){
                var state = "mybox";
                var files = $('#getFile')[0].files[0];
                if(files.size < 10485760){
                    uploadFile(state);
                }else{
                    $("div.message1").removeClass("alert-success");
                    $("div.message1").addClass('alert-danger');
                    $("div.message1").text("File size is too big!");
                    $("div.message1").fadeIn(300).delay(1500).fadeOut(400);
                }
            });

            $('#uploadModal').on('click', '#uploadToVaultButton', function(){
                var state = "vault";
                var files = $('#getFile')[0].files[0];
                if(files.size < 10485760){
                    uploadFile(state);
                }else{
                    $("div.message1").removeClass("alert-success");
                    $("div.message1").addClass('alert-danger');
                    $("div.message1").text("File size is too big!");
                    $("div.message1").fadeIn(300).delay(1500).fadeOut(400);
                }
            });

            //CHECK IDLE TIME
            let idleInterval = setInterval(timerIncrement, 1000);

            $(this).on('mousemove',function(e){
                idleTime = 0;
            });
            $(this).on('keypress',function(e){
                idleTime = 0;
            });


            
    
        }
    );



    function uploadFile(state){
        var action = "uploadFile";
        var formData = new FormData();
        var files = $('#getFile')[0].files;
      
        if(files.length > 0){
            
            formData.append('getFile', files[0]);
            formData.append('action',action);
            formData.append('state',state);

            $.ajax({
                url: 'fileInfo.php',
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('.overlayLoading').addClass('active');
                    $("#loading").show();
                    
                },
                success: function(status){
                    $('.overlayLoading').removeClass('active');
                    $("#loading").hide();
                    console.log(status);
                        if(status == "success"){
                            if($("div.message1").hasClass("alert-danger")){
                                $("div.message1").removeClass("alert-danger");
                                }
                            $("div.message1").addClass('alert-success');
                            $("div.message1").text("File uploaded successfully!");
                            $("div.message1").fadeIn(300).delay(1500).fadeOut(400);
    
                                $("#main").load(location.href + " #main");
                            
                            
                            var sortType = "sortByDefault"
                            if(state == "mybox"){
                                var displayFile = "displayFileList";
                                var page = "mybox";
                                
                                displayFileList(displayFile, page, sortType);
                            }else{
                                var displayFile = "displayVaultFileList";
                                displayFileList(displayFile, state, sortType);
                            }
                        }else{
                            if($("div.message1").hasClass("alert-success")){
                                $("div.message1").removeClass("alert-success");
                                }
                            $("div.message1").addClass('alert-danger');
                            $("div.message1").text("Failed to upload file!");
                            $("div.message1").fadeIn(300).delay(1500).fadeOut(400);
                        }
                }
            });
        }
    

            

           
    }

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
            beforeSend: function(){
                $('.overlayLoading').addClass('active');
                $("#loading").show();
            },
            success: function(fileList){
                 //Refresh the file list
                 $('#mainContainer').append(fileList);

                if(page == "sharebox"){
                    $('#myBoxRightMobile, #myBoxRight').hide();
                    $('#shareBoxRight, #shareBoxRightMobile').show();

                    $('#addButton, #closeVaultButton, #emptybinButton').removeClass("d-flex");
                    $('#shareButton, #addToVaultButton,#removeFromVaultButton, #delButton, #delButtonMobile, #restoreButton, #restoreButtonMobile').removeClass("d-md-flex");
                    $('#shareButton, #delButton,#delButtonMobile, #shareButtonMobile, #addToVaultButton,#removeFromVaultButton, #closeVaultButton, #addButton, #addToVaultButtonMobile, #removeFromVaultButtonMobile,#emptybinButton, #closeVaultButtonMobile, #restoreButton, #restoreButtonMobile ').addClass("d-none");

                    $('#buttonrow').removeClass("w-50");
                    $('#buttonrow').addClass("w-25");

             
                    $('#sortByDeletedTime').attr('id', 'sortByTime');
                    $('#labelSort').text('Created At ');
                    $("#boxName").text("--SharedFiles");
                }else if(page == "mybox"){
                    $('#shareBoxRight, #shareBoxRightMobile').hide();
                    $('#myBoxRightMobile,#myBoxRight').show();
                 

                    $("#boxName").text("--MyFiles");
                    $('#labelSort').text('Created At ');
                    $('#uploadToVaultButton').attr('id', 'uploadButton');
                    $('#sortByDeletedTime').attr('id', 'sortByTime');
               

                    $('#closeVaultButton, #emptybinButton').removeClass("d-flex");
                    $('#removeFromVaultButton, #delButton, #delButtonMobile, #restoreButton, #restoreButtonMobile').removeClass("d-md-flex");
                    $('#closeVaultButton, #delButton, #delButtonMobile, #closeVaultButtonMobile, #removeFromVaultButton, #removeFromVaultButtonMobile, #emptybinButton, #restoreButton, #restoreButtonMobile').addClass("d-none");
                  
                 
                 

                }else if (page == "vault"){
                    $("#boxName").text("--Vault");
                    $('#sortByDeletedTime').attr('id', 'sortByTime');
                    $('#labelSort').text('Created At ');
                    $('#uploadButton').attr('id', 'uploadToVaultButton');
         

                    $('#shareBoxRight, #shareBoxRightMobile, #myBoxRightMobile,#myBoxRight').hide();
                    $('#emptybinButton').removeClass('d-flex');
                    $('#shareButton, #addToVaultButton,#delButton, #delButtonMobile, #restoreButton, #restoreButtonMobile').removeClass("d-md-flex");
                    $('#shareButton, #shareButtonMobile, #delButton, #delButtonMobile,#addToVaultButton, #addToVaultButtonMobile, #restoreButton,#restoreButtonMobile, #emptybinButton').addClass("d-none");
                  
                   
                }else if(page == "bin"){
                    $("#boxName").text("--Bin");
                    $('#shareBoxRight, #shareBoxRightMobile').hide();
                    $('#myBoxRightMobile,#myBoxRight ').show();
       

                    $('#removeButton,#removeButtonMobile, #shareButton, #shareButtonMobile, #downloadButton, #downloadButtonMobile, #previewButton,#previewButtonMobile, #addToVaultButton, #addToVaultButtonMobile, #removeFromVaultButton, #removeFromVaultButtonMobile').removeClass("d-md-flex");
                    $('#addButton, #closeVaultButton').removeClass('d-flex');
                    $('#addButton, #removeButton,#removeButtonMobile, #shareButton, #shareButtonMobile, #downloadButton,#downloadButtonMobile, #previewButton,#previewButtonMobile, #addToVaultButton, #addToVaultButtonMobile, #removeFromVaultButton, #removeFromVaultButtonMobile, #closeVaultButton').addClass("d-none");
                   
                    

                    $('#sortByTime').attr('id', 'sortByDeletedTime');
                    $('#labelSort').text('Deleted At');
                    $('#buttonrow').removeClass("w-50");
                    $('#buttonrow').addClass("w-25");
                }

                if($('#myBoxMiddle').children().length<=0){
                    $('#myBoxMiddle').append("<div class='mt-5 ml-3 mr-5 p-5 styled-box'>Your box is empty, try to add some files!</div>");
                }
                if($('#vaultMiddle').children().length<=0){
                    $('#vaultMiddle').append("<div class='mt-5 ml-3 mr-5 p-5 styled-box'>Your vault is empty, try to add some files!</div>");
                }
                if($('#shareBoxMiddle').children().length<=0){
                    $('#shareBoxMiddle').append("<div class='mt-5 ml-3 mr-5 p-5 styled-box'>You have no shared files....</div>");
                }

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

              
                $('#myBoxMiddle, #vaultMiddle, #shareBoxMiddle, #binMiddle').on('click', '.off-select', function(){ 
                    $(this).siblings(".off-select").removeClass("on-select");
                    $(this).toggleClass("on-select");
                    $('#fileInfoMobile').removeClass('fileInfoMobileClose');  //toggle file infomation
                    hide = false;
                });
            
                
                $("#myBoxMiddle, #shareBoxMiddle, #vaultMiddle, #binMiddle").on('click','.row-file', function(){
                   

                    var rowID = $(this).prop("id");
                    var fileID = $(this).attr("value");
                    var num = rowID.slice(4);
                    var filename = document.getElementById("file_"+num).textContent;

                    setFile(num, fileID, filename);

                    var action = "";
    
                    
                    $('.overlayMobile').addClass('active');

                  
                    if(($('#'+rowID).hasClass('on-select'))){
                            action = "showFileInfo";
                            showFileInfo(fileID, action);
                        
                    }else{
                        clearFileInfo();
                        setFile(em, em,em);
                        $('#fileInfoMobile').removeClass('fileInfoMobileClose');
                    }

                    

                });


                $("#deleteModal").off('click', '#delete-btn');
                $('#deleteModal').on('click', '#delete-btn', function(){
                  var filename = getFILENAME();
                    var num = getNUM();
                    var fileID = getFILEID();
                    if(page == 'bin'){
                        action = "deletePermanentlyFile";
                        delFile(fileID, filename, action, num);
                    }else if(page == 'sharebox'){
                        action = "deleteSharedFile";
                        delFile(fileID, filename, action, num);
                    }
                       

                        
                });

                $("#removefromvaultModal").off('click', '#removefromvault-btn');
                $('#removefromvaultModal').on('click', '#removefromvault-btn', function(){
                    var filename = getFILENAME();
                    var num = getNUM();
                   
                    action = "removeFromVault";
                    removeFromVault(filename, action, num);
           

                });

                $("#addtovaultModal").off('click', '#addtovault-btn');
                $('#addtovaultModal').on('click', '#addtovault-btn', function(){
                    var filename = getFILENAME();
                    var num = getNUM();
                    action = "addtovault";
                    addToVault(filename, action, num);
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
                      
                        var num = getNUM();
                        if (num == "" || num == undefined){
                            $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                        }else{
                            $('#previewModal').modal('show');
                                action="previewFile";
                                previewFile(fileID, action);
                            
                        }
                       
                    }
                );

                     
                $('#main').off('click', '#downloadButton, #downloadButtonMobile');
                $('#main').on('click', '#downloadButton, #downloadButtonMobile', function(){
                    var fileID = getFILEID();
                    var num = getNUM();
                    if (num == ""){
                        $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                    }else{
                            action = "downloadFile";
                            document.getElementById("downloadButton").href = "downloadFile.php?action=" + action + "&path=" + fileID;
                            document.getElementById("downloadButtonMobile").href = "downloadFile.php?action=" + action + "&path=" + fileID;
                    }
                    
        
                });

            

                $('#main').on('click', '#closeVaultButton', function(){
                    action = "closeVault";
                    var state = "manual";
                    closeVault(action, state);
                });

                $('#main').on('click', '#removeButton, #removeButtonMobile', function(){
                    action = "movetoBin";
                    $('#fileInfoMobile').addClass('fileInfoMobileClose');
                    $('.overlayMobile').removeClass('active');
                    var filename = getFILENAME();
                    var num = getNUM();

                        if (num == "" || num == undefined){
                            $("div.message3").fadeIn(300).delay(1500).fadeOut(400);
                        }else{
                            if(page == 'sharebox'){
                                $('#deleteModal').modal('show');
                             } else{
                                 moveToBin(filename, action, page, num);
                            }
                        }

                
                    

                    
                });

                $('#emptybinModal').on('click', '#emptybin-btn', function(){
                    action = "emptybin";
                    emptyBin(action);
                });

                $('#main').on('click', '#restoreButton, #restoreButtonMobile', function(){
                    action = "restorefile";
                    var fileID = getFILEID();
                    var num = getNUM();
                    restoreFile(fileID, action, num);
                });
         

                    
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

                //Show vault model to get and submit otp
                $('#mySidebar').off('click','#gotoVault');
                $('#mySidebar').on('click', '#gotoVault', function(){
                    //check vault is open or not
                    action = "checkVault";
                   
                    checkVault(action);
                 
                });

                $('#mySidebar').off('click', '#gotoRecycleBin');
                $('#mySidebar').on('click', '#gotoRecycleBin', function(){
                    displayFile = "displayRecycleBin";
                    page = "bin";
                    sortType = "sortByDefault";
                    displayBox(displayFile, page, sortType);
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
              
             

            },
            complete: function(){
                 $('.overlayLoading').removeClass('active');
                $("#loading").hide();
            }

        });

    }
   //REMOVE FROM VAULT
   function removeFromVault(filename, action, num){
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {filename: filename, action: action},
        dataType: 'JSON',
        success: function(status){
            console.log(status);
            if(status == "success"){
              
                $("#row_"+num).remove();
                $("#removefromvaultModal").modal('hide');
               
            }else{
                console.log("fail");
            }
        }
    });
   }

    //check vault status
    function checkVault(action){
        $.ajax({
            url: 'otpController.php',
            type: 'post',
            data: {action: action},
            dataType: 'JSON',
            success: function(status){
                
                //if open, show file
                if(status == "1"){
                    displayFile = "displayVaultFileList";
                    page = "vault";
                    sortType = "sortByDefault";
                    displayBox(displayFile,page, sortType);

                }else if(status == "0"){
                    //if close, call modal to get OTP
                    $('#vaultModal').modal('show');
                    console.log(status);
                    
                    $('#vaultModal').on('click', '#getOTP-btn', function(){
                        action = "sendOTP";
                        sendOTP(action);
                    });

                    $('#vaultModal').on('click', '#submitOTP-btn', function(){
                        action = "submitOTP";
                        otpPass = document.querySelector("#otpPass").value;
                        console.log(action);
                        submitOTP(action, otpPass);
                    });
                    
                }else{
                    console.log(status);
                }
            }

        });
        
    }

    //VAULT FUNCTION
    function addToVault(file, action, id){
    $.ajax({
        url: 'fileInfo.php',
        type: 'post',
        data: {file: file, action: action},
        dataType: 'JSON',
        success: function(status){
            if(status == "success"){
                $("#row_"+id).remove();
                $("div.message5").fadeIn(300).delay(1500).fadeOut(400);
                setTimeout(function(){
                    $('#addtovaultModal').modal('hide')
                  }, 2000);
              
            }else{
                console.log("fail");
            }
        }
    });
    }

    //Increase timer
    function timerIncrement(){
        idleTime = idleTime + 1;
    
        if(idleTime > 900){
            //close the vault
            //Problem: check otp, need to change otp because time is still valid
           $action = "checkVault";
            $.ajax({
                    url: 'otpController.php',
                    type: 'post',
                    data: {action: action},
                    dataType: 'JSON',
                    success: function(status){
                        if(status == '1'){
                            action = "closeVault";
                            var state = "auto";
                            closeVault(action, state);
                        }
                }
            });

           
           
        }
    }

    function closeVault(action, state){
    
        $.ajax({
            url: 'fileInfo.php',
            type: 'post',
            data: {action: action},
            dataType: 'JSON',
            success: function(status){
                if(status == "success"){
                    if(state == "auto"){
                    
                        $('#messageModal').modal('show');
    
                        setTimeout(function(){
                            $('#messageModal').modal('hide')
                          }, 10000);
    
                          setTimeout(function(){
                            window.location.reload();
                          }, 10000);
                          
                    }else if(state == "manual"){
                        window.location.reload();
                    }
                }else{
                    console.log("fail");
                }
               
                

            }
        });

    }

    //Send OTP
    function sendOTP(action){
        $.ajax({
            url: 'otpController.php',
            type: 'post',
            data: {action: action},
            dataType: 'JSON',
            beforeSend: function(){
                $('.overlayLoading').addClass('active');
                $("#loading").show();
            },
            success:function(status){
                console.log(status);
                if($("div.message4").hasClass("alert-danger")){
                   $("div.message4").removeClass("alert-danger");
                }
                $("div.message4").addClass('alert-success');
                $("div.message4").text("OTP has sent! Check your email! Your OTP will be valid for 5 minutes!");
                $("div.message4").fadeIn(300).delay(3000).fadeOut(400);
            },
            complete: function(){
                $('.overlayLoading').removeClass('active');
                 $("#loading").hide();
            }
        });
    }

    //submit OTP
    function submitOTP(action, otpPass){
        $.ajax({
            url: 'otpController.php',
            type: 'post',
            data: {action: action, otpPass: otpPass},
            dataType: 'JSON',
            success: function(status){
                console.log(status);
                if(status == "1" || status == "2" || status == "3" || status == "4"){
                    if($("div.message4").hasClass("alert-success")){
                        $("div.message4").removeClass("alert-success");
                     }
                    $("div.message4").addClass("alert-danger");
                    if(status == "1"){
                        $("div.message4").text("OTP number is needed");
                    }else if(status == "2"){
                        $("div.message4").text("Wrong OTP Number!");
                    }else if(status == "3"){
                        $("div.message4").text("OTP has expired!");
                    }else{
                        $("div.message4").text("OTP has been used!");
                    }
                 
                }else if(status == "5"){
                    if($("div.message4").hasClass("alert-danger")){
                        $("div.message4").removeClass("alert-danger");
                     }
                        $("div.message4").addClass('alert-success');
                        $("div.message4").text("OTP is valid!");

                        //hide modal after 3 seconds
                        setTimeout(function(){
                            $('#vaultModal').modal('hide')
                          }, 2000);

                        displayFile = "displayVaultFileList";
                        page = "vault";
                        sortType = "sortByDefault";
                        displayBox(displayFile,page, sortType);

                }
                $("div.message4").fadeIn(300).delay(3000).fadeOut(400);
              
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
                var shortType = return_arr['shortType'];
                var path = return_arr['path'];
                var type = return_arr['filetype'];
                var imgbox = document.getElementById("previewImg");
                var framebox = document.getElementById("previewFrame");
                var videobox = document.getElementById("previewVideo");
                var audiobox = document.getElementById("previewAudio");
                imgbox.style.display = "none";
                framebox.style.display = "none";
                videobox.style.display = "none";
                audiobox.style.display = "none";
           
         
                
                if(shortType == "Image" ){  
                    //use <img>
                    videobox.style.display = "none";
                    framebox.style.display = "none";
                    audiobox.style.display = "none";
                    imgbox.style.display = "block";
                 
                    $('#previewImg').attr("src", src="./" + path);
                }else if(shortType == "Video"){
                    framebox.style.display = "none";
                    imgbox.style.display = "none";
                    videobox.style.display = "block";
                    audiobox.style.display = "none";
                    $('#previewVideo').attr("src",src="./" + path);
                    $('#previewVideo').attr("type", type="video/"+type );
                   
                }else if(shortType == "Audio"){
                    framebox.style.display = "none";
                    imgbox.style.display = "none";
                    videobox.style.display = "none";
                    audiobox.style.display = "block";
                    $('#previewAudio').attr("src", src="./" + path);
                    $('#previewAudio').attr("type", type="audio/"+type );
                }
                else{ 
                    //use <iframe>
                    imgbox.style.display = "none";
                    framebox.style.display = "block";
                    audiobox.style.display = "none";
                    videobox.style.display = "none";
                    $("#previewFrame").attr("src", src="https://docs.google.com/viewer?url=https://calibre-ebook.com/downloads/demos/demo.docx&embedded=true");
                    //$("#previewFrame").attr("src", src="https://docs.google.com/viewer?url=http://localhost/" + path + "&embedded=true");
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
        success: function(status){
            
            if(status == "success"){
                $('#checkUser').val('');
                $("div.message2").removeClass("alert-danger");
                $("div.message2").addClass('alert-success');
                $("div.message2").text("File shared successfully!");
            }else {
                $("div.message2").removeClass("alert-success");
                $("div.message2").addClass('alert-danger');
                 if(status == "no exists"){
                    $("div.message2").text("User does not exist!");
                 }else if(status == "shared"){
                    $("div.message2").text("You have shared to this user!");
                }else{
                    $("div.message2").text("Failed to share file!");
                }
            }
            $("div.message2").fadeIn(300).delay(1500).fadeOut(400);
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
                $(".name").text(return_arr.filename);
                $(".type").text(return_arr.filetype);
                $(".size").text(return_arr.filesize);
                $(".timecreate").text(return_arr.createtime);
                var user = return_arr.username;
                 var shareUser = return_arr.shared_users;
                    if(shareUser.trim() == ''){
                        $("span.sharewith").text("");
                    }else{
                        $("span.sharewith").text(shareUser.slice(0,-1));
                    }
         
                 $(".shareby").text(user);
                
                
            }
        });
        
    }


    //DELETE FUNCTION
    function delFile(fileID,filename, action, id){
        $.ajax({
            url: 'fileInfo.php',
            type: 'post',
            data: {fileID: fileID, filename: filename, action: action},
            dataType: 'JSON', 
            beforeSend: function(){
                $('.overlayLoading').addClass('active');
                $("#loading").show();
            },
            success: function(status){
                if(status == "success"){
                    $("#row_"+id).remove();
                    $("#deleteModal").modal('hide');
                    $('.overlayMobile').removeClass('active');
                    $('#fileInfoMobile').addClass('fileInfoMobileClose');
                }else{
                    console.log("fail");
                }
            },
            complete: function(){
                $('.overlayLoading').removeClass('active');
                $("#loading").hide();
            }
        });
    }

    //MOVE TO BIN FUNCTION
    function moveToBin(file, action, page, num){
        console.log("here");
        $.ajax({
            url: 'fileInfo.php',
            type: 'post',
            data: {file: file, action: action, page:page},
            dataType: 'JSON',
            success: function(status){
                if(status == "success"){
                    $("#row_"+num).remove();
                }else{
                    console.log('fail');
                }
            }
        });
    }

    function emptyBin(action){
        $.ajax({
            url: 'fileInfo.php',
            data: {action: action},
            type: 'post',
            dataType: 'json',
            beforeSend: function(){
               $("#emptybinModal").modal('hide');
                $('.overlayLoading').addClass('active');
                $("#loading").show();
        
            },
            success: function(status){
               
                $('.overlayLoading').removeClass('active');
                $("#loading").hide();
               
               if(status == "success"){
                 
                    $("#main").load(location.href + " #main");
                  
                    var displayFile = "displayRecycleBin";
                    var page = "bin";
                    var sortType = "sortByDefault";
                    displayFileList(displayFile, page, sortType);
                   
               }else{
                    console.log("fail");
               }
            }
        });
    }

    function restoreFile(fileID, action, num){
        $.ajax({
            url: 'fileInfo.php',
            data: {fileID: fileID, action: action},
            type: 'post',
            dataType: 'json',
            success: function(status){
                console.log(status);
                if(status == "success"){
                    $("#row_"+num).remove();
                    $('.overlayMobile').removeClass('active');
                    $('#fileInfoMobile').addClass('fileInfoMobileClose');
                }else{
                    console.log('fail');
                }
            }
        });
    }



 

})();
