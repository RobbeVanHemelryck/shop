var URL = "https://projects.taltiko.com/shop/";
var browsePagina = 1;
$(function(){

	$inputs = $(".factuur-input-other");
    if ($('#zelfdeAdres').is(':checked')) {
        $inputs.prop("required", false);
		$inputs.hide(100);
    }
    else{
    	$inputs.prop("required", true);
		$inputs.show(100);
    }


	setTimeout(function(){ 
		$(".notification-green").first().fadeOut(400, function showNext(){
	    	$(this).next(".notification-green").fadeOut(400, showNext);
	    });
	}, 3000);

	$(".notification-close").on('click', function(e){
		$(this).parent().fadeOut(250);
	});

	$(".winkelwagen-add").on("click", function(e){
		e.preventDefault();

		var productId = $(this).parent().parent().find(".product-metadata-id").html();

		$.ajax({
	        type: "POST",
	        url: URL + "controllers/RequestController.php",
	        data: {'winkelwagenAdd' : productId, 'ajax' : true},
	        success: function(data) {
	        	$("#nav-winkelmandje-dropdown").html(data);
	        }
	    });
	});

	$(".nav-winkelmandje-account-btn").on('click', function(e){
		var id = this.id;
		$dropdown1 = $("#" + id + "-dropdown");

		$dropdown1.toggle(100);

		$(".nav-winkelmandje-account-dropdown").not($dropdown1).hide();

		e.stopPropagation();

		$("body").on('click', function(e){
			if(!($(e.target).parents(".nav-winkelmandje-account-dropdown").length > 0)){
				$dropdown1.hide(100);
			}
		});
	});

	$(".producten-titel-btn").on('click', function(e){
		var id = this.id;
		$dropdown2 = $("#" + id.replace("btn", "dropdown"));

		$dropdown2.toggle(100);

		$(".producten-titel-dropdown").not($dropdown2).hide();

		e.stopPropagation();

		$("body").on('click', function(e){
			if(!($(e.target).parents(".producten-titel-dropdown").length > 0)){
				$dropdown2.hide(100);
			}
		});
	});

	$(".producten-titel-item-sort").on('click', function(e){

		var id = this.id;
		$.ajax({
	        type: "POST",
	        url: URL + "controllers/RequestController.php",
	        data: {'sortMethod' : id, 'filters' : filters},
	        success: function(data) {
	        	$(".producten-cont").html(data);
	        },
	        error: function(req, error){
	        	console.log(error);
	        }
	    });
	});

	

	$(".browse-filter-submit").on('click', function(e){
		e.preventDefault();
		var filters_beta = $(".browse-filter-checkbox:checked");
		
		var filters_alfa = [];
		for(var i = 0; i < filters_beta.length; i++){
			filters_alfa.push(filters_beta[i].value);
		}

		window.filters = filters_alfa;

		getBrowseHtml(1);
	});

	$(document.body).on('click', '.pagina-nummer' ,function(e){
		e.preventDefault();
		getBrowseHtml(this.value);

		$paginanummers = $('.pagina-nummer');
		for(var i = 0; i < $paginanummers.length; i++){
			$($paginanummers[i]).removeClass('pagina-nummer-current');
		}
		$(this).addClass('pagina-nummer-current');
	});

	function getBrowseHtml(pagina){
		$.ajax({
	        type: "POST",
	        url: URL + "controllers/RequestController.php",
	        data: {'onlyFilter' : true, 'filters' : window.filters, 'pagina' : pagina, 'perPagina' : 12},
	        success: function(data) {
	        	data = JSON.parse(data);
	        	$(".producten-cont").html(data['producten']);
	        	$('#pagina-subcont').html(data['paginas']);
	        },
	        error: function(req, error){
	        	console.log(error);
	        }
	    });
	}

	$('#zelfdeAdres').change(function(){
		$inputs = $(".factuur-input-other");
        if ($(this).is(':checked')) {
            $inputs.prop("required", false);
			$inputs.hide(100);
        }
        else{
        	$inputs.prop("required", true);
			$inputs.show(100);
        }
    });

	$('#zoeken-submit').on('click', function(e){
		if($('#zoeken-zoekbalk').val() == ''){
			e.preventDefault();
		}
		if($(window).width() < 1000){
			if($('#zoeken-zoekbalk').val() == ''){
				e.preventDefault();
				$('#zoeken-zoekbalk').toggle(200);
			}
		}
	});

	$(window).resize(function(){
		if($(window).width() > 1001 && $('#zoeken-zoekbalk').css('display', 'block')){
			$('#zoeken-zoekbalk').show(200);
		}
		if($(window).width() < 1000){
			$('#zoeken-zoekbalk').hide();
		}
	});
});