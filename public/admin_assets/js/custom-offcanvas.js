$(document).ready(function() {
    $(document).on('click', '.cancel_btn, .cls_btn_left', function(e) {
        var offcanvasElement = $(this).closest('.offcanvas');
        if (offcanvasElement.length) {
            var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement[0]);
            if (!offcanvasInstance) {
                offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement[0]);
            }
            offcanvasInstance.hide();
        }
    });
});
