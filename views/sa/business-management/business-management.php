<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Business Management';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddBusinessMember"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped dtr-inline" id="tblBusinessList" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="name" data-sortable="true" data-width="15%"><?= Yii::t('app', 'Name') ?></th>
					<th data-field="domain" data-sortable="true" data-width="10%" data-formatter="nameFormatter" ><?= Yii::t('app', 'Domain') ?></th>
					<th data-field="user_id" data-sortable="true" data-width="10%" ><?= Yii::t('app', 'User ID') ?></th>
					<th data-field="status" data-formatter="statusFormatter" data-sortable="true" data-width="10%" ><?= Yii::t('app', 'Status') ?></th>
					<th data-field="admin_name" data-sortable="false" data-width="10%" ><?= Yii::t('app', 'Admin Name') ?></th>
					<th data-field="admin_phone" data-sortable="false" data-width="10%" ><?= Yii::t('app', 'Admin Phone') ?></th>
					<th data-field="admin_email" data-formatter="emailFormatter" data-sortable="false" data-width="10%" ><?= Yii::t('app', 'Admin Email') ?></th>
					<th data-field="office_name" data-sortable="false" data-width="10%" ><?= Yii::t('app', 'Office Name') ?></th>
					<th data-field="office_code" data-sortable="false" data-width="10%" ><?= Yii::t('app', 'Office Code') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Businesses -->
<div class="modal fade" id="modalBusiness" aria-hidden="true" aria-labelledby="modalBusinessTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalBusinessTitle"><?= Yii::t('app', 'Add Business Management') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeBusinessModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
                <ul id="modalMainTabs" class="nav nav-tabs">
                    <li id="liBusinessDetailTabAdd" class="nav-item active">
                        <a class="nav-link" href="#businessDetailTab" role="tab" data-toggle="tab" aria-controls="businessDetailTab"><?=Yii::t('app', 'Business Detail')?></a>
                    </li>
                    <li id="liAdminDetailTabAdd" class="nav-item">
                        <a class="nav-link" href="#adminDetailTab" role="tab" data-toggle="tab" aria-controls="adminDetailTab"><?=Yii::t('app', 'Admin Detail')?></a>
                    </li>
                    <li id="liOfficeDetailTabAdd" class="nav-item">
                        <a class="nav-link" href="#officeDetailTab" role="tab" data-toggle="tab" aria-controls="officeDetailTab"><?=Yii::t('app', 'Office Detail')?></a>
                    </li>
					<li id="liAppDetailTab" class="nav-item">
                        <a class="nav-link" href="#appDetailTab" role="tab" data-toggle="tab" aria-controls="appDetailTab"><?=Yii::t('app', 'Apps')?></a>
                    </li>
					<li id="liGvTab" class="nav-item">
                        <a class="nav-link" href="#gvDetailTab" role="tab" data-toggle="tab" aria-controls="gvDetailTab"><?=Yii::t('app', 'Global Variables')?></a>
                    </li>
                </ul>
				<!--Tab panes-->
                <div class="tab-content" id="divModalTabs">
					<!-- Business Detail Tab -->
                    <div role="tabpanel" class="tab-pane active" id="businessDetailTab">
						<form id="formBusinessDetailData" class="form" autocomplete="off">
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>
								<div class="col-sm-8">
									<input id="inputBusinessName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="128" data-parsley-required data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Domain') ?></label>
								<div class="col-sm-8">
									<input id="inputBusinessDomain" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Domain') ?>" maxlength="128" data-parsley-required data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Address') ?></label>
								<div class="col-sm-8">
									<textarea id="inputBusinessAddress" name="inputBusinessAddress" rows="5" class="form-control form-control-sm no-resize" placeholder="<?= Yii::t('app', 'Address') ?>" maxlength="1028" data-parsley-required data-parsley-maxlength="1028"></textarea>
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Country') ?></label>
								<div class="col-sm-8">
                                    <select id="inputBusinessCountry" name="inputBusinessCountry" class="form-control form-control-sm selectpicker" title="<?= Yii::t('app', 'Select Country') ?>" data-actions-box="true" data-size="6" data-live-search="true" data-parsley-required data-parsley-errors-container="#errBusinessCountry">
									</select>
									<div class="help-block text-danger" id="errBusinessCountry"></div>
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'City') ?></label>
								<div class="col-sm-8">
									<select id="inputBusinessCity" name="inputBusinessCity" class="form-control form-control-sm selectpicker" title="<?= Yii::t('app', 'Select City') ?>" data-actions-box="true" data-size="6" data-live-search="true" data-parsley-required data-parsley-errors-container="#errBusinessCity">
									</select>
									<div class="help-block text-danger" id="errBusinessCity"></div>
								</div>
							</div>
							<div class="modal-footer pb-0 pr-0">
								<button type="button" class="btn btn-sm btn-secondary btnReset" onclick="resetBusinessDetailForm('formBusinessDetailData');"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
								<button class="btn btn-sm btn-primary btnNext" form="formBusinessDetailData" type="submit"><?= Yii::t('app', 'Next') ?> <i class="bi bi-arrow-right"></i></button>
								<button class="btn btn-sm btn-primary btnSubmit" form="formBusinessDetailData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
							</div>
						</form>
					</div>
					<!-- Admin Detail Tab -->
					<div role="tabpanel" class="tab-pane fade" id="adminDetailTab">
						<form id="formAdminDetailData" class="form" autocomplete="off">
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'User ID') ?></label>
								<div class="col-sm-8">
									<input id="inputUserId" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'User ID') ?>" maxlength="40" data-parsley-required data-parsley-maxlength="40" data-parsley-pattern="[a-zA-Z0-9]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>" data-parsley-length="[8, 40]" data-parsley-whitespace="trim" onkeypress="return event.charCode != 32">
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>
								<div class="col-sm-4 pr-1">
									<input id="inputFirstName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'First Name') ?>" maxlength="32" data-parsley-required data-parsley-maxlength="32" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
								</div>
								<div class="col-sm-4 pl-1">
									<input id="inputLastName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Last Name') ?>" maxlength="32" data-parsley-required data-parsley-maxlength="32" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Gender') ?></label>
								<div class="col-sm-8">
									<select id="inputGender" name="inputGender" class="form-control form-control-sm selectpicker" data-size="2" title="Select Gender" data-parsley-errors-container="#errGender">
										<option value="M"><?= Yii::t('app', 'Male') ?></option>
										<option value="F"><?= Yii::t('app', 'Female') ?></option>
									</select>
									<div class="help-block text-danger" id="errGender"></div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-control-label col-sm-4" for=""><?= Yii::t('app', 'Birthdate') ?></label>
								<div class="col-sm-8">
									<div class="input-group">
										<input name="inputBirthdate" id="inputBirthdate" type="text" class="form-control form-control-sm date" placeholder="<?= Yii::t('app', 'Birthate') ?>" maxlength="10" data-parsley-maxlength="10" data-parsley-errors-container="#errBirthdate">
									</div>
									<div class="help-block text-danger" id="errBirthdate"></div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Email') ?></label>
								<div class="col-sm-8">
									<input id="inputEmail" type="email" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Email') ?>" maxlength="128" data-parsley-maxlength="128" data-parsley-type="email">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Phone') ?></label>
								<div class="col-sm-8">
									<input id="inputPhone" type="text" class="form-control form-control-sm" style="" placeholder="<?= Yii::t('app', 'Phone') ?>" maxlength="40" data-parsley-maxlength="40" data-parsley-pattern="[0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value cannot be negative number.') ?>">
								</div>
							</div>
							<div class="modal-footer pb-0 pr-0">
								<button type="button" class="btn btn-sm btn-default toolbar-btn btnReset" onclick="resetBusinessDetailForm('formAdminDetailData');"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
								<button class="btn btn-sm btn-primary btnBack" form="formAdminDetailData" type="button"><i class="bi bi-arrow-left"></i> <?= Yii::t('app', 'Back') ?></button>
								<button class="btn btn-sm btn-primary btnNext" form="formAdminDetailData" type="submit"><?= Yii::t('app', 'Next') ?> <i class="bi bi-arrow-right"></i></button>
								<button class="btn btn-sm btn-primary btnSubmit" form="formAdminDetailData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
							</div>
						</form>
					</div>
					<!-- Office Detail Tab -->
					<div role="tabpanel" class="tab-pane fade" id="officeDetailTab">
						<form id="formOfficeDetailData" class="form" autocomplete="off">
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office Code') ?></label>
								<div class="col-sm-8">
									<input id="inputAddOfficeCode" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Office Code') ?>" maxlength="60" data-parsley-required data-parsley-maxlength="60">
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office Name') ?></label>
								<div class="col-sm-8">
									<input id="inputAddOfficeName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Office Name') ?>" maxlength="120" data-parsley-required data-parsley-maxlength="120" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Country') ?></label>
								<div class="col-sm-8">
									<select id="inputAddCountry" name="inputAddCountry" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Country') ?>" data-parsley-required data-parsley-errors-container="#errAddCountry">
									</select>
									<div class="help-block text-danger" id="errAddCountry"></div>
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'City') ?></label>
								<div class="col-sm-8">
									<select id="inputAddCity" name="inputAddCity" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select City') ?>" data-parsley-required data-parsley-errors-container="#errAddCity">
									</select>
									<div class="help-block text-danger" id="errAddCity"></div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Address') ?></label>
								<div class="col-sm-8">
									<textarea id="inputAddAddress" name="inputAddAddress" rows="5" class="form-control form-control-sm no-resize" placeholder="<?= Yii::t('app', 'Address') ?>" maxlength="1028" data-parsley-maxlength="1028"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Phone') ?></label>
								<div class="col-sm-8">
									<input id="inputAddPhone" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Phone') ?>" maxlength="40" data-parsley-maxlength="40" ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Fax') ?></label>
								<div class="col-sm-8">
									<input id="inputAddFax" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Fax') ?>" maxlength="20" data-parsley-maxlength="20" ?>
								</div>
							</div>
							<div class="modal-footer pb-0 pr-0">
								<button type="button" class="btn btn-sm btn-default btnReset" onclick="resetBusinessDetailForm('formOfficeDetailData');"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
								<button class="btn btn-sm btn-primary btnBack" form="formOfficeDetailData" type="button"> <i class="bi bi-arrow-left"></i> <?= Yii::t('app', 'Back') ?></button>
								<button class="btn btn-sm btn-primary btnSubmit" form="formOfficeDetailData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
							</div>
						</form>
					</div>
					<!-- App Detail Tab -->
					<div role="tabpanel" class="tab-pane fade" id="appDetailTab">
						<form id="formAppDetailTab" class="form" autocomplete="off">
						</form>
					</div>
					<!-- Global Variables Tab -->
					<div role="tabpanel" class="tab-pane fade" id="gvDetailTab">
						<form id="formGvDetailTab" class="form" autocomplete="off">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _businessList = <?= $businessList; ?>;
	var _countryList = <?= $countryList; ?>;
	var _cityList = <?= $cityList; ?>;
	var _groupList = <?= $groupList; ?>;
	var _officeList = <?= $officeList; ?>;
	var _currActiveTab = 'liBusinessDetailTab';
	var _action = '';
	var _temporaryData = [];
	var _arrTab = ['businessDetailTab', 'adminDetailTab', 'officeDetailTab', 'appDetailTab', 'gvDetailTab'];

    var _tblBusinessList = {
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
		sortName        : ['first_name'],
		sortOrder       : ['ASC'],
		data 			: _businessList.rows,
		totalRows 		: _businessList.total,
		url             : '<?= Url::to(['sa/business-management/get-business-list']) ?>',
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
				p.search = [$('#inputSearchBusinessName').val(), $('#inputSearchBusinessDomain').val(), $('#inputSearchAdminPhone').val(), $('#inputSearchAdminUserId').val(), $('#inputSearchAdminName').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function(){
        $('#tblBusinessList').bootstrapTableWrapper(_tblBusinessList);
		generateGlobalSearchAdminList();
		generateCountryList();
		generateGroupList();
		generateOfficeList();

		$('#btnAddBusinessMember').off('click').on('click', function(){
			_action = 'add';
			setBusinessModal();
			$('#modalBusinessTitle').html('<?= Yii::t('app', 'Add Business Management') ?>');
			$('#modalBusiness').modal('show');
		});

		$('#modalMainTabs > li').click(function(e)
        {
            let tabId = this.id;

            if (_currActiveTab != tabId)
            {
                $('#' + _currActiveTab).removeClass('active');
                $('#' + tabId).addClass('active');

                _currActiveTab = tabId;
            }
        });

		$('.btnNext').click(function(){
			let form = $('#' + $(this).attr('form'));

			if (form.parsley().isValid())
			{
				let tab = form.parent().attr('id');

				for (let i = 0 ; i < _arrTab.length ; i++)
				{
					if (tab == _arrTab[i])
					{
						$('a[href="#' + _arrTab[i+1] + '"]').click();
					}
				}
			}
		});

        $('.btnBack').click(function(){
			let form = $('#' + $(this).attr('form'));

			let tab = form.parent().attr('id');

			for (let i = 0 ; i < _arrTab.length ; i++)
			{
				if (tab == _arrTab[i])
				{
					form.parsley().reset();

					$('a[href="#' + _arrTab[i-1] + '"]').click();
				}
			}
		});

		// Form Business Detail
		$('#formBusinessDetailData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

        $('#formBusinessDetailData input, #formBusinessDetailData select').on('change keyup', function () {
            $(this).parsley().validate();
        });

		$('#formBusinessDetailData').submit(function(e){
			e.preventDefault();

			if (_action == 'edit') {
                
			}
		});

		// Form Admin Detail
		$('#formAdminDetailData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

        $('#formAdminDetailData input, #formAdminDetailData select').on('change keyup', function () {
            $(this).parsley().validate();
        });

		$('#formAdminDetailData').submit(function(e){
			e.preventDefault();

			if (_action == 'edit') {
                
			}
		});

		// Form Office Detail
		$('#formOfficeDetailData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

        $('#formOfficeDetailData input, #formOfficeDetailData select').on('change keyup', function () {
            $(this).parsley().validate();
        });

		$('#formOfficeDetailData').submit(function(e){
			e.preventDefault();

			if (_action == 'edit') {
                
			}
		});
    });

	function closeBusinessModal()
    {
        $('a[href="#businessDetailTab"]').click();
		resetBusinessDetailForm();
    }

	function resetBusinessDetailForm(formId)
	{
		// if (_action == 'add') {
		// 	generateCountryList();
		// 	generateCityList('', '');
		// }

        // Business Detail Form
		if (formId == null || formId == 'formBusinessDetailData')
		{
			$('#formBusinessDetailData').trigger('reset');
			$('#formBusinessDetailData').parsley().reset();

			if (_action == 'edit')
			{
				$('#modalMainTabs').removeClass('d-none');
				$('#inputBusinessName').val(_temporaryData.business_name);
				$('#inputBusinessDomain').val(_temporaryData.business_domain);
				$('#inputBusinessAddress').val(_temporaryData.business_address);
				$('#inputBusinessCountry').selectpicker('val', _temporaryData.business_country_code);
				generateCityList(_temporaryData.business_country_code, 'business');
				$('#inputBusinessCity').selectpicker('val', _temporaryData.business_city_code);
			}
		}

        // Admin Detail form
		if (formId == null || formId == 'formAdminDetailData')
		{
			$('#formAdminDetailData').trigger('reset');
			$('#formAdminDetailData').parsley().reset();

            if (_action == 'edit')
            {
                
            }
		}
        
        // Office Detail form
		if (formId == null || formId == 'formOfficeDetailData')
		{
			$('#formOfficeDetailData').trigger('reset');
			$('#formOfficeDetailData').parsley().reset();

            if (_action == 'edit')
            {
                
            }
		}

        $('.selectpicker').selectpicker('refresh');
	}

	function initiateAppsData() {
		let html = '';

		_appList.result.forEach(app => {
			if (app['OWNER_TYPE'] === 'SA') return;

			let count = app['PARENT_CODE'] === 0 ? 0 : app['COUNTER'];
			let tabClass = app['PARENT_CODE'] === 0 ? '' : `tab-${count}`;

			html += `
				<div class="form-check ${tabClass}">
					<input type="checkbox" data-app-code="${app['APP_CODE']}" 
						class="form-check-input checkbox-apps ${app['PARENT_CODE']}" 
						value="" onchange="checkboxBehavior('${app['APP_CODE']}')">
					<label class="form-check-label ml-2">
						${app['NAME']}
					</label>
				</div>`;
		});

		$('#adminDetailTab .form-horizontal').html(html);
	}

	function setBusinessModal() {
		$('a[href="#businessDetailTab"]').click();
		$('.btnSubmit').addClass('d-none');
        $('.btnBack').removeClass('d-none');
        $('.btnNext').removeClass('d-none');
        $('.btnReset').removeClass('d-none');
		$('#modalMainTabs').addClass('d-none');
		$('#inputAdminUserId').prop('disabled', true);
	}

	function searchBusinessList(flag) {
		_isAdvanceSearch = flag;
		$('#tblBusinessList').bootstrapTable('refresh');
	}

	function generateGlobalSearchAdminList() {
		$.globalSearch({
			searchFunction			: function() {return searchBusinessList(false);},
			searchFunctionAdvanced	: function() {return searchBusinessList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Business by Name') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchBusinessName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="128" data-parsley-minlength="1" data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Domain') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchBusinessDomain" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Domain') ?>" maxlength="128" data-parsley-minlength="1" data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Admin Phone') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchAdminPhone" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Phone') ?>" maxlength="128" data-parsley-minlength="1" data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Admin User ID') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchAdminUserId" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Admin User ID') ?>" maxlength="128" data-parsley-minlength="1" data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Admin Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchAdminName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Admin Name') ?>" maxlength="128" data-parsley-minlength="1" data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
			'
		});
	}

	function generateCountryList() {
    	const uniqueCountries = new Map();
		_countryList.forEach(c => uniqueCountries.set(c.code_2, c));

		const $inputBusinessCountry = $('#inputBusinessCountry');
		const $inputOfficeCountry = $('#inputOfficeCountry');

		uniqueCountries.forEach(c => {
			const opt = `<option value="${c.code_2}">${c.name} (${c.code_2})</option>`;
			$inputBusinessCountry.append(opt);
			$inputOfficeCountry.append(opt);
		});

		if ($inputBusinessCountry.hasClass('selectpicker')) {
			$inputBusinessCountry.selectpicker('refresh');
		}

		if ($inputOfficeCountry.hasClass('selectpicker')) {
			$inputOfficeCountry.selectpicker('refresh');
		}

		// Event listener untuk mengupdate kota berdasarkan negara yang dipilih
		$inputBusinessCountry.on('change', function() {
			const selectedCountry = this.value;
			generateCityList(selectedCountry, 'business');
		});

		$inputOfficeCountry.on('change', function() {
			const selectedCountry = this.value;
			generateCityList(selectedCountry, 'office');
		});
	}

	function generateCityList(countryCode, targetType) {
		let $inputCity;

		if (targetType === 'business') {
			$inputCity = $('#inputBusinessCity');
		} else if (targetType === 'office') {
			$inputCity = $('#inputOfficeCity');
		}
		
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

	function generateGroupList() {
        [...new Set(_groupList)].forEach(g => {
            const opt = `<option value="${g.id}">${g.name}</option>`;
            // $('#inputGroup').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

	function generateOfficeList() {
        [...new Set(_officeList)].forEach(o => {
            const opt = `<option value="${o.id}">${o.name}</option>`;
            // $('#inputOffice').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

	function getBusinessDetail(id) {
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/business-management/get-business-detail-by-id']) ?>',
			data 	: 
			{
				'businessId' : id
			},
			success	: function(response)
			{
				console.log(response);
				_temporaryData = $.parseJSON(response);

				$('#modalBusinessTitle').html('<?= Yii::t('app', 'Edit Business Management') ?>');
				resetBusinessDetailForm();
				$('#modalBusiness').modal('show');
			}
		});
	}

	function showModalBusinessDetail(id) {
		_action = 'edit';
		getBusinessDetail(id);
	}

	function nameFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.id}" onclick="showModalBusinessDetail(this.dataset.code)">${value}</label>`;
	}

	function emailFormatter(value, row, index) {
		return `<label class="text-lowercase" data-value="${value}">${value}</label>`;
	}

	function statusFormatter(value, row, index) {
		const statusLabels = {
			1: '<span class="badge badge-success"><?= Yii::t('app', 'Active') ?></span>',
			2: '<span class="badge badge-danger"><?= Yii::t('app', 'Inactive') ?></span>',
			3: '<span class="badge badge-danger"><?= Yii::t('app', 'Hold') ?></span>',
		};

        return statusLabels[value] || '<span class="badge badge-secondary">UNKNOWN</span>';
    }
</script>