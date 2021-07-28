$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
    return new bootstrap.Dropdown(dropdownToggleEl)
})

$(document).on('change', 'input', function () {
    var
        $this = $(this),
        val = $this.val(),
        newVal;

    newVal = val.replace(/[\u06F0-\u06F9]/g, function (digit) {
        switch (digit) {
            case '۰':
                digit = '0';
                break;
            case '۱':
                digit = '1';
                break;
            case '۲':
                digit = '2';
                break;
            case '۳':
                digit = '3';
                break;
            case '۴':
                digit = '4';
                break;
            case '۵':
                digit = '5';
                break;
            case '۶':
                digit = '6';
                break;
            case '۷':
                digit = '7';
                break;
            case '۸':
                digit = '8';
                break;
            default:
                digit = '9';
        }
        return digit;
    });

    $this.val(newVal);
});


$('[data-bs-target="#defaultModal"]').click(function () {
    var modal = $('#defaultModal');
    let modal_body = modal.find('div.modal-body');
    let modal_title = modal.find('div.modal-header .modal-title');
    let modal_footer = modal.find('div.modal-footer');
    let modal_header = modal.find('div.modal-header');
    let confirm_btn = modal.find('div.modal-footer #confirm');

    let modal_header_title = $(this).attr('data-title');
    let modal_confirm_text = $(this).attr('data-confirm-text');
    let modal_size = $(this).attr('data-modal-size');
    let modal_path = $(this).attr('data-path');
    let error_msg = "<div class='alert alert-warning'><i class=\"material-icons\">sentiment_neutral</i><span>خطا در بارگزاری اطلاعات</span></div>";

    modal_body.html('در حال بارگذاری اطلاعات ..');
    // set the modal header title.
    modal_title.html(modal_header_title);

    confirm_btn.html(modal_confirm_text);
    if (typeof modal_confirm_text == 'undefined' || modal_confirm_text == '')
        confirm_btn.hide();
    else
        confirm_btn.show();

    if (typeof modal_size != 'undefined')
        modal.find(".modal-dialog").addClass(modal_size);

    // Send View to Modal
    $.ajax({
        type: 'GET',
        url: modal_path,
        success: function (result) {
            modal_footer.show();
            modal_header.show();
            modal_body.html(result);
        }
    }).fail(function (result) {
        modal_footer.hide();
        modal_header.hide();
        modal_body.html(error_msg);
    });
});

window.checkSubmitForm = function () {
    if (document.getElementById('dynamic-form')) {
        document.getElementById('dynamic-form').submit();
    }
}

$(".pdate").persianDatepicker({
    showGregorianDate: true,
    formatDate: "YYYY-0M-0D",
    observer: true,
});
