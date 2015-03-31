$(document).ready(function() {
	$(".sidebar-install").click(function() {
		$('html, body').animate({
			scrollTop: $(".how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(".sidebar-login").click(function() {
		$('html, body').animate({
			scrollTop: $(".login-how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(".sidebar-user-management").click(function() {
		$('html, body').animate({
			scrollTop: $(".user-management-how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(".sidebar-report-generating").click(function() {
		$('html, body').animate({
			scrollTop: $(".report-generating-how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(".sidebar-uploading").click(function() {
		$('html, body').animate({
			scrollTop: $(".uploading-how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(".sidebar-search").click(function() {
		$('html, body').animate({
			scrollTop: $(".search-how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(".sidebar-data-analysis").click(function() {
		$('html, body').animate({
			scrollTop: $(".data-analysis-how-to").offset().top
		}, 1000);
		$(".sidebar-element").removeClass("selected");
		$(this).addClass("selected");		
	});
	
	$(window).scroll(function () {

    if ($(window).scrollTop() > $('.how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-install').addClass("selected");	
    }
	
	if ($(window).scrollTop() > $('.login-how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-login').addClass("selected");	
    }
	if ($(window).scrollTop() > $('.user-management-how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-user-management').addClass("selected");	
    }
	if ($(window).scrollTop() > $('.report-generating-how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-report-generating').addClass("selected");	
    }
	if ($(window).scrollTop() > $('.uploading-how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-uploading').addClass("selected");	
    }
	if ($(window).scrollTop() > $('.search-how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-search').addClass("selected");	
    }
	if ($(window).scrollTop() > $('.data-analysis-how-to').offset().top) {
        $(".sidebar-element").removeClass("selected");
		$('.sidebar-data-analysis').addClass("selected");	
    }
	
	});
	
});