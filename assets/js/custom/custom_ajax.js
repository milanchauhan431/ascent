// Datatable : Get Serverside Data
function ssDatatable(ele,tableHeaders,tableOptions,dataSet={}){	
	var textAlign ={}; //var srnoPosition = 1;
	if(tableHeaders.textAlign!=""){var textAlign = JSON.parse(tableHeaders.textAlign);}
	
	var orderableTarget =[0,1]; 
	if(tableHeaders.sortable != ""){var orderableTarget = JSON.parse(tableHeaders.sortable);}
	
	var dataUrl = ele.attr('data-url');
	var tableId = ele.attr('id');
	if(tableHeaders[0] != ""){$('#' + tableId).append(tableHeaders.theads);}	

	var ssTableOptions = {
		"paging": true,
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
		/* "rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			console.log(nRow);
			return nRow;
	  	}, */
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
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function(){initTable();} }],
		"fnInitComplete":function(){$('.dataTables_scrollBody').perfectScrollbar();},
		"fnDrawCallback": function( oSettings ) {$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();$(".bt-switch").bootstrapSwitch();}
	};
	
	// Append Search Inputs
	if (tableHeaders.hasOwnProperty('reInit')) {}
	else{
		$('.ssTable-cf thead tr:eq(0)').clone(true).insertAfter( '.ssTable-cf thead tr:eq(0)' );
		var ignorCols = $(".ssTable-cf").data('ninput');//.split(",");
		var selectIndex = $(".ssTable-cf").data('selectindex');
		var selectBox = $(".ssTable-cf").data('selectbox');
		var lastIndex = $(".ssTable-cf thead").find("tr:first th").length - 1;var i=0;
			
		$(".ssTable-cf thead tr:eq(1) th").each( function (index,value) 
		{
			if(jQuery.inArray(index, ignorCols) != -1) {$(this).html( '' );}
			else
			{
				if((jQuery.inArray(-1, ignorCols) != -1) && index == lastIndex){$(this).html( '' );}
				else{$(this).html( '<input type="text" style="width:100%;"/>' );}
			}
			
		    if(jQuery.inArray(index, selectIndex)!= -1) {$(this).html( selectBox[i] );i++;}
			    
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
	
	/*setTimeout(function(){
    	$('#' + tableId +' tbody tr').each( (tr_idx,tr) => {
            $(tr).children('td').each( (td_idx, td) => {
                console.log( '[' +tr_idx+ ',' +td_idx+ '] => ' + $(td).text());
                if(($(td).text().length) > 35){$(td).addClass('bwa');}
            });                 
        });
	}, 1000);*/
    
	$('.ssTable-cf thead tr:eq(1) th').each( function (i) {
		$( 'input', this ).on( 'keyup', function () {
			if ( ssTable.column(i).search() !== this.value ) {ssTable.column(i).search( this.value ).draw();}
		});
		$( 'select', this ).on( 'change', function () {
			if ( ssTable.column(i).search() !== this.value ) {ssTable.column(i).search( this.value ).draw();}
		});
	} );
	
	// if(tableId){initSpeechRecognitation();}
}

function jpDataTable_old(tableId){
	var jpDataTable = $('.jpDataTable').DataTable( {
		"paging": true,
		responsive: true,
		"scrollY": '52vh',
		"scrollX": true,
		deferRender: true,
		scroller: true,
		destroy: true,
		// 'stateSave':false,
		"autoWidth" : false,
		pageLength: 50,
		language: { search: "" },
		lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,500 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','500 rows' ]
		],
		order:[],
		orderCellsTop: true,
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 'pageLength','copy', 'excel']
	});
	jpDataTable.buttons().container().appendTo( '#' + tableId +'_wrapper toolbar' );
	$('.dataTables_filter').css("text-align","left");
	$('#' + tableId +'_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	$('#' + tableId +'_filter label').attr("id","search-form");	
	$('#' + tableId +'_filter .form-control-sm').css("width","97%");
	$('#' + tableId +'_filter .form-control-sm').attr("placeholder","Search.....");	
	
	setTimeout(function(){ jpDataTable.columns.adjust().draw();}, 300);
	return jpDataTable;
}

function jpDataTable(tableId,state=false){
	
	// Append Search Inputs
	var srowposition = $('#'+tableId).data('srowposition');
	if(!srowposition){srowposition = 1;}
	var cloneFromTr = srowposition -1;
	var headerRowCount = $('.colSearch thead tr').length;
	if ( headerRowCount == srowposition ) {
    	$('.colSearch thead tr:eq('+cloneFromTr+')').clone(true).insertAfter( '.colSearch thead tr:eq('+cloneFromTr+')' );
    	var ignorCols = $(".colSearch").data('ninput');//.split(",");
    	var lastIndex = $(".colSearch thead").find("tr:first th").length - 1;
    	$(".colSearch thead tr:eq("+srowposition+") th").each( function (index,value) 
    	{
    		if(jQuery.inArray(index, ignorCols) != -1) {$(this).html( '' );}
    		else
    		{
    			if((jQuery.inArray(-1, ignorCols) != -1) && index == lastIndex){$(this).html( '' );}
    			else{$(this).html( '<input type="text" style="width:100%;"/>' );}
    		}
    	});
	}
	
	var jpDataTable = $('.jpDataTable').DataTable( {
		"paging": true,
		responsive: true,
		"scrollY": '52vh',
		"scrollX": true,
		deferRender: true,
		scroller: true,
		destroy: true,
		'stateSave':true,
		"autoWidth" : false,
		pageLength: 50,
		language: { search: "" },
		lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,500 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','500 rows' ]
		],
		order:[],
		orderCellsTop: true,
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 'pageLength','copy', 'excel'],
		"fnInitComplete":function(){
		    $('.dataTables_scrollBody').perfectScrollbar();
		    $("#"+tableId+" tbody tr").each(function () {
                $('td', this).each(function (i,v) {
                    var td = $(this);
                    var styleProps = $("#"+tableId+" thead tr th:eq("+i+")").css(["text-align"]);
                    $.each( styleProps, function( prop, value ){td.css( prop, value ); });
                 });
            });
		},
		"fnDrawCallback": function( oSettings ) {$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();}
	});
	
	jpDataTable.buttons().container().appendTo( '#' + tableId +'_wrapper toolbar' );
	$('.dataTables_filter').css("text-align","left");
	$('#' + tableId +'_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	$('#' + tableId +'_filter label').attr("id","search-form");	
	$('#' + tableId +'_filter .form-control-sm').css("width","97%");
	$('#' + tableId +'_filter .form-control-sm').attr("placeholder","Search.....");	
	
	var state = jpDataTable.state.loaded();
	$('.colSearch thead tr:eq('+srowposition+') th').each( function (i) {
		if ( state ) {
			var colSearch = state.columns[i].search;
			if ( colSearch.search ) {$('.colSearch thead tr:eq('+srowposition+') th:eq('+i+') input').val( colSearch.search );}
		}
		$( 'input', this ).on( 'keyup change', function () {
			if ( jpDataTable.column(i).search() !== this.value ) {jpDataTable.column(i).search( this.value ).draw();}
		});
	} );
	
	$('.page-wrapper').resizer(function() {jpDataTable.columns.adjust().draw(); });
	setTimeout(function(){ jpDataTable.columns.adjust().draw();}, 100);
	return jpDataTable;
}

function jpReportTable(tableId) {
	var jpReportTable = $('#'+tableId).DataTable({
		responsive: true,
		//"scrollY": '52vh',
		//"scrollX": true,
		"scrollXInner": true,
		//deferRender: true,
		//scroller: true,
		//destroy: true,
		//'stateSave':false,
		"fixedHeader": true,
		"autoWidth" : false,
		order: [],
		"columnDefs": [
		    {type: 'natural',targets: 0},
			{orderable: false,targets: "_all"},
			{className: "text-center",targets: [0, 1]},
			{className: "text-center","targets": "_all"}
		],
		pageLength: 25,
		language: {search: ""},
		lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,500 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','500 rows' ]
		],
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" + "<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: ['pageLength', 'excel'],
		"fnInitComplete":function(){$('.dataTables_scrollBody').perfectScrollbar();},
	    "fnDrawCallback": function( oSettings ) {$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();}
	});
	jpReportTable.buttons().container().appendTo('#'+tableId+'_wrapper toolbar');
	$('.dataTables_filter .form-control-sm').css("width", "97%");
	$('.dataTables_filter .form-control-sm').attr("placeholder", "Search.....");
	$('.dataTables_filter').css("text-align", "left");
	$('.dataTables_filter label').css("display", "block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius", "0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius", "0");
	setTimeout(function(){ jpReportTable.columns.adjust().draw();}, 10);
	$('.page-wrapper').resizer(function() {jpReportTable.columns.adjust().draw(); });
	return jpReportTable;
}

// Datatable : Get Serverside Data
function searchingDatatable(ele,tableOptions,dataSet={}){	
	var textAlign ={};var srnoPosition = 0;
	var dataUrl = ele.attr('data-url');
	var tableId = ele.attr('id');
	if(tableHeaders[0] != ""){$('#' + tableId).append(tableHeaders.theads);}		
	var ssTableOptions = {
		"paging": true,
		"processing": true,
		"serverSide": true,
		'ajax': {url: base_url + dataUrl,type:"POST", data:dataSet,global:false } ,
		responsive: true,
		"scrollY": '52vh',
		"scrollX": true,
		deferRender: true,
		scroller: true,
		destroy: true,
		"autoWidth" : false,
		pageLength: 50,
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$('td', nRow).eq(srnoPosition).html(oSettings._iDisplayStart+iDisplayIndex +1);
			return nRow;
	  	},
		language: { search: "" },
		lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,500 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','500 rows' ]
		],
		order:[],
		orderCellsTop: true,
		"columnDefs": 	[
							// { orderable: false, targets: [0,1] } ,
							{ className: "text-center", "targets": textAlign.center }, 
							{ className: "text-left", "targets": textAlign.left }, 
							{ className: "text-right", "targets": textAlign.right }
						],
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function ( e, dt, node, config ) {innitSearchingTable(srnoPosition);}}],
		"fnInitComplete":function(){$('.dataTables_scrollBody').perfectScrollbar();},
		"fnDrawCallback": function( oSettings ) {$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();$(".bt-switch").bootstrapSwitch();}
	};
	
	// Append Search Inputs
	if (tableHeaders.hasOwnProperty('reInit')) {}
	else{
		$('.ssTable-cf thead tr:eq(0)').clone(true).insertAfter( '.ssTable-cf thead tr:eq(0)' );
		var ignorCols = $(".ssTable-cf").data('ninput');//.split(",");
		var lastIndex = $(".ssTable-cf thead").find("tr:first th").length - 1;
		$(".ssTable-cf thead tr:eq(1) th").each( function (index,value) 
		{
			if(jQuery.inArray(index, ignorCols) != -1) {$(this).html( '' );}
			else
			{
				if((jQuery.inArray(-1, ignorCols) != -1) && index == lastIndex){$(this).html( '' );}
				else{$(this).html( '<input type="text" style="width:100%;"/>' );}
			}
		});
	}
	
	$.extend( ssTableOptions, tableOptions );
	ssTable = ele.DataTable(ssTableOptions);
	ssTable.buttons().container().appendTo( '#' + tableId +'_wrapper toolbar' );
	$('.dataTables_filter').css("text-align","left");
	$('.dataTables_filter').css("display","none");
	$('#' + tableId +'_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	$('#' + tableId +'_filter label').attr("id","search-form");	
	$('#' + tableId +'_filter .form-control-sm').css("width","97%");
	$('#' + tableId +'_filter .form-control-sm').attr("placeholder","Search.....");	
	
	$('.ssTable-cf thead tr:eq(1) th').each( function (i) {
		$( 'input', this ).on( 'keyup change', function () {
			if ( ssTable.column(i).search() !== this.value ) {ssTable.column(i).search( this.value ).draw();}
		});
	} );
	
	// if(tableId){initSpeechRecognitation();}
}

// Datatable : Get Serverside Data For Tax Invoice
function defaultDtTable(ele,dataSet={})
{	
	var dataUrl = ele.attr('data-url');
	var tableId = ele.attr('id');	
	var dtTableOptions = {
		"paging": true,
		"processing": true,
		"serverSide": true,
		'ajax': {url: base_url + controller + dataUrl,type:"POST", data:dataSet,global:false } ,
		responsive: true,
		"scrollY": '52vh',
		"scrollX": true,
		deferRender: true,
		scroller: true,
		destroy: true,
		'stateSave':true,
		"autoWidth" : false,
		pageLength: 50,
		language: { search: "" },
		lengthMenu: [
			[ 10, 20, 25, 50, 75, 100, 250,-1 ],
			[ '10 rows', '20 rows', '25 rows', '50 rows', '75 rows', '100 rows','250 rows','Show All' ]
		],
		order:[],
		orderCellsTop: true,
		dom: "<'row'<'col-sm-4'B><'col-sm-8 ctbtr'>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function ( e, dt, node, config ) {initDtTable(dataSet);}}, {text: 'Pdf',action: function ( e, dt, node, config ) {dtTablePdf();}}],
		"fnInitComplete":function(){
		    $('.dataTables_scrollBody').perfectScrollbar();
		    $(".dtTable tbody tr").each(function () {
                $('td', this).each(function (i,v) {
                    var td = $(this);
                    var styleProps = $(".dtTable thead tr th:eq("+i+")").css(["text-align"]);
                    $.each( styleProps, function( prop, value ){td.css( prop, value ); });
                 });
            });
		},
		"fnDrawCallback": function( oSettings ) {$('.dataTables_scrollBody').perfectScrollbar('destroy').perfectScrollbar();checkPermission();}
	};
	
	// Append Search Inputs
	var headerRowCount = $('.dtTable-cf thead tr').length;
	if ( headerRowCount == 1 ) {
		$('.dtTable-cf thead tr:eq(0)').clone(true).insertAfter( '.dtTable-cf thead tr:eq(0)' );
		var ignorCols = $(".dtTable-cf").data('ninput');//.split(",");
		var lastIndex = $(".dtTable-cf thead").find("tr:first th").length - 1;
		$(".dtTable-cf thead tr:eq(1) th").each( function (index,value) 
		{
			if(jQuery.inArray(index, ignorCols) != -1) {$(this).html( '' );}
			else
			{
				if((jQuery.inArray(-1, ignorCols) != -1) && index == lastIndex){$(this).html( '' );}
				else{$(this).html( '<input type="text" style="width:100%;"/>' );}
			}
		});
	}
	
	dtTable = ele.DataTable(dtTableOptions);
	dtTable.buttons().container().appendTo( '#' + tableId +'_wrapper toolbar' );
	$('.dataTables_filter').css("text-align","left");
	$('#' + tableId +'_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	$('#' + tableId +'_filter label').attr("id","search-form");	
	$('#' + tableId +'_filter .form-control-sm').css("width","97%");
	$('#' + tableId +'_filter .form-control-sm').attr("placeholder","Search.....");	
	
	var state = dtTable.state.loaded();
	$('.dtTable-cf thead tr:eq(1) th').each( function (i) {
		if ( state ) {
			var colSearch = state.columns[i].search;
			if ( colSearch.search ) {$('.dtTable-cf thead tr:eq(1) th:eq('+i+') input').val( colSearch.search );}
		}
		$( 'input', this ).on( 'keyup change', function () {
			if ( dtTable.column(i).search() !== this.value ) {dtTable.column(i).search( this.value ).draw();}
		});
	} );
	//setTimeout(function(){if(ctb != ""){$('div.ctbtr').html();}},500);
	// if(tableId){initSpeechRecognitation();}
}