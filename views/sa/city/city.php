<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - City';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddCity"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblCity" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="code" data-sortable="true" data-formatter="codeFormatter" data-width="20%"><?= Yii::t('app', 'Code') ?></th>
                    <th data-field="name" data-formatter="cityNameFormatter" data-sortable="true" data-width="25%"><?= Yii::t('app', 'Name') ?></th>
                    <th data-field="country_code" data-sortable="true" data-width="25%"><?= Yii::t('app', 'Country Code') ?></th>
					<th data-field="timezone" data-sortable="true" data-width="25%"><?= Yii::t('app', 'Timezone') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update City -->
<div class="modal fade" id="modalCityForm" aria-hidden="true" aria-labelledby="modalCityTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalCityTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetCityForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formCityData" class="form" autocomplete="off">
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code') ?></label>
						<div class="col-sm-8">
							<input id="inputCityCode" type="text" class="form-control form-control-sm text-uppercase" placeholder="<?= Yii::t('app', 'Code') ?>" maxlength="3" data-parsley-required data-parsley-maxlength="3" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>
						<div class="col-sm-8">
							<input id="inputCityName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-required data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">
						</div>
					</div>
                    <div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Country Code') ?></label>
						<div class="col-sm-8">
                            <select id="inputCityCountryCode" name="inputCityCountryCode" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Country') ?>" data-parsley-required data-parsley-errors-container="#errCityCountryCode">
							</select>
							<div class="help-block text-danger" id="errCityCountryCode"></div>
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Timezone') ?></label>
						<div class="col-sm-8">
                            <select id="inputCityTimezone" name="inputCityTimezone" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Timezone') ?>" data-parsley-required data-parsley-errors-container="#errCityTimezone">
							</select>
							<div class="help-block text-danger" id="errCityTimezone"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetCityForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button class="btn btn-sm btn-primary" form="formCityData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _countryList = <?= $countryList ?>;
    var _timezoneList = <?= $timezoneList ?>;
    var _cityList = <?= $cityList ?>;
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblCity = {
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
		data 			: _cityList.rows,
		totalRows 		: _cityList.total,
		url             : '<?= Url::to(['sa/city/get-city-list']) ?>',
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
				p.search = [$('#inputSearchCityCode').val(), $('#inputSearchCityName').val(), $('#inputSearchCityCountryCode option:selected').val(), $('#inputSearchCityTimezone option:selected').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formCityData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formCityData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertCity();
			} else if (_action == 'edit') {
				updateCity();
			}
		});
        
        $('#tblCity').bootstrapTableWrapper(_tblCity);
		generateGlobalSearchCity();
        generateCountryList();
        generateTimezoneList();

        $('#btnAddCity').on('click', function() {
			_action = 'add';
			resetCityForm();
			$('#modalCityTitle').html('<?= Yii::t('app', 'Add City') ?>');
			$('#modalCityForm').modal('show');
		});
    });

	function insertCity() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add City') ?>?',
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
							'cityCode' 			    : $('#inputCityCode').val(),
							'cityName' 			    : $('#inputCityName').val(),
                            'cityCountryCode'       : $('#inputCityCountryCode option:selected').val(),
                            'cityTimezone'          : $('#inputCityTimezone option:selected').val(),
						};

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/city/insert-city') ?>',
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
													$('#modalCityForm').modal('hide');
													resetCityForm();
													$('#tblCity').bootstrapTable('refresh');
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

	function updateCity() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this City') ?>?',
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
							'cityCode' 			    : $('#inputCityCode').val(),
							'cityName' 			    : $('#inputCityName').val(),
                            'cityCountryCode'       : $('#inputCityCountryCode option:selected').val(),
                            'cityTimezone'          : $('#inputCityTimezone option:selected').val(),
						};
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/city/update-city') ?>',
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
													$('#modalCityForm').modal('hide');
													resetCityForm();
													$('#tblCity').bootstrapTable('refresh');
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

	function searchCityList(flag) {
		_isAdvanceSearch = flag;
		$('#tblCity').bootstrapTable('refresh');
	}

	function generateGlobalSearchCity() {
		$.globalSearch({
			searchFunction			: function() {return searchCityList(false);},
			searchFunctionAdvanced	: function() {return searchCityList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search City by Code') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Code') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCityCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Code') ?>" maxlength="3" data-parsley-maxlength="3" data-parsley-pattern="[a-zA-Z]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchCityName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphabet.') ?>">\
					</div>\
				</div>\
                <div class="form-group row">\
                    <label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Country') ?></label>\
                    <div class="col-sm-8">\
                        <select id="inputSearchCityCountryCode" name="inputSearchCityCountryCode" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Country') ?>" data-parsley-errors-container="#errSearchCityCountryCode">\
                        </select>\
                        <div class="help-block text-danger" id="errSearchCityCountryCode"></div>\
                    </div>\
                </div>\
				<div class="form-group row">\
                    <label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Timezone') ?></label>\
                    <div class="col-sm-8">\
                        <select id="inputSearchCityTimezone" name="inputSearchCityTimezone" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Timezone') ?>" data-parsley-errors-container="#errSearchCityTimezone">\
                        </select>\
                        <div class="help-block text-danger" id="errSearchCityTimezone"></div>\
                    </div>\
                </div>\
			'
		});
	}

    function generateCountryList() {
        [...new Set(_countryList)].forEach(c => {
            const opt = `<option value="${c.code_2}">${c.name} (${c.code_2})</option>`;
            $('#inputCityCountryCode, #inputSearchCityCountryCode').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

    function generateTimezoneList() {
        [...new Set(_timezoneList)].forEach(c => {
            const opt = `<option value="${c.name}">${c.name} (${c.utc})</option>`;
            $('#inputCityTimezone, #inputSearchCityTimezone').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

    function resetCityForm() {
		$('#formCityData').trigger('reset');
		$('#formCityData').parsley().reset();
		$('#inputCityCode').attr('readonly', false);

		if (_action == 'edit') {
			$('#inputCityCode').val(_temporaryData.code);
			$('#inputCityCode').attr('readonly', true);
			$('#inputCityName').val(_temporaryData.name);
            $('#inputCityCountryCode').selectpicker('val', _temporaryData.country_code);
			$('#inputCityTimezone').selectpicker('val', _temporaryData.timezone);
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getCityDetail(code)
	{
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/city/get-city-detail-by-code']) ?>',
			data 	: 
			{
				'cityCode' : code
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);

				$('#modalCityTitle').html('<?= Yii::t('app', 'Edit City') ?>');
				resetCityForm();
				$('#modalCityForm').modal('show');
			}
		});
	}

	function showModalCityDetail(code) {
		_action = 'edit';
		getCityDetail(code);
	}

    function codeFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.code}" onclick="showModalCityDetail(this.dataset.code)">${value}</label>`;
	}

	function cityNameFormatter(value, row, index) {
		return `<label>${initCap(value)}</label>`;
	}
</script>