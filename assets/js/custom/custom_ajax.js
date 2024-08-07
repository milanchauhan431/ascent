$(document).ready(function(){
	var selectedRow = null;	

	// Handle initial row selection on click
	$(document).on("click",".table tbody tr",function(){
		$(".table tbody tr").removeClass('selectedTableRow');
		$(this).addClass('selectedTableRow');
		selectedRow = $(this);
	});

	/* Table tr selected and up down selected row using up and down key */
	$(document).keydown(function(e) {
		//Table row up down using Up arrow key and Down arrow key
		if (e.keyCode === 38) { // Up arrow key
			if (selectedRow && selectedRow.prev().length > 0) {
				selectedRow.removeClass('selectedTableRow');
				selectedRow = selectedRow.prev();
				selectedRow.addClass('selectedTableRow');
			}
		} else if (e.keyCode === 40) { // Down arrow key
			if (selectedRow && selectedRow.next().length > 0) {
				selectedRow.removeClass('selectedTableRow');
				selectedRow = selectedRow.next();
				selectedRow.addClass('selectedTableRow');
			}
		}

		//Table scrolling when up down row
		if(selectedRow != null){
			$ ('.key-scroll .dataTables_scrollBody').scrollTop(selectedRow.position().top);
		}		
		
		//on press atl + O bar to show/hide action Buttons
		if (e.altKey && e.keyCode === 79) { // Check if the key pressed is the spacebar (key code 32)
			//e.preventDefault(); // Prevent the default action of the spacebar (scrolling down the page)
			console.log('click');
			if($('.selectedTableRow .actionButtons .mainButton').hasClass("showAction") == false){
				$('.selectedTableRow .actionButtons .mainButton').addClass('open showAction'); // Trigger the click event on your button 
				$('.selectedTableRow .actionButtons .btnDiv').attr('style','z-index:9;');
				$('.selectedTableRow .actionButtons .mainButton i').removeClass('fa fa-cog');
				$('.selectedTableRow .actionButtons .mainButton i').addClass('fa fa-times');
				$('.selectedTableRow .actionButtons .btnDiv a:first').focus();
			}else{
				$('.selectedTableRow .actionButtons .mainButton ').removeClass('open showAction'); // Trigger the click event on your button 
				$('.selectedTableRow .actionButtons .btnDiv').attr('style','z-index:-1;');
				$('.selectedTableRow .actionButtons .mainButton i').removeClass('fa fa-times');
				$('.selectedTableRow .actionButtons .mainButton i').addClass('fa fa-cog');
				$('.selectedTableRow').focus();
			}		  
		}

		// Check if the Alt key and A key are pressed
		if(e.altKey && e.which === 65 || e.altKey && e.which == "a"){
			//Open modal or page for new entry 
			$(".card-header .press-add-btn").trigger("click");
		}

		// Check if the Alt key and C key are pressed
		if (e.altKey && e.which === 67) {
			// Find the last opened modal and close it
			$('.modal-footer .press-close-btn, .card-footer .press-close-btn').trigger("click");
		}
		
		// GO TO Datatable pagination next page using (ALT + N)
		if(e.altKey && e.which === 78){
			$(".dataTables_paginate .pagination .next").trigger("click");
		}

		// GO TO Datatable pagination previous page using (ALT + P)
		if(e.altKey && e.which === 80){
			$(".dataTables_paginate .pagination .previous").trigger("click");
		}
	});
});

// Datatable : Get Serverside Data
function ssDatatable(ele,tableHeaders,tableOptions,dataSet={}){	
	var textAlign ={}; //var srnoPosition = 1;
	if(tableHeaders.textAlign!=""){var textAlign = JSON.parse(tableHeaders.textAlign);}
	
	var orderableTarget = [0,1]; 
	if(tableHeaders.sortable != ""){var orderableTarget = JSON.parse(tableHeaders.sortable) || [0,1];}
	
	var dataUrl = ele.attr('data-url');
	var tableId = ele.attr('id'); 
	if(tableHeaders.theads != ""){
		$('#' + tableId).html("");		
		$('#' + tableId).append(tableHeaders.theads);		
	}	

	var ssTableOptions = {
		"paging": true,
		//"processing": true,
		"serverSide": true,
		'ajax': {
			url: base_url + controller + dataUrl,
			type:"POST", data:dataSet,
			global:false ,
			beforeSend: function() {
				var columnCount = $('#'+tableId+' thead tr').first().children().length;
				$("#"+tableId+" TBODY").html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			},
		} ,
		responsive: true,
		"scrollY": '52vh',
		"scrollX": true,
		deferRender: true,
		scroller: true,
		destroy: true,
		//'stateSave':true,
		"autoWidth" : false,
		pageLength: 50,
		language: { search: "" },
		lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,500 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','500 rows' ]
		],
		order:[],
		orderCellsTop: true,
		"columnDefs": 	[
							{ orderable: false, targets: orderableTarget } ,
							{ className: "text-center", "targets": textAlign.center }, 
							{ className: "text-left", "targets": textAlign.left }, 
							{ className: "text-right", "targets": textAlign.right }
						],
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 
			'pageLength', 
			'excel', 
			{className:'nav-tab-refresh',text: 'Refresh',action: function(){ initTable(); } }
		],
		
		"fnInitComplete":function(){
			$('.dataTables_scrollBody').perfectScrollbar();
			$('#' + tableId +' tbody tr:first').trigger('click');
		},
		"fnDrawCallback": function( oSettings ) {
			$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();
			$(".bt-switch").bootstrapSwitch();
			checkPermission();
			$('#' + tableId +' tbody tr:first').trigger('click');
		}
	};	

	// Append Search Inputs	
	var headerRowCount = $('.ssTable thead tr').length;
	if ( headerRowCount == 1 ) {

		$('.ssTable-cf thead tr:eq(0)').clone(true).insertAfter( '.ssTable-cf thead tr:eq(0)' );
		var ignorCols = $(".ssTable-cf").data('ninput');
		var selectIndex = $(".ssTable-cf").data('selectindex');
		var selectBox = $(".ssTable-cf").data('selectbox');
		var lastIndex = $(".ssTable-cf thead").find("tr:first th").length - 1;var i=0;
			
		$(".ssTable-cf thead tr:eq(1) th").each( function (index,value) {
			if($.inArray(index, ignorCols) != -1) {$(this).html( '' );}
			else{
				if(($.inArray(-1, ignorCols) != -1) && index == lastIndex){$(this).html( '' );}
				else{$(this).html( '<input type="text" style="width:100%;"/>' );}
			}
			
			if($.inArray(index, selectIndex)!= -1) {$(this).html( selectBox[i] );i++;}
					
			$(this).removeClass("sorting sorting_asc sorting_desc");
			$(this).addClass("sorting_disabled");
			$(this).attr("data-orderable",false);
		});
	}
	
	$.extend( ssTableOptions, tableOptions );
	ssTable = ele.DataTable(ssTableOptions);
	ssTable.buttons().container().appendTo( '#' + tableId +'_wrapper toolbar' );
	$('.dataTables_filter').css("text-align","left");
	$('#' + tableId +'_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	$('#' + tableId +'_filter label').attr("id","search-form");	
	$('#' + tableId +'_filter .form-control-sm').css("width","97%");
	$('#' + tableId +'_filter .form-control-sm').attr("placeholder","Search.....");	
	$(".dataTables_scroll").addClass("key-scroll");
    
	/* $('.ssTable-cf thead tr:eq(1) th').each( function (i) {
		$( 'input', this ).on( 'keyup', function () {
			if ( ssTable.column(i).search() !== this.value ) {ssTable.column(i).search( this.value ).draw();}
		});
		$( 'select', this ).on( 'change', function () {
			if ( ssTable.column(i).search() !== this.value ) {ssTable.column(i).search( this.value ).draw();}
		});
	} ); */

	var state = ssTable.state.loaded();
	$('.ssTable-cf thead tr:eq(1) th').each( function (i) {
		if ( state ) {
			var colSearch = state.columns[i].search;
			if ( colSearch.search ) {$('.ssTable-cf thead tr:eq(1) th:eq('+i+') input').val( colSearch.search );}
		}
		$( 'input', this ).on( 'keyup change', function () {
			if ( ssTable.column(i).search() !== this.value ) {ssTable.column(i).search( this.value ).draw();}
		});
	});
	
	// if(tableId){initSpeechRecognitation();}
}