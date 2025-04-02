<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Currency';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddCcy"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblCcy" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="code" data-sortable="true" data-formatter="codeFormatter" data-width="30%"><?= Yii::t('app', 'Code') ?></th>
					<th data-field="name" data-sortable="true" data-width="35%"><?= Yii::t('app', 'Name') ?></th>
					<th data-field="numeric_code" data-sortable="true" data-width="30%" ><?= Yii::t('app', 'Numeric Code') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update Currency -->
<div class="modal fade" id="modalCcyForm" aria-hidden="true" aria-labelledby="modalCcyTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalCcyTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetCcyForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formCcyData" class="form" autocomplete="off">
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code') ?></label>
						<div class="col-sm-8">
							<input id="inputCcyCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Code') ?>" maxlength="3" data-parsley-required data-parsley-maxlength="3" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>
						<div class="col-sm-8">
							<input id="inputCcyName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-required data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Numeric Code') ?></label>
						<div class="col-sm-8">
                        <input id="inputCcyNumericCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Numeric Code') ?>" maxlength="3" data-parsley-maxlength="3" data-parsley-pattern="[0-9]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be numeric.') ?>">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetCcyForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button class="btn btn-sm btn-primary" form="formCcyData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _ccyList = <?= $ccyList ?>;
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblCcy = {
		makePagination	: true,
		singleSelect	: false,
		clickToSelect	: false,
		uniqueId		: 'ID',
		toolbar			: '#toolbar',
		showRefresh		: false,
		method			: 'POST',
		pagination		: true,
		sidePagination 	: 'server',
		pageNumber		: 1,
		pageSize		: 10,
		sortName        : ['code'],
		sortOrder       : ['ASC'],
		data 			: _ccyList.rows,
		totalRows 		: _ccyList.total,
		url             : '<?= Url::to(['sa/currency/get-currency-list']) ?>',
		responseHandler : function(res)
		{
			return res;
		},
		queryParams     : function(p)
		{
			if (!_isAdvanceSearch)
			{
				p.search = $('#__txtGlobalSearch__').val();
			}
			else if (_isAdvanceSearch)
			{
				p.search = [$('#inputSearchCcyCode').val(), $('#inputSearchCcyName').val(), $('#inputSearchCcyNumericCode').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formCcyData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formCcyData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertCcy();
			} else if (_action == 'edit') {
				updateCcy();
			}
		});
        
        $('#tblCcy').bootstrapTableWrapper(_tblCcy);
		generateGlobalSearchCcy();

        $('#btnAddCcy').on('click', function() {
			_action = 'add';
			resetCcyForm();
			$('#modalCcyTitle').html('<?= Yii::t('app', 'Add Currency') ?>');
			$('#modalCcyForm').modal('show');
		});
    });

	function insertCcy() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add Currency') ?>?',
			closeByBackdrop: false,
			buttons: [
				{
					label: '<?= Yii::t('app', 'No') ?>',
					cssClass: "btn btn-sm btn-secondary",
					action: function(modalWrapper, button, buttonData, originalEvent) 
					{
						modalWrapper.hide();
					}
				}, 
				{
					label: '<?= Yii::t('app', 'Yes') ?>',
					cssClass: "btn btn-sm btn-primary",
					action: function(modalWrapper, button, buttonData, originalEvent) 
					{
						var data = {
							'ccyCode' 			: $('#inputCcyCode').val(),
							'ccyName' 			: $('#inputCcyName').val(),
							'ccyNumericCode' 	: $('#inputCcyNumericCode').val()
						};

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/currency/insert-currency') ?>',
							success : function(response)
							{
								BootstrapModalWrapperFactory.alert({
									title  : '<?= Yii::t('app', 'Message') ?>',
									message: nl2br(msgConverter(response)),
									closeByBackdrop: false,
									buttons: [
										{
											label   : 'OK',
											cssClass: 'btn btn-primary',
											action  : function (modalWrapper, button, buttonData, originalEvent)
											{
												modalWrapper.hide();

												if (response.errNum == 0) {
													$('#modalCcyForm').modal('hide');
													resetCcyForm();
													$('#tblCcy').bootstrapTable('refresh');
												}
											}
										},
									]
								});
							}
						});
						
						modalWrapper.hide();
					}
				}
			]
		});
	}

	function updateCcy() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this Currency') ?>?',
			closeByBackdrop: false,
			buttons: [
				{
					label: '<?= Yii::t('app', 'No') ?>',
					cssClass: "btn btn-sm btn-secondary",
					action: function(modalWrapper, button, buttonData, originalEvent) 
					{
						modalWrapper.hide();
					}
				}, 
				{
					label: '<?= Yii::t('app', 'Yes') ?>',
					cssClass: "btn btn-sm btn-primary",
					action: function(modalWrapper, button, buttonData, originalEvent) 
					{
						var data = {
							'ccyCode' 			: $('#inputCcyCode').val(),
							'ccyName' 			: $('#inputCcyName').val(),
							'ccyNumericCode' 	: $('#inputCcyNumericCode').val()
						};
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/currency/update-currency') ?>',
							success : function(response)
							{
								BootstrapModalWrapperFactory.alert({
									title  : '<?= Yii::t('app', 'Message') ?>',
									message: nl2br(msgConverter(response)),
									closeByBackdrop: false,
									buttons: [
										{
											label   : 'OK',
											cssClass: 'btn btn-primary',
											action  : function (modalWrapper, button, buttonData, originalEvent)
											{
												modalWrapper.hide();

												if (response.errNum == 0) {
													$('#modalCcyForm').modal('hide');
													resetCcyForm();
													$('#tblCcy').bootstrapTable('refresh');
												}
											}
										},
									]
								});
							}
						});
		
						modalWrapper.hide();
					}
				}
			]
		});
	}

	function searchCcyList(flag) {
		_isAdvanceSearch = flag;
		$('#tblCcy').bootstrapTable('refresh');
	}

	function generateGlobalSearchCcy() {
		$.globalSearch({
			searchFunction			: function() {return searchCcyList(false);},
			searchFunctionAdvanced	: function() {return searchCcyList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Currency by Code') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCcyCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Code') ?>" maxlength="3" data-parsley-maxlength="3" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCcyName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Numeric Code') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCcyNumericCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Numeric Code') ?>" maxlength="3" data-parsley-maxlength="3" data-parsley-pattern="[0-9]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be numeric.') ?>">\
					</div>\
				</div>\
			'
		});
	}

    function resetCcyForm() {
		$('#formCcyData').trigger('reset');
		$('#formCcyData').parsley().reset();
		$('#inputCcyCode').attr('readonly', false);

		if (_action == 'edit') {
			$('#inputCcyCode').val(_temporaryData.code);
			$('#inputCcyCode').attr('readonly', true);
			$('#inputCcyName').val(_temporaryData.name);
			$('#inputCcyNumericCode').selectpicker('val', _temporaryData.numeric_code);
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getCcyDetail(ccyCode)
	{
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/currency/get-currency-detail-by-code']) ?>',
			data 	: 
			{
				'ccyCode' : ccyCode
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);

				$('#modalCcyTitle').html('<?= Yii::t('app', 'Edit Currency') ?>');
				resetCcyForm();
				$('#modalCcyForm').modal('show');
			}
		});
	}

	function showModalCcyDetail(ccyCode) {
		_action = 'edit';
		getCcyDetail(ccyCode);
	}

    function codeFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.code}" onclick="showModalCcyDetail(this.dataset.code)">${value}</label>`;
	}
</script>