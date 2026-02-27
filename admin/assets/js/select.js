$('#selectAll').on('click', function () {
    // Use prop() method to set the "checked" property of all checkboxes
    $('.checkbox').prop('checked', $(this).prop('checked'));
});