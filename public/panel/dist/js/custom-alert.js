function CustomAlert(type, message){
    var color = 'alert-info';
    var tag = 'Info';
    if(type == 'success'){
        tag = 'Success';
        color = 'bg-success';
    }else if (type == 'danger'){
        var tag = 'Error';
        color = 'bg-danger';
    }else if (type == 'warning'){
        var tag = 'Warning';
        color = 'bg-warning';
    }else if (type == 'info'){
        var tag = 'Info';
        color = 'bg-info';
    }
    $(document).Toasts('create', {
        class: color,
        title: tag,
        subtitle: '',
        body: message,
        autohide: true,
        delay: 5000,
    })

    // $(".section-notification").append(text);
    //
    // setTimeout(function (e){
    //     // $('.alert').css('top', 'auto').css('left', 'auto');
    //     $(".section-notification").html('');
    // },5000);
}
