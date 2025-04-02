<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Offices';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddOffice"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblOfficeList" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="name" data-sortable="true" data-formatter="nameFormatter" data-width="25%"><?= Yii::t('app', 'Office Name') ?></th>
                    <th data-field="code" data-width="10%"><?= Yii::t('app', 'Office Code') ?></th>
                    <th data-field="city" data-formatter="cityNameFormatter" data-sortable="true" data-width="10%"><?= Yii::t('app', 'City') ?></th>
					<th data-field="timezone" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Timezone') ?></th>
					<th data-field="ccy" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Currency') ?></th>
					<th data-field="address" data-formatter="addressFormatter" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Address') ?></th>
					<th data-field="phone" data-formatter="phoneFormatter" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Phone') ?></th>
					<th data-field="fax" data-formatter="faxFormatter" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Fax') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update Offices -->
<div class="modal fade" id="modalOfficeForm" aria-hidden="true" aria-labelledby="modalOfficeTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalOfficeTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetOfficeForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formOfficeData" class="form" autocomplete="off">
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office Code') ?></label>
						<div class="col-sm-8">
							<input id="inputOfficeCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Office Code') ?>" maxlength="60" data-parsley-required data-parsley-maxlength="60">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office Name') ?></label>
						<div class="col-sm-8">
							<input id="inputOfficeName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Office Name') ?>" maxlength="120" data-parsley-required data-parsley-maxlength="120" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Country') ?></label>
						<div class="col-sm-8">
							<select id="inputCountry" name="inputCountry" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Country') ?>" data-parsley-required data-parsley-errors-container="#errCountry">
							</select>
							<div class="help-block text-danger" id="errCountry"></div>
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'City') ?></label>
						<div class="col-sm-8">
							<select id="inputCity" name="inputCity" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select City') ?>" data-parsley-required data-parsley-errors-container="#errCity">
							</select>
							<div class="help-block text-danger" id="errCity"></div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Address') ?></label>
						<div class="col-sm-8">
							<textarea id="inputAddress" name="inputAddress" rows="5" class="form-control form-control-sm no-resize" placeholder="<?= Yii::t('app', 'Address') ?>" maxlength="1028" data-parsley-maxlength="1028"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Phone') ?></label>
						<div class="col-sm-8">
							<input id="inputPhone" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Phone') ?>" maxlength="40" data-parsley-maxlength="40" ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Fax') ?></label>
						<div class="col-sm-8">
							<input id="inputFax" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Fax') ?>" maxlength="20" data-parsley-maxlength="20" ?>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetOfficeForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button class="btn btn-sm btn-primary" form="formOfficeData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var _countryList = <?= $countryList; ?>;
	var _cityList = <?= $cityList; ?>;
	var _officeList = <?= $officeList; ?>;
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblOffice = {
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
		sortName        : ['name'],
		sortOrder       : ['ASC'],
		data 			: _officeList.rows,
		totalRows 		: _officeList.total,
		url             : '<?= Url::to(['sa/offices/get-office-list']) ?>',
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
				p.search = [$('#inputSearchOfficeName').val(), $('#inputSearchOfficeCode').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formOfficeData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formOfficeData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertOffice();
			} else if (_action == 'edit') {
				updateOffice();
			}
		});
        
        $('#tblOfficeList').bootstrapTableWrapper(_tblOffice);
		generateGlobalSearchOffice();
		generateCountryList();
		generateCityList();

        $('#btnAddOffice').on('click', function() {
			_action = 'add';
			resetOfficeForm();
			generateCityList();
			$('#modalOfficeTitle').html('<?= Yii::t('app', 'Add Office') ?>');
			$('#modalOfficeForm').modal('show');
		});
    });

	function insertOffice() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add Office') ?>?',
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
							'officeCode': $('#inputOfficeCode').val(),
							'officeName': $('#inputOfficeName').val(),
							'countryCode': $('#inputCountry option:selected').val(),
							'cityCode': $('#inputCity option:selected').val(),
							'address': $('#inputAddress').val(),
							'phone': $('#inputPhone').val(),
							'fax': $('#inputFax').val(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/offices/insert-office') ?>',
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
													$('#modalOfficeForm').modal('hide');
													resetOfficeForm();
													$('#tblOfficeList').bootstrapTable('refresh');
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

	function updateOffice() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this Office') ?>?',
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
							'officeId': _temporaryData.id,
							'officeCode': $('#inputOfficeCode').val(),
							'officeName': $('#inputOfficeName').val(),
							'countryCode': $('#inputCountry option:selected').val(),
							'cityCode': $('#inputCity option:selected').val(),
							'address': $('#inputAddress').val(),
							'phone': $('#inputPhone').val(),
							'fax': $('#inputFax').val(),
						}
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/offices/update-office') ?>',
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
													$('#modalOfficeForm').modal('hide');
													resetOfficeForm();
													$('#tblOfficeList').bootstrapTable('refresh');
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

	function searchOfficeList(flag) {
		_isAdvanceSearch = flag;
		$('#tblOfficeList').bootstrapTable('refresh');
	}

	function generateGlobalSearchOffice() {
		$.globalSearch({
			searchFunction			: function() {return searchOfficeList(false);},
			searchFunctionAdvanced	: function() {return searchOfficeList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Office by Name') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office Code') ?></label>\
					<div class="col-sm-8">\
						<input id="inputOfficeCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Office Code') ?>" maxlength="60" data-parsley-maxlength="60" ?>\
					</div>\
				</div>\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputOfficeName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Office Name') ?>" maxlength="120" data-parsley-maxlength="120" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
			'
		});
	}

    function resetOfficeForm() {
		$('#formOfficeData').trigger('reset');
		$('#formOfficeData').parsley().reset();

		if (_action == 'edit') {
			$('#inputOfficeName').val(_temporaryData.name);
			$('#inputOfficeCode').val(_temporaryData.code);
			$('#inputCountry').val(_temporaryData.country_code);
			generateCityList(_temporaryData.country_code);
			$('#inputCity').val(_temporaryData.city_code);
			$('#inputAddress').val(_temporaryData.address);
			$('#inputPhone').val(_temporaryData.phone);
			$('#inputFax').val(_temporaryData.fax);
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getOfficeDetail(id) {
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/offices/get-office-detail-by-id']) ?>',
			data 	: 
			{
				'officeId' : id
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);
				console.log(_temporaryData);

				$('#modalOfficeTitle').html('<?= Yii::t('app', 'Edit Office') ?>');
				resetOfficeForm();
				$('#modalOfficeForm').modal('show');
			}
		});
	}

	function generateCountryList() {
    	const uniqueCountries = new Map();
		_countryList.forEach(c => uniqueCountries.set(c.code_2, c));

		const $inputCountry = $('#inputCountry');

		uniqueCountries.forEach(c => {
			const opt = `<option value="${c.code_2}">${c.name} (${c.code_2})</option>`;
			$inputCountry.append(opt);
		});

		if ($inputCountry.hasClass('selectpicker')) {
			$inputCountry.selectpicker('refresh');
		}

		// Event listener untuk mengupdate kota berdasarkan negara yang dipilih
		$inputCountry.on('change', function() {
			const selectedCountry = this.value;
			generateCityList(selectedCountry);
		});
	}

	function generateCityList(countryCode) {
		const $inputCity = $('#inputCity');
		$inputCity.empty();

		if (countryCode) {
			const filteredCities = _cityList.filter(city => city.country_code === countryCode);

			filteredCities.forEach(c => {
				const opt = `<option value="${c.code}">${c.name} (${c.code})</option>`;
				$inputCity.append(opt);
			});
		}

		if ($inputCity.hasClass('selectpicker')) {
			$inputCity.selectpicker('refresh');
		}
	}

	function showModalOfficeDetail(id) {
		_action = 'edit';
		getOfficeDetail(id);
	}

    function nameFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.id}" onclick="showModalOfficeDetail(this.dataset.code)">${value}</label>`;
	}

	function cityNameFormatter(value, row, index) {
		return `<label>${initCap(value)}</label>`;
	}

	function addressFormatter(value, row, index) {
		return `${(value ? initCap(value) : '-')}`;
	}

	function phoneFormatter(value, row, index) {
		return `${(value ? value : '-')}`;
	}

	function faxFormatter(value, row, index) {
		return `${(value ? value : '-')}`;
	}
</script>