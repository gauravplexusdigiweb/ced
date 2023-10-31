$("#EducationTable, #DocumentTable").DataTable({
    "responsive": false,
    "lengthChange": true,
    "autoWidth": true
});

// #####################################################################################################
// Employee Education Function

$("#AddNewEducation").on('click', function() {
    $("#EducationFormSubmit")[0].reset();
    $("#education_url").val($(this).attr("add_url"));
    $("#modal-education").modal('show');
    setTimeout(function() {$("#education_qualification").focus();},1000);
});
$(document).on('click',".EditButtonEducation", function() {
    $("#education_qualification").val($(this).attr('qualification'));
    $("#education_main_subject").val($(this).attr('main_subject'));
    $("#education_year").val($(this).attr('year'));
    $("#education_scu").val($(this).attr('scu'));
    $("#education_url").val($(this).attr("edit_url"));
    $("#modal-education").modal('show');
    setTimeout(function() {$("#education_qualification").focus();},1000);
});

$("#EducationFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#education_url").val();
        e.preventDefault();
        $.ajax({
            url : url,
            type : 'post',
            data : new FormData(this),
            contentType : false,
            cache : false,
            processData : false,
            error: function(xhr, status, error) {
                if(xhr.status == 422){
                    var res = JSON.parse(xhr.responseText);
                    var text = '';
                    if('education_result' in res.errors){$("#education_result").focus();}
                    if('education_institute' in res.errors){$("#education_institute").focus();}
                    if('education_qualification' in res.errors){$("#education_qualification").focus();}
                    $.each(res.errors, function (key, value) {
                        $.each(value, function (k, v) {
                            text += v+'<br>';
                        });
                    });
                    CustomAlert('warning', text);
                }else{
                    CustomAlert('warning', JSON.parse(xhr.responseText).message);
                }
            },
            success : function (data) {
                if(data['status']){
                    var text = '<tr id="row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['qualification']+"</td>";
                    text += "<td>"+data.data.data['main_subject']+"</td>";
                    text += "<td>"+data.data.data['year']+"</td>";
                    text += "<td>"+data.data.data['scu']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonEducation" qualification="'+data.data.data["qualification"]+'" main_subject="'+data.data.data["main_subject"]+'" year="'+data.data.data["year"]+'" scu="'+data.data.data["scu"]+'" edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="EDUCATIONDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#EducationTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-education").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.EDUCATIONDELETEFORM',(function(e) {
    //console.log(e);
    if(e.isDefaultPrevented())
    {}
    else
    {
        if(!confirm('Are sure you want to delete ?')){
            return false;
        }
        var url = $(this).find("input[name=url]").val();
        e.preventDefault();
        $.ajax({
            url:url, // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data:new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache:false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            error: function(xhr, status, error) {
                CustomAlert('warning', 'The data was used on other tables, so it can not be deleted.');
            },
            success: function(data)   // A function to be called if request succeeds
            {
                if(data['status']){
                    $('#row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));


// #####################################################################################################
// Employee Document Function

$("#AddNewDocument").on('click', function() {
    $("#DocumentFormSubmit")[0].reset();
    $("#document_url").val($(this).attr("add_url"));
    $("#modal-document").modal('show');
    setTimeout(function() {$("#document_category").focus();},1000);
});
$(document).on('click', ".EditButtonDocument", function() {
    $("#document_category").val($(this).attr('category')).trigger("change");
    $("#document_url").val($(this).attr("edit_url"));
    $("#modal-document").modal('show');
    setTimeout(function() {$("#document_category").focus();},1000);
});

$("#DocumentFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#document_url").val();
        e.preventDefault();
        $.ajax({
            url : url,
            type : 'post',
            data : new FormData(this),
            contentType : false,
            cache : false,
            processData : false,
            error: function(xhr, status, error) {
                if(xhr.status == 422){
                    var res = JSON.parse(xhr.responseText);
                    var text = '';
                    if('document_file' in res.errors){$("#document_file").focus();}
                    if('document_category' in res.errors){$("#document_category").focus();}
                    $.each(res.errors, function (key, value) {
                        $.each(value, function (k, v) {
                            text += v+'<br>';
                        });
                    });
                    CustomAlert('warning', text);
                }else{
                    CustomAlert('warning', JSON.parse(xhr.responseText).message);
                }
            },
            success : function (data) {
                if(data['status']){
                    var text = '<tr id="row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['category']+"</td>";
                    text += "<td>"+data.data.data['created']+"</td>";
                    text += "<td>";
                    text += '<a target="_blank" href="'+data.data.image+'">\n' +
                        '                    <i class="fa fa-file"></i>\n' +
                        '                    </a>';
                    text += "</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonDocument" category="'+data.data.data["category_id"]+'" file="'+data.data.data["file"]+'"  edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="DOCUMENTDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#DocumentTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-document").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.DOCUMENTDELETEFORM',(function(e) {
    //console.log(e);
    if(e.isDefaultPrevented())
    {}
    else
    {
        if(!confirm('Are sure you want to delete ?')){
            return false;
        }
        var url = $(this).find("input[name=url]").val();
        e.preventDefault();
        $.ajax({
            url:url, // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data:new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache:false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            error: function(xhr, status, error) {
                CustomAlert('warning', 'The data was used on other tables, so it can not be deleted.');
            },
            success: function(data)   // A function to be called if request succeeds
            {
                if(data['status']){
                    $('#row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));
