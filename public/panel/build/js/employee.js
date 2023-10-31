$("#FamilyTable, #ExperienceTable, #EducationTable, #BankTable, #DocumentTable, #LanguageTable, #ReferenceTable").DataTable({
    "responsive": false,
    "lengthChange": true,
    "autoWidth": true
});

// Employee Family Function

$("#AddNewFamily").on('click', function() {
    $("#FamilyFormSubmit")[0].reset();
    $("#family_url").val($(this).attr("add_url"));
   $("#modal-family").modal('show');
   setTimeout(function() {$("#family_name").focus();},1000);
});
$(document).on('click', ".EditButtonFamily", function() {
    $("#family_name").val($(this).attr('full_name'));
    $("#family_relation").val($(this).attr('relation'));
    $("#family_age").val($(this).attr('age'));
    $("#family_occupation").val($(this).attr('occupation'));
    $("#family_mobile").val($(this).attr('mobile'));
    $("#family_url").val($(this).attr("edit_url"));
    $("#modal-family").modal('show');
    setTimeout(function() {$("#family_name").focus();},1000);
});

$("#FamilyFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#family_url").val();
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
                    if('family_relation' in res.errors){$("#family_relation").focus();}
                    if('family_name' in res.errors){$("#family_name").focus();}
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
                    var text = '<tr id="fml_row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['name']+"</td>";
                    text += "<td>"+data.data.data['relation']+"</td>";
                    text += "<td>"+data.data.data['age']+"</td>";
                    text += "<td>"+data.data.data['occupation']+"</td>";
                    text += "<td>"+data.data.data['mobile']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonFamily" full_name="'+data.data.data["name"]+'" relation="'+data.data.data["relation"]+'" age="'+data.data.data["age"]+'" occupation="'+data.data.data["occupation"]+'" mobile="'+data.data.data["mobile"]+'" edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="FAMILYDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#fml_row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#FamilyTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-family").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.FAMILYDELETEFORM',(function(e) {
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
                    $('#fml_row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));

// #####################################################################################################
// Employee Experience Function

$("#AddNewExperience").on('click', function() {
    $("#ExperienceFormSubmit")[0].reset();
    $("#experience_url").val($(this).attr("add_url"));
    $("#modal-experience").modal('show');
    setTimeout(function() {$("#experience_company").focus();},1000);
});
$(document).on('click', ".EditButtonExperience", function() {
    $("#experience_company").val($(this).attr('company'));
    $("#experience_year").val($(this).attr('year'));
    $("#experience_details").val($(this).attr('details'));
    $("#experience_url").val($(this).attr("edit_url"));
    $("#modal-experience").modal('show');
    setTimeout(function() {$("#experience_company").focus();},1000);
});

$("#ExperienceFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#experience_url").val();
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
                    if('experience_year' in res.errors){$("#experience_year").focus();}
                    if('experience_company' in res.errors){$("#experience_company").focus();}
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
                    var text = '<tr id="exp_row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['company']+"</td>";
                    text += "<td>"+data.data.data['year']+"</td>";
                    text += "<td>"+data.data.data['details']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonExperience" company="'+data.data.data["company"]+'" year="'+data.data.data["year"]+'" details="'+data.data.data["details"]+'" edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="EXPERIENCEDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#exp_row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#ExperienceTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-experience").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.EXPERIENCEDELETEFORM',(function(e) {
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
                    $('#exp_row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));

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
    $("#education_institute").val($(this).attr('institute'));
    $("#education_place").val($(this).attr('place'));
    $("#education_year").val($(this).attr('year'));
    $("#education_scu").val($(this).attr('scu'));
    $("#education_result").val($(this).attr('result'));
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
                    var text = '<tr id="edu_row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['qualification']+"</td>";
                    text += "<td>"+data.data.data['institute']+"</td>";
                    text += "<td>"+data.data.data['place']+"</td>";
                    text += "<td>"+data.data.data['year']+"</td>";
                    text += "<td>"+data.data.data['scu']+"</td>";
                    text += "<td>"+data.data.data['result']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonEducation" qualification="'+data.data.data["qualification"]+'" institute="'+data.data.data["institute"]+'" place="'+data.data.data["place"]+'" year="'+data.data.data["year"]+'" scu="'+data.data.data["scu"]+'" result="'+data.data.data["result"]+'" edit_url="'+data.data.edit_url+'">\n' +
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
                        $('#edu_row_id'+data.data.data["id"]).replaceWith(text);
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
                    $('#edu_row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));


// #####################################################################################################
// Employee Bank Function

$("#AddNewBank").on('click', function() {
    $("#BankFormSubmit")[0].reset();
    $("#bank_url").val($(this).attr("add_url"));
    $("#modal-bank").modal('show');
    setTimeout(function() {$("#bank_qualification").focus();},1000);
});
$(document).on('click', ".EditButtonBank", function() {
    $("#bank_ac_name").val($(this).attr('ac_name'));
    $("#bank_ac_no").val($(this).attr('ac_no'));
    $("#bank_ac_type").val($(this).attr('ac_type'));
    $("#bank_name").val($(this).attr('bank'));
    $("#bank_branch").val($(this).attr('bank_branch'));
    $("#bank_ifsc_code").val($(this).attr('ifsc_code'));
    $("#bank_ifsc_ifsc_code").val($(this).attr('ifsc_ifsc_code'));
    if($(this).attr('is_primary') == 1){
        $("#is_primary").prop('checked',true).change();
    }
    $("#bank_url").val($(this).attr("edit_url"));
    $("#modal-bank").modal('show');
    setTimeout(function() {$("#bank_qualification").focus();},1000);
});

$("#BankFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#bank_url").val();
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
                    if('ifsc_code' in res.errors){$("#ifsc_code").focus();}
                    if('bank_branch' in res.errors){$("#bank_branch").focus();}
                    if('bank_name' in res.errors){$("#bank").focus();}
                    if('bank_ac_type' in res.errors){$("#bank_ac_type").focus();}
                    if('bank_ac_no' in res.errors){$("#bank_ac_no").focus();}
                    if('bank_ac_name' in res.errors){$("#bank_ac_name").focus();}
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
                    var text = '<tr id="bank_row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['ac_name']+"</td>";
                    text += "<td>"+data.data.data['ac_no']+"</td>";
                    text += "<td>"+data.data.data['ac_type']+"</td>";
                    text += "<td>"+data.data.data['bank']+"</td>";
                    text += "<td>"+data.data.data['bank_branch']+"</td>";
                    text += "<td>"+data.data.data['ifsc_code']+"</td>";
                    text += "<td>"+data.data.data['is_primary_value']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonBank" ac_name="'+data.data.data["ac_name"]+'" ac_no="'+data.data.data["ac_no"]+'" ac_type="'+data.data.data["ac_type"]+'" bank="'+data.data.data["bank"]+'" bank_branch="'+data.data.data["bank_branch"]+'" ifsc_code="'+data.data.data["ifsc_code"]+'" is_primary="'+data.data.data["is_primary"]+'" edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="BANKDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#bank_row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#BankTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-bank").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.BANKDELETEFORM',(function(e) {
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
                    $('#bank_row_id'+data.data.row_id).remove();
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
                    var text = '<tr id="doc_row_id'+data.data.data["id"]+'">';
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
                        $('#doc_row_id'+data.data.data["id"]).replaceWith(text);
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
                    $('#doc_row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));

// #####################################################################################################
// Employee Language Function

$("#AddNewLanguage").on('click', function() {
    $("#LanguageFormSubmit")[0].reset();
    $("#language_url").val($(this).attr("add_url"));
    $("#modal-language").modal('show');
    setTimeout(function() {$("#language_name").focus();},1000);
});
$(document).on('click', ".EditButtonLanguage", function() {
    $("#language_name").val($(this).attr('language'));
    if($(this).attr('lang_speak') == 1){
        $("#lang_speak").prop('checked',true).change();
    }
    if($(this).attr('lang_read') == 1){
        $("#lang_read").prop('checked',true).change();
    }
    if($(this).attr('lang_write') == 1){
        $("#lang_write").prop('checked',true).change();
    }
    $("#language_url").val($(this).attr("edit_url"));
    $("#modal-language").modal('show');
    setTimeout(function() {$("#language_name").focus();},1000);
});

$("#LanguageFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#language_url").val();
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
                    if('language_name' in res.errors){$("#language_name").focus();}
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
                    var text = '<tr id="lang_row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['language']+"</td>";
                    text += "<td>"+data.data.data['lang_speak_value']+"</td>";
                    text += "<td>"+data.data.data['lang_read_value']+"</td>";
                    text += "<td>"+data.data.data['lang_write_value']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonLanguage" language="'+data.data.data["language"]+'" lang_speak="'+data.data.data["lang_speak"]+'" lang_read="'+data.data.data["lang_read"]+'" lang_write="'+data.data.data["lang_write"]+'" edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="LANGUAGEDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#lang_row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#LanguageTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-language").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.LANGUAGEDELETEFORM',(function(e) {
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
            processData:false,        // To send DOMLanguage or non processed data file it is set to false
            error: function(xhr, status, error) {
                CustomAlert('warning', 'The data was used on other tables, so it can not be deleted.');
            },
            success: function(data)   // A function to be called if request succeeds
            {
                if(data['status']){
                    $('#lang_row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));


// #####################################################################################################
// Employee Reference Function

$("#AddNewReference").on('click', function() {
    $("#ReferenceFormSubmit")[0].reset();
    $("#reference_url").val($(this).attr("add_url"));
    $("#modal-reference").modal('show');
    setTimeout(function() {$("#reference_name").focus();},1000);
});
$(document).on('click', ".EditButtonReference", function() {
    $("#reference_name").val($(this).attr('reference_name'));
    $("#reference_mobile").val($(this).attr('reference_mobile'));
    $("#reference_relation").val($(this).attr('reference_relation'));
    $("#reference_remarks").val($(this).attr('reference_remarks'));

    $("#reference_url").val($(this).attr("edit_url"));
    $("#modal-reference").modal('show');
    setTimeout(function() {$("#reference_name").focus();},1000);
});

$("#ReferenceFormSubmit").on('submit',function(e){
    if(e.isDefaultPrevented()){
    }else{
        var url = $("#reference_url").val();
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
                    if('reference_name' in res.errors){$("#reference_name").focus();}
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
                    var text = '<tr id="ref_row_id'+data.data.data["id"]+'">';
                    text += "<td>"+data.data.data['name']+"</td>";
                    text += "<td>"+data.data.data['mobile']+"</td>";
                    text += "<td>"+data.data.data['relation']+"</td>";
                    text += "<td>"+data.data.data['remarks']+"</td>";
                    text += "<td>";
                    text += '<button type="button" class="btn-transparent EditButtonReference" reference_name="'+data.data.data["name"]+'" reference_mobile="'+data.data.data["mobile"]+'" reference_relation="'+data.data.data["relation"]+'" reference_remarks="'+data.data.data["remarks"]+'" edit_url="'+data.data.edit_url+'">\n' +
                        '                        <i class="fa fa-edit text-success"></i>\n' +
                        '                    </button>';
                    text += "</td>";
                    text += "<td>";
                    text += '<form class="REFERENCEDELETEFORM" style="display:inline-block;">\n' +
                        '<input type="hidden" name="_token" value="'+data.data.csrf_token+'">\n' +
                        '                        <input type="hidden" name="url" value="'+data.data.delete_url+'">\n' +
                        '                        <button type="submit" class="btn-transparent">\n' +
                        '                            <i class="fa fa-trash text-danger"></i>\n' +
                        '                        </button>\n' +
                        '                    </form>';
                    text += "</td>";

                    if(data.data.task =='Edit'){
                        $('#ref_row_id'+data.data.data["id"]).replaceWith(text);
                    }else{
                        $("#ReferenceTable tbody").append(text);
                    }
                    CustomAlert('success', data.message);
                    $("#modal-reference").modal('hide');
                }else{
                    CustomAlert('danger', data.message);
                    $("#code").focus();
                }
            }
        });
    }
});

$(document).on('submit', '.REFERENCEDELETEFORM',(function(e) {
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
            processData:false,        // To send DOMReference or non processed data file it is set to false
            error: function(xhr, status, error) {
                CustomAlert('warning', 'The data was used on other tables, so it can not be deleted.');
            },
            success: function(data)   // A function to be called if request succeeds
            {
                if(data['status']){
                    $('#ref_row_id'+data.data.row_id).remove();
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            }
        });
    }
}));
