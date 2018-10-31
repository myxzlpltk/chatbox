(function ($) {

	'use strict';

	var config = {
		dataTable: {
			"language" : {
				"sProcessing":   "Sedang memproses...",
				"sLengthMenu":   "Tampilkan _MENU_ entri",
				"sZeroRecords":  "Tidak ditemukan data yang sesuai",
				"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
				"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
				"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
				"sInfoPostFix":  "",
				"sSearch":       "Cari:",
				"sUrl":          "",
				"oPaginate": {
					"sFirst":    "Pertama",
					"sPrevious": "Sebelumnya",
					"sNext":     "Selanjutnya",
					"sLast":     "Terakhir"
				}
			},
			"order": [[0, "desc" ]]
		}
	};

	if(jQuery().DataTable != undefined){
		$('table.dataTable').each(function(){
			var cfg = config.dataTable;
			if($(this).data('ajax') != undefined){
				cfg["processing"] = true;
				cfg["serverSide"] = true;
				cfg["ajax"] = $(this).data('ajax');
			}

			$(this).dataTable(cfg);
		});
	}

	$('iframe').on('load', function(event){
		$(this).height($(this).contents().height());
	});

	$('button[type="submit"]').click(function(event){
		event.preventDefault();

		swal({
			text: 'Apakah anda yakin untuk mengirim?',
			dangerMode: true,
			buttons: true
		}).then((value) => {
			if(value == true){
				$(this).closest('form').submit();
			}
		});
	});

}(jQuery));