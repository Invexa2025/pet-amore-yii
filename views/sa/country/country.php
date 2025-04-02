<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Country';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddCountry"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblCountry" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="code_2" data-sortable="true" data-formatter="codeFormatter" data-width="15%"><?= Yii::t('app', 'Code 2') ?></th>
                    <th data-field="code_3" data-sortable="true" data-width="15%"><?= Yii::t('app', 'Code 3') ?></th>
                    <th data-field="ccy" data-sortable="true" data-width="15%"><?= Yii::t('app', 'Currency') ?></th>
					<th data-field="name" data-formatter="countryNameFormatter" data-sortable="true" data-width="40%"><?= Yii::t('app', 'Name') ?></th>
					<th data-field="phone_code" data-formatter="phoneCodeFormatter" data-sortable="true" data-width="10%" ><?= Yii::t('app', 'Phone Code') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update Country -->
<div class="modal fade" id="modalCountryForm" aria-hidden="true" aria-labelledby="modalCountryTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalCountryTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetCountryForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formCountryData" class="form" autocomplete="off">
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code 2') ?></label>
						<div class="col-sm-8">
							<input id="inputCountryCode2" type="text" class="form-control form-control-sm text-uppercase" placeholder="<?= Yii::t('app', 'Code 2') ?>" maxlength="2" data-parsley-required data-parsley-maxlength="2" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
                    <div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code 3') ?></label>
						<div class="col-sm-8">
							<input id="inputCountryCode3" type="text" class="form-control form-control-sm text-uppercase" placeholder="<?= Yii::t('app', 'Code 3') ?>" maxlength="3" data-parsley-required data-parsley-maxlength="3" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>
						<div class="col-sm-8">
							<input id="inputCountryName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-required data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
                    <div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Currency') ?></label>
						<div class="col-sm-8">
                            <select id="inputCcyCode" name="inputCcyCode" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Currency') ?>" data-parsley-required data-parsley-errors-container="#errCcyCode">
							</select>
							<div class="help-block text-danger" id="errCcyCode"></div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Phone Code') ?></label>
						<div class="col-sm-8">
                        <input id="inputCountryPhoneCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Phone Code') ?>" maxlength="10" data-parsley-maxlength="10" data-parsley-pattern="[0-9]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be numeric.') ?>">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetCountryForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button class="btn btn-sm btn-primary" form="formCountryData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _ccyList = <?= $ccyList ?>;
    var _countryList = <?= $countryList ?>;
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblCountry = {
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
		sortName        : ['code_2'],
		sortOrder       : ['ASC'],
		data 			: _countryList.rows,
		totalRows 		: _countryList.total,
		url             : '<?= Url::to(['sa/country/get-country-list']) ?>',
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
				p.search = [$('#inputSearchCountryCode2').val(), $('#inputSearchCountryCode3').val(), $('#inputSearchCountryName').val(), $('#inputSearchCountryCcy option:selected').val(), $('#inputSearchCountryPhoneCode').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formCountryData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formCountryData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertCountry();
			} else if (_action == 'edit') {
				updateCountry();
			}
		});
        
        $('#tblCountry').bootstrapTableWrapper(_tblCountry);
		generateGlobalSearchCountry();
        generateCcyList();

        $('#btnAddCountry').on('click', function() {
			_action = 'add';
			resetCountryForm();
			$('#modalCountryTitle').html('<?= Yii::t('app', 'Add Country') ?>');
			$('#modalCountryForm').modal('show');
		});
    });

	function insertCountry() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add Country') ?>?',
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
							'countryCode2' 			: $('#inputCountryCode2').val(),
							'countryCode3' 			: $('#inputCountryCode3').val(),
                            'countryName'           : $('#inputCountryName').val(),
                            'ccyCode'               : $('#inputCcyCode option:selected').val(),
							'countryPhoneCode' 	    : $('#inputCountryPhoneCode').val()
						};

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/country/insert-country') ?>',
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
													$('#modalCountryForm').modal('hide');
													resetCountryForm();
													$('#tblCountry').bootstrapTable('refresh');
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

	function updateCountry() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this Country') ?>?',
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
							'countryCode2' 			: $('#inputCountryCode2').val(),
							'countryCode3' 			: $('#inputCountryCode3').val(),
                            'countryName'           : $('#inputCountryName').val(),
                            'ccyCode'               : $('#inputCcyCode option:selected').val(),
							'countryPhoneCode' 	    : $('#inputCountryPhoneCode').val()
						};
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/country/update-country') ?>',
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
													$('#modalCountryForm').modal('hide');
													resetCountryForm();
													$('#tblCountry').bootstrapTable('refresh');
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

	function searchCountryList(flag) {
		_isAdvanceSearch = flag;
		$('#tblCountry').bootstrapTable('refresh');
	}

	function generateGlobalSearchCountry() {
		$.globalSearch({
			searchFunction			: function() {return searchCountryList(false);},
			searchFunctionAdvanced	: function() {return searchCountryList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Country by Code 2') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code 2') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCountryCode2" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Code 2') ?>" maxlength="2" data-parsley-maxlength="2" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">\
					</div>\
				</div>\
                <div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code 3') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCountryCode3" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Code 3') ?>" maxlength="3" data-parsley-maxlength="3" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCountryName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
                <div class="form-group row">\
                    <label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Currency') ?></label>\
                    <div class="col-sm-8">\
                        <select id="inputSearchCountryCcy" name="inputSearchCountryCcy" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Currency') ?>" data-parsley-required data-parsley-errors-container="#errSearchCountryCcy">\
                        </select>\
                        <div class="help-block text-danger" id="errSearchCountryCcy"></div>\
                    </div>\
                </div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Phone Code') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCountryPhoneCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Phone Code') ?>" maxlength="10" data-parsley-maxlength="10" data-parsley-pattern="[0-9]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be numeric.') ?>">\
					</div>\
				</div>\
			'
		});
	}

    function generateCcyList() {
        [...new Set(_ccyList)].forEach(c => {
            const opt = `<option value="${c.code}">${c.code} - ${c.name}</option>`;
            $('#inputCcyCode, #inputSearchCountryCcy').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

    function resetCountryForm() {
		$('#formCountryData').trigger('reset');
		$('#formCountryData').parsley().reset();
		$('#inputCountryCode2').attr('readonly', false);

		if (_action == 'edit') {
			$('#inputCountryCode2').val(_temporaryData.code_2);
			$('#inputCountryCode2').attr('readonly', true);
            $('#inputCountryCode3').val(_temporaryData.code_3);
			$('#inputCountryName').val(_temporaryData.name);
            $('#inputCcyCode').selectpicker('val', _temporaryData.ccy);
			$('#inputCountryPhoneCode').val(_temporaryData.phone_code);
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getCountryDetail(countryCode2)
	{
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/country/get-country-detail-by-code']) ?>',
			data 	: 
			{
				'countryCode2' : countryCode2
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);

				$('#modalCountryTitle').html('<?= Yii::t('app', 'Edit Country') ?>');
				resetCountryForm();
				$('#modalCountryForm').modal('show');
			}
		});
	}

	function showModalCountryDetail(countryCode2) {
		_action = 'edit';
		getCountryDetail(countryCode2);
	}

    function codeFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.code_2}" onclick="showModalCountryDetail(this.dataset.code)">${value}</label>`;
	}

	function phoneCodeFormatter(value, row, index) {
		return `${(value ? value : '-')}`;
	}

	function countryNameFormatter(value, row, index) {
		return `<label>${initCap(value)}</label>`;
	}
</script>