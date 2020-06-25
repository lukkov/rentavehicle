import '../css/app.scss';

import $ from 'jquery';
require('../../_common/auto-submit');

require('bootstrap');
require('bootstrap-datepicker/dist/js/bootstrap-datepicker');

$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

let currentUrl = window.location.pathname;
$('#adminSidebarMenu li').each(function() {
    if (currentUrl.includes($(this).find('a').attr('href'))){
        $(this).addClass('active');
    }
});

$('.custom-file-input').on('change', function(event) {
    let inputFile = event.currentTarget;
    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name);
});
let date = new Date();
date.setDate(date.getDate()-1);

$('.rental-start-date').datepicker({
    format: 'yyyy/mm/dd',
    datesDisabled:  $('.rental-start-date').data('used-dates'),
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.rental-end-date').datepicker({
    format: 'yyyy/mm/dd',
    datesDisabled:  $('.rental-end-date').data('used-dates'),
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.filter-rental-start-date').datepicker({
    format: 'yyyy/mm/dd'
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.filter-rental-end-date').datepicker({
    format: 'yyyy/mm/dd'
}).on('show', function() {
    $('.datepicker').addClass('show');
});