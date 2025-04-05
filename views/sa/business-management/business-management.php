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
	<div class="modal-dialog modal-dialog-centered modal-xl">
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
									<select id="inputOfficeCountry" name="inputAddCountry" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Country') ?>" data-parsley-required data-parsley-errors-container="#errAddCountry">
									</select>
									<div class="help-block text-danger" id="errAddCountry"></div>
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'City') ?></label>
								<div class="col-sm-8">
									<select id="inputOfficeCity" name="inputAddCity" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select City') ?>" data-parsley-required data-parsley-errors-container="#errAddCity">
									</select>
									<div class="help-block text-danger" id="errAddCity"></div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Address') ?></label>
								<div class="col-sm-8">
									<textarea id="inputOfficeAddress" name="inputAddAddress" rows="5" class="form-control form-control-sm no-resize" placeholder="<?= Yii::t('app', 'Address') ?>" maxlength="1028" data-parsley-maxlength="1028"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Phone') ?></label>
								<div class="col-sm-8">
									<input id="inputOfficePhone" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Phone') ?>" maxlength="40" data-parsley-maxlength="40" ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Fax') ?></label>
								<div class="col-sm-8">
									<input id="inputOfficeFax" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Fax') ?>" maxlength="20" data-parsley-maxlength="20" ?>
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
							<!-- Business Apps -->
							<div class="form-group row required">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'App Selection') ?></label>
								<div class="col-sm-8" id="divInputBusinessApps">
									<select id="inputBusinessApps" name="inputBusinessApps" class="form-control form-control-sm selectpicker" data-actions-box="true" data-selected-text-format="count" multiple data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select App') ?>" data-parsley-required data-parsley-errors-container="#errBusinessApps">
									</select>
									<div class="help-block text-danger" id="errBusinessApps"></div>
								</div>
							</div>
							<!-- Business Apps Preview -->
							<div class="form-group row">
								<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Summary') ?></label>
								<div class="col-sm-8">
									<textarea id="groupBusinessAppsSummary" rows="5" class="form-control form-control-sm no-resize" disabled></textarea>
								</div>
							</div>
							<div class="modal-footer pb-0 pr-0">
								<button type="button" class="btn btn-sm btn-default btnReset" onclick="resetBusinessDetailForm('formAppDetailTab');"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
								<button class="btn btn-sm btn-primary btnSubmit" form="formAppDetailTab" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
							</div>    
						</form>
					</div>
					<!-- Global Variables Tab -->
					<div role="tabpanel" class="tab-pane fade" id="gvDetailTab">
						<form id="formGvDetailTab" class="form" autocomplete="off">
							<!-- Global Variable Input append here -->
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	.gv-group-title-wrapper{
		border-bottom: 1px dashed var(--prussian-blue);
		padding: 5px 0;
	}
	
	.gv-group-title{
		color: var(--prussian-blue);
		font-size: 16px;
	}

	.gv-item-wrapper{
		padding: 10px 0;
	}
</style>

<script type="text/javascript">
    var _businessList = <?= $businessList; ?>;
	var _countryList = <?= $countryList; ?>;
	var _cityList = <?= $cityList; ?>;
	var _groupList = <?= $groupList; ?>;
	var _appList = <?= $appList ?>;
	var _globalVariableList = <?= $globalVariableList ?>;	
	var _officeList = <?= $officeList; ?>;
	var _currActiveTab = 'liBusinessDetailTab';
	var _arrPrivList = [];
	var _arrSelected = [];
	var _selectedApp = [];
	var _clickedApp = '';
	var _action = '';
	var _temporaryData = [];
	var _businessId;
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
		generateGroupApps();
		generateGlobalVariableData();

		$('#btnAddBusinessMember').off('click').on('click', function(){
			_action = 'add';
			setBusinessModal();
			resetBusinessDetailForm();
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

		/**
		 * BUSINESS APPS EVENT
		 */
		$('#inputBusinessApps, #inputSearchApps').on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) 
		{
			_clickedApp = '';

			if (typeof newValue != 'undefined')
			{
				// Push clicked item to array
				_clickedApp = $(this).find('option').eq(clickedIndex).val();

				_arrSelected = [];

				if (_clickedApp)
				{
					var element = $(this).find('option').eq(clickedIndex).val();
					_arrSelected.push(_clickedApp);

					findChildren(_clickedApp);

					for (var i = 0; i < _appList.length; i++)
					{
						if (_appList[i]['app_code'] == _clickedApp && newValue)
						{
							findParents(i);
						}
					}

					if (newValue)
					{
						_selectedApp = _selectedApp.concat(_arrSelected);
						_selectedApp = $.grep(_selectedApp, function(v, k)
						{
							return $.inArray(v ,_selectedApp) === k;
						});

						$('#' + this.id).val(_selectedApp);
						$('#' + this.id).selectpicker('refresh');
					}
					else
					{
						_arrSelected = _selectedApp.filter(function(el) 
						{
							return _arrSelected.indexOf(el) == -1;
						});

						$('#' + this.id).val(_arrSelected);
						$('#' + this.id).selectpicker('refresh');
						_selectedApp = _arrSelected;
						_arrSelected = [];
					}

					generateSummaryText();
				}
			}
		});

		$('.bs-select-all').on('click', function() 
		{
			_selectedApp = _arrPrivList;
			_arrSelected = _selectedApp;
			$('#inputApps, #inputSearchApps').val(_arrSelected);
			$('#inputApps, #inputSearchApps').selectpicker('refresh');
			generateSummaryText();
		});

		$('.bs-deselect-all').on('click', function() 
		{
			_selectedApp = [];
			_arrSelected = [];
			$('#inputApps, #inputSearchApps').val(_arrSelected);
			$('#inputApps, #inputSearchApps').selectpicker('refresh');
			generateSummaryText();
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
                updateBusinessDetail();
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
			insertBusiness();

			if (_action == 'edit') {
                
			}
		});
		
		// Form Business Apps
		$('#formAppDetailTab').parsley({
			errorClass: 'is-invalid text-danger',
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

        $('#formAppDetailTab input, #formAppDetailTab select').on('change keyup', function () {
            $(this).parsley().validate();
        });

		$('#formAppDetailTab').submit(function(e){
			e.preventDefault();

		});
		
		// Form Global Variable
		$('#formGvDetailTab').parsley({
			errorClass: 'is-invalid text-danger',
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

        $('#formGvDetailTab input, #formGvDetailTab select').on('change keyup', function () {
            $(this).parsley().validate();
        });

		$('#formGvDetailTab').submit(function(e){
			e.preventDefault();
			updateGlobalVariables();
		});
    });

	/**
	 * BUSINESS APPS HELPER FUNCTIONS
	 */
	function generateSummaryText() {
		let cntParent = 0;
		let summaryArr = [];

		_appList.forEach((app, i) => {
			if (!_selectedApp.includes(app.app_code)) return;

			let indentLevel = (app.parent_code !== 0) ? app.parent_code.split('-').length - 1 : 0;
			let desc = '&nbsp;'.repeat(indentLevel * 7);
			
			if (app.parent_code !== 0) {
				summaryArr.push(`${desc}${app.name}`);
			} else {
				cntParent++;
				summaryArr.push(`${cntParent}. &ensp;${app.name}`);
			}
		});		

		$('#groupBusinessAppsSummary').html(summaryArr.join('\r\n'));
		$('#divInputBusinessApps > .bootstrap-select.form-control').removeClass('is-invalid text-danger');
	}


	function findChildren(app) {
		let children = _appList.filter(item => item.parent_code == app);
		
		for (let child of children) {
			_arrSelected.push(child.app_code);
			findChildren(child.app_code);
		}
	}

	function findAppIndex(app) {
		return _appList.findIndex(item => item.app_code == app);
	}

	function findParents(i) {
		if (_appList[i] && _appList[i].parent_code != '0') {
			_arrSelected.push(_appList[i].parent_code);
			let parentIndex = findAppIndex(_appList[i].parent_code);
			if (parentIndex !== -1) findParents(parentIndex);
		}
	}

	function closeBusinessModal()
    {
        $('a[href="#businessDetailTab"]').click();
		resetBusinessDetailForm();
    }

	function setBusinessModal() {
		$('a[href="#businessDetailTab"]').click();
		$('.btnSubmit').addClass('d-none');
        $('.btnBack').removeClass('d-none');
        $('.btnNext').removeClass('d-none');
        $('.btnReset').removeClass('d-none');
		$('.btnSubmit[form="formOfficeDetailData"]').removeClass('d-none');
		$('#modalMainTabs').addClass('d-none');
		
		// Input fields
		$('#inputUserId').prop('disabled', false);
		$('#inputOfficeCode').prop('disabled', false);
		$('#inputOfficeName').prop('disabled', false);


		if (_action == 'edit')
		{
			$('.btnBack').addClass('d-none');
			$('.btnNext').addClass('d-none');
			$('.btnSubmit').removeClass('d-none');

			// Input fields
			$('#inputUserId').prop('disabled', true);
			$('#inputOfficeCode').prop('disabled', true);
			$('#inputOfficeName').prop('disabled', true);
		}
	}

	function resetBusinessDetailForm(formId)
	{
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
			}
		}

        // Admin Detail form
		if (formId == null || formId == 'formAdminDetailData')
		{
			$('#formAdminDetailData').trigger('reset');
			$('#formAdminDetailData').parsley().reset();

            if (_action == 'edit')
            {
                $('#inputUserId').val(_temporaryData.user_id);
				$('#inputFirstName').val(_temporaryData.first_name);
				$('#inputLastName').val(_temporaryData.last_name);
				$('#inputGender').selectpicker('val', _temporaryData.gender);
				$('#inputBirthdate').val(_temporaryData.birthdate);
				$('#inputEmail').val(_temporaryData.admin_email);
				$('#inputPhone').val(_temporaryData.admin_phone);
            }
		}
        
        // Office Detail form
		if (formId == null || formId == 'formOfficeDetailData')
		{
			$('#formOfficeDetailData').trigger('reset');
			$('#formOfficeDetailData').parsley().reset();

            if (_action == 'edit')
            {
                $('#inputOfficeCode').val(_temporaryData.office_code);
				$('#inputOfficeName').val(_temporaryData.office_name);
				$('#inputOfficeCountry').selectpicker('val', _temporaryData.office_country_code).trigger('change');
				$('#inputOfficeCity').selectpicker('val', _temporaryData.office_city_code);
				$('#inputOfficeAddress').val(_temporaryData.office_address);
				$('#inputOfficePhone').val(_temporaryData.office_phone);
				$('#inputOfficeFax').val(_temporaryData.office_fax)
            }
		}

		// Business Apps form
		if (formId == null || formId == 'formAppDetailTab')
		{
			$('#formAppDetailTab').trigger('reset');
			$('#formAppDetailTab').parsley().reset();
			_selectedApp = [];
			_arrSelected = [];
			$('#groupBusinessAppsSummary').html('');

			if (_action == 'edit')
			{

			}
		}

		// Global Variables form
		if (formId == null || formId == 'formGvDetailTab')
		{
			$('#formGvDetailTab').parsley().reset();
			$('#formGvDetailTab input').each(function() {
				let initialValue = $(this).attr('data-initial-value');
				$(this).val(initialValue);
			});
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

		const $inputOfficeCountry = $('#inputOfficeCountry');

		uniqueCountries.forEach(c => {
			const opt = `<option value="${c.code_2}">${c.name} (${c.code_2})</option>`;
			$inputOfficeCountry.append(opt);
		});

		if ($inputOfficeCountry.hasClass('selectpicker')) {
			$inputOfficeCountry.selectpicker('refresh');
		}

		// Event listener untuk mengupdate kota berdasarkan negara yang dipilih
		$inputOfficeCountry.on('change', function() {
			const selectedCountry = this.value;
			generateCityList(selectedCountry, 'office');
		});
	}

	function generateCityList(countryCode, targetType) {
		let $inputCity;

		$inputCity = $('#inputOfficeCity');
		
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

	function generateGroupApps() {
		if (_appList.length === 0) {
			$('#inputBusinessApps, #inputSearchApps').prop('title', 'No available App exists');
		} else {
			let options = [];
			
			_appList.forEach((app, i) => {
				let indentLevel = (app['parent_code'] != 0) ? app['parent_code'].split('-').length - 1 : 0;
				let indent = '&nbsp;'.repeat(indentLevel * 7);
				let style = (app['parent_code'] == 0) ? 'font-weight-bold' : '';
				
				// Jika bukan root, tambahkan indentasi dan simbol ">"
				let name = (app['parent_code'] != 0) ? `${indent}> ${app['name']}` : app['name'];
				
				options.push(`<option class="${style}" value="${app['app_code']}">${name}</option>`);
				_arrPrivList.push(app['app_code']);
			});

			$('#inputBusinessApps, #inputSearchApps').append(options.join('')).selectpicker('refresh');
		}
	}

	function generateGlobalVariableData() {
		let groupedGlobalVariable = {};
		let globalVariableForm = $('#formGvDetailTab');

		// Clear previous content
		globalVariableForm.html('');

		_globalVariableList.forEach(item => {
			if (!groupedGlobalVariable[item.var_group]) groupedGlobalVariable[item.var_group] = [];
			groupedGlobalVariable[item.var_group].push(item);
		});

		// Loop grouped global variable data and inject into form
		Object.keys(groupedGlobalVariable).forEach(group => {
			let groupHtml = `
				<div class="gv-wrapper">
					<div class="gv-group-title-wrapper">
						<h4 class="gv-group-title">${group}</h4>
					</div>
					<div class="gv-item-wrapper">
			`;

			groupedGlobalVariable[group].forEach((item, idx) => {
				groupHtml += `
					<div class="form-group row d-flex align-items-center">
						<label class="col-sm-2 col-form-label" id="inputVarName${idx + 1}">${item.var_name}</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="inputVarValue${idx + 1}" maxlength="128" 
								data-parsley-minlength="1" data-parsley-maxlength="128" data-parsley-pattern="[a-zA-Z0-9 ]+" 
								data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>"
								value="${item.var_value || ''}" 
								data-initial-value="${item.var_value || ''}">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="inputVarNumber${idx + 1}" maxlength="40" 
								data-parsley-maxlength="40" data-parsley-pattern="[0-9]+" 
								data-parsley-pattern-message="<?= Yii::t('app', 'This value should be numeric.') ?>"
								value="${item.var_number || ''}" 
								data-initial-value="${item.var_number || ''}">
						</div>
						<div class="col-sm-4">
							<textarea rows="2" class="form-control form-control-sm no-resize" disabled>${item.var_desc || ''}</textarea>
						</div>  
					</div>
				`;
			});

			groupHtml += `</div></div>`; // Close wrappers
			globalVariableForm.append(groupHtml);
		});

		// Append form buttons
		let globalVariableFormButtons = `
			<div class="modal-footer pb-0 pr-0">
				<button type="button" class="btn btn-sm btn-default btnReset" onclick="resetBusinessDetailForm('formGvDetailTab');">
					<i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?>
				</button>
				<button class="btn btn-sm btn-primary btnSubmit" form="formGvDetailTab" type="submit">
					<i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?>
				</button>
			</div>   
		`;

		globalVariableForm.append(globalVariableFormButtons);
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
				_temporaryData = $.parseJSON(response);
				_businessId = _temporaryData.business_id;

				$('#modalBusinessTitle').html('<?= Yii::t('app', 'Edit Business Management') ?>');
				setBusinessModal();
				resetBusinessDetailForm();
				$('#modalBusiness').modal('show');
			}
		});
	}

	function insertBusiness()
	{
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to create new business') ?>?',
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
							'businessName'	: $('#inputBusinessName').val(),
							'businessDomain' : $('#inputBusinessDomain').val(),
							'businessUserId' : $('#inputUserId').val(),
							'businessUserFirstName'	: $('#inputFirstName').val(),
							'businessUserLastName'	: $('#inputLastName').val(),
							'businessUserGender' : $('#inputGender option:selected').val(),
							'businessUserBirthdate'	: $('#inputBirthdate').val(),
							'businessUserEmail'	: $('#inputEmail').val(),
							'businessUserPhone'	: $('#inputPhone').val(),
							'businessOfficeCode' : $('#inputOfficeCode').val(),
							'businessOfficeName' : $('#inputOfficeName').val(),
							'businessOfficeCountry' : $('#inputOfficeCountry option:selected').val(),
							'businessOfficeCity' : $('#inputOfficeCity option:selected').val(),
							'businessOfficeAddress' : $('#inputOfficeAddress').val(),
							'businessOfficePhone' : $('#inputOfficePhone').val(),
							'businessOfficeFax' : $('#inputOfficeFax').val()
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/business-management/insert-business') ?>',
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
													$('#modalBusiness').modal('hide');
													resetBusinessDetailForm();
													$('#tblBusinessList').bootstrapTable('refresh');
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

	function updateBusinessDetail()
	{
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to update business detail') ?>?',
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
							'businessId': _businessId,
							'businessName'	: $('#inputBusinessName').val(),
							'businessDomain' : $('#inputBusinessDomain').val(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/business-management/update-business') ?>',
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
													$('#modalBusiness').modal('hide');
													resetBusinessDetailForm();
													$('#tblBusinessList').bootstrapTable('refresh');
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

	function updateBusinessAdmin()
	{
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to update business admin') ?>?',
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
							'businessUserId' : $('#inputUserId').val(),
							'businessUserFirstName'	: $('#inputFirstName').val(),
							'businessUserLastName'	: $('#inputLastName').val(),
							'businessUserGender' : $('#inputGender option:selected').val(),
							'businessUserBirthdate'	: $('#inputBirthdate').val(),
							'businessUserEmail'	: $('#inputEmail').val(),
							'businessUserPhone'	: $('#inputPhone').val(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/business-management/') ?>',
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
	
	function updateBusinessOffice()
	{
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to update business office') ?>?',
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
							'businessOfficeCode' : $('#inputOfficeCode').val(),
							'businessOfficeName' : $('#inputOfficeName').val(),
							'businessOfficeCountry' : $('#inputOfficeCountry option:selected').val(),
							'businessOfficeCity' : $('#inputOfficeCity option:selected').val(),
							'businessOfficeAddress' : $('#inputOfficeAddress').val(),
							'businessOfficePhone' : $('#inputOfficePhone').val(),
							'businessOfficeFax' : $('#inputOfficeFax').val(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/business-management/') ?>',
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

	function updateBusinessApps()
	{
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add business apps') ?>?',
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
							'businessApps'  	: $('#inputBusinessApps').val().join(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/business-management/') ?>',
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
	
	function updateGlobalVariables()
	{
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to update global variables') ?>?',
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
						let globalVariableData = [];

						$('#formGvDetailTab .form-group').each(function () {
							let varName = $(this).find('label').text().trim();
							let varValue = $(this).find('input[type="text"]').eq(0).val();
							let varNumber = $(this).find('input[type="text"]').eq(1).val();

							globalVariableData.push({
								var_name: varName,
								var_value: varValue,
								var_number: varNumber
							});
						});

						$.ajax({
							type : 'POST',
							data : {
								'globalVariables': globalVariableData
							},
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/business-management/update-global-variables') ?>',
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

	function showModalBusinessDetail(id) {
		_action = 'edit';
		getBusinessDetail(id);
	}

	function nameFormatter(value, row, index) {
		return `<label class="link-label" onclick="showModalBusinessDetail(${row.id})">${value}</label>`;
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