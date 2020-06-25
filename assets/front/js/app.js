import '../css/app.scss';

import $ from 'jquery';
global.$ = $;

import 'bootstrap';
import 'owl.carousel';
import '../js/carrent-master/main';
require('bootstrap-datepicker/dist/js/bootstrap-datepicker');
require('../../_common/auto-submit');

$('#profile-confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('.js-datepicker').datepicker({
    format: 'mm/dd/yyyy'
});

let currentUrl = window.location.pathname;
$('.profile-navbar li').each(function() {
    if (currentUrl.includes($(this).find('a').attr('href'))){
        $(this).addClass('active');
    }
});

let date = new Date();
date.setDate(date.getDate()-1);

$('.rental-start-date').datepicker({
    format: 'yyyy/mm/dd',
    startDate: date,
    datesDisabled:  $('.rental-start-date').data('used-dates'),
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.rental-end-date').datepicker({
    format: 'yyyy/mm/dd',
    startDate: date,
    datesDisabled:  $('.rental-end-date').data('used-dates'),
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.quick-search-available-from').datepicker({
    format: 'yyyy/mm/dd',
    startDate: date,
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.quick-search-available-to').datepicker({
    format: 'yyyy/mm/dd',
    startDate: date,
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.filter-rental-start-date').datepicker({
    format: 'yyyy/mm/dd',
    startDate: date,
}).on('show', function() {
    $('.datepicker').addClass('show');
});

$('.filter-rental-end-date').datepicker({
    format: 'yyyy/mm/dd',
    startDate: date,
}).on('show', function() {
    $('.datepicker').addClass('show');
});
