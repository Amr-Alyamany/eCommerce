// viewport
$(document).ready(function run() {
		$(".form").slideDown(1000, function() { });
});

	if(screen.width<=300){
		document.getElementById("myViewport").setAttribute("content","width=300, user-scalable=no");
	
	}else{
		document.getElementById("myViewport").setAttribute("content","width=device-width , user-scalable=no");
	}
$(function(){

	'use strict';
	$('[placeholder]').focus(function() {
		$(this).attr('data-text',$(this).attr('placeholder'));
		$(this).attr('placeholder' , '');
	}).blur(function() {
		$(this).attr('placeholder' , $(this).attr('data-text'));
	});

$('input').each(function() {
	if ($(this).attr('required') === "required") {
		$(this).after('<span class="asterisk">*</span>');
		}
	});

var pass = $('.password');

$('.show-pass').hover(function () {
	pass.attr('type' , 'text');
	}, function () {
		pass.attr('type' , 'password');
	});

$('.confirm').click(function () {
	return confirm('Are you sure?');
	});
});