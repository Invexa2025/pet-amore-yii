<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Admin List';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAdminList"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblAdminList" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="full_name" data-sortable="true" data-formatter="nameFormatter" data-width="20%"><?= Yii::t('app', 'Full Name') ?></th>
                    <th data-field="office_name" data-width="10%"><?= Yii::t('app', 'Office') ?></th>
					<th data-field="email" data-formatter="emailFormatter" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Email') ?></th>
					<th data-field="phone" data-formatter="phoneFormatter" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Phone') ?></th>
                    <th data-field="user_id" data-sortable="true" data-width="15%"><?= Yii::t('app', 'User ID') ?></th>
					<th data-field="status" data-sortable="true" data-formatter="userStatusFormatter" data-width="10%"><?= Yii::t('app', 'Status') ?></th>
					<th data-field="create_time" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Create Time') ?><br>(UTC <?= $officeUtc ?>)</th>
					<th data-field="last_action" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Last Action') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update/Lock/Unlock Admin -->
<div class="modal fade" id="modalAdminListForm" aria-hidden="true" aria-labelledby="modalAdminListTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalAdminListTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetAdminForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formAdminData" class="form" autocomplete="off">
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
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Group') ?></label>
						<div class="col-sm-8">
							<select id="inputGroup" name="inputGroup" class="form-control form-control-sm selectpicker" multiple  data-selected-text-format="count" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Group') ?>" data-parsley-required data-parsley-errors-container="#errGroup">
							</select>
							<div class="help-block text-danger" id="errGroup"></div>
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office') ?></label>
						<div class="col-sm-8">
							<select id="inputOffice" name="inputOffice" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Office') ?>" data-parsley-required data-parsley-errors-container="#errOffice">
							</select>
							<div class="help-block text-danger" id="errOffice"></div>
						</div>
					</div>                  
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetAdminForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button type="button" id="btnLockUnlock" class="btn btn-sm btn-secondary d-none"><i class="bi bi-lock" id="iconLockUnlock"></i> <span id="txtLockUnlock"><?= Yii::t('app', 'Lock') ?></span></button>
				<button class="btn btn-sm btn-primary" form="formAdminData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var _groupList = <?= $groupList; ?>;
	var _officeList = <?= $officeList; ?>;
	var _adminList = <?= $adminList; ?>;
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblAdminList = {
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
		sortName        : ['u.first_name'],
		sortOrder       : ['ASC'],
		data 			: _adminList.rows,
		totalRows 		: _adminList.total,
		url             : '<?= Url::to(['sa/admin-list/get-admin-list']) ?>',
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
				p.search = [$('#inputSearchName').val(), $('#inputSearchGender').val(), $('#inputSearchLastAction').val(), $('#inputSearchGroup').val().join(), $('#inputSearchOffice option:selected').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formAdminData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formAdminData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertAdmin();
			} else if (_action == 'edit') {
				updateAdmin();
			}
		});
        
        $('#tblAdminList').bootstrapTableWrapper(_tblAdminList);
		generateGlobalSearchAdminList();
		generateGroupList();
		generateOfficeList();

		$('.date').datetimepicker({
            format: 'DD/MM/YYYY',
            useCurrent: true,
            date: moment(),
        });

        $('#btnAdminList').on('click', function() {
			_action = 'add';
			$('#btnLockUnlock').addClass('d-none');
			resetAdminForm();
			$('#modalAdminListTitle').html('<?= Yii::t('app', 'Add Admin') ?>');
			$('#modalAdminListForm').modal('show');
		});

		$('#btnLockUnlock').on('click', function()
		{
			updateStatusAdmin();
		});
    });

	function insertAdmin() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add Admin') ?>?',
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
							'userId'     	: $('#inputUserId').val(),
							'firstName'     : $('#inputFirstName').val(),
							'lastName'  	: $('#inputLastName').val(),
							'gender'  		: $('#inputGender option:selected').val(),
							'birthdate' 	: $('#inputBirthdate').val(),
							'email' 		: $('#inputEmail').val(),
							'phone' 		: $('#inputPhone').val(),
							'group' 		: $('#inputGroup').val().join(),
							'office' 		: $('#inputOffice option:selected').val(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/admin-list/insert-admin') ?>',
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
													$('#modalAdminListForm').modal('hide');
													resetAdminForm();
													$('#tblAdminList').bootstrapTable('refresh');
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

	function updateAdmin() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this Admin') ?>?',
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
							'id' 			: _temporaryData.id,
							'firstName'     : $('#inputFirstName').val(),
							'lastName'  	: $('#inputLastName').val(),
							'gender'  		: $('#inputGender option:selected').val(),
							'birthdate' 	: $('#inputBirthdate').val(),
							'email' 		: $('#inputEmail').val(),
							'phone' 		: $('#inputPhone').val(),
							'group' 		: $('#inputGroup').val().join(),
							'office' 		: $('#inputOffice option:selected').val(),
						}
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/admin-list/update-admin') ?>',
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
													$('#modalAdminListForm').modal('hide');
													resetAdminForm();
													$('#tblAdminList').bootstrapTable('refresh');
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

	function updateStatusAdmin() {
		var isLocked = _temporaryData.status == 1;
		var newStatus = isLocked ? 2 : 1;
		var txtStatus = '<?= Yii::t('app', 'Are you sure you want to ') ?>' + (isLocked ? '<?= Yii::t('app', 'lock this user?') ?>' : '<?= Yii::t('app', 'unlock this user?') ?>');


		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : txtStatus,
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
							'id' : _temporaryData.id,
							'status' : newStatus
						};
				
						$.ajax({
							type    : 'POST',
							dataType: 'JSON',
							url     : '<?= Url::to(['sa/admin-list/update-status-admin']) ?>',
							data    : data,
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
													$('#modalAdminListForm').modal('hide');
													resetAdminForm();
													$('#tblAdminList').bootstrapTable('refresh');
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

	function searchAdminList(flag) {
		_isAdvanceSearch = flag;
		$('#tblAdminList').bootstrapTable('refresh');
	}

	function generateGlobalSearchAdminList() {
		$.globalSearch({
			searchFunction			: function() {return searchAdminList(false);},
			searchFunctionAdvanced	: function() {return searchAdminList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Admin by Name') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Name') ?>" maxlength="64" data-parsley-minlength="1" data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Gender') ?></label>\
					<div class="col-sm-8">\
						<select id="inputSearchGender" name="inputSearchGender" class="form-control form-control-sm selectpicker" data-size="2" title="Select Gender" data-parsley-errors-container="#errSearchGender">\
							<option value="M"><?= Yii::t('app', 'Male') ?></option>\
							<option value="F"><?= Yii::t('app', 'Female') ?></option>\
						</select>\
						<div class="help-block text-danger" id="errSearchGender"></div>\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-control-label col-sm-4" for=""><?= Yii::t('app', 'Last Action') ?></label>\
					<div class="col-sm-8">\
						<div class="input-group">\
							<input name="inputSearchLastAction" id="inputSearchLastAction" type="text" class="form-control form-control-sm date" placeholder="<?= Yii::t('app', 'Last Action') ?>" maxlength="10" data-parsley-maxlength="10" data-parsley-errors-container="#errSearchLastAction">\
						</div>\
						<div class="help-block text-danger" id="errSearchLastAction"></div>\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Group') ?></label>\
					<div class="col-sm-8">\
						<select id="inputSearchGroup" name="inputSearchGroup" class="form-control form-control-sm selectpicker" multiple  data-selected-text-format="count" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Group') ?>" data-parsley-errors-container="#errSearchGroup">\
						</select>\
						<div class="help-block text-danger" id="errSearchGroup"></div>\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Office') ?></label>\
					<div class="col-sm-8">\
						<select id="inputSearchOffice" name="inputSearchOffice" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Office') ?>" data-parsley-errors-container="#errSearchOffice">\
						</select>\
						<div class="help-block text-danger" id="errSearchOffice"></div>\
					</div>\
				</div>\
			'
		});
	}

    function resetAdminForm() {
		$('#formAdminData').trigger('reset');
		$('#formAdminData').parsley().reset();
		$('#inputUserId').attr('readonly', false);

		if (_action == 'edit') {
			$('#inputUserId').attr('readonly', true);
			$('#inputUserId').val(_temporaryData.user_id);
			$('#inputFirstName').val(_temporaryData.first_name);
			$('#inputLastName').val(_temporaryData.last_name);
			$('#inputGender').selectpicker('val', _temporaryData.gender);
			$('#inputBirthdate').val(_temporaryData.birthdate);
			$('#inputEmail').val(_temporaryData.email);
			$('#inputPhone').val(_temporaryData.phone);
			let group = _temporaryData.group_id.replace(/{|}/g, "").split(",");
			$('#inputGroup').selectpicker('val', group);
			$('#inputOffice').selectpicker('val', _temporaryData.office_id);

			if (_temporaryData.status == 1)
			{
				$('#iconLockUnlock').addClass('bi bi-lock');
				$('#txtLockUnlock').text('Lock');
			}
			else // unlock
			{
				$('#iconLockUnlock').addClass('bi bi-unlock');
				$('#txtLockUnlock').text('Unlock');
			}
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getAdminDetail(id) {
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/admin-list/get-admin-detail-by-id']) ?>',
			data 	: 
			{
				'userId' : id
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);

				$('#modalAdminListTitle').html('<?= Yii::t('app', 'Edit Admin') ?>');
				resetAdminForm();
				$('#btnLockUnlock').removeClass('d-none');
				$('#modalAdminListForm').modal('show');
			}
		});
	}

	function showModalAdminDetail(id) {
		_action = 'edit';
		getAdminDetail(id);
	}
	
	function generateGroupList() {
        [...new Set(_groupList)].forEach(g => {
            const opt = `<option value="${g.id}">${g.name}</option>`;
            $('#inputGroup, #inputSearchGroup').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

	function generateOfficeList() {
        [...new Set(_officeList)].forEach(o => {
            const opt = `<option value="${o.id}">${o.name}</option>`;
            $('#inputOffice, #inputSearchOffice').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

    function nameFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.id}" onclick="showModalAdminDetail(this.dataset.code)">${value}</label>`;
	}

	function emailFormatter(value, row, index) {
		return `<label class="text-lowercase" data-value="${value}">${value ? value : '-'}</label>`;
	}

	function phoneFormatter(value, row, index) {
		return `<label data-value="${value}">${value ? value : '-'}</label>`;
	}

    function userStatusFormatter(value, row, index) {
		const statusLabels = {
			1: '<span class="badge badge-success"><?= Yii::t('app', 'Active') ?></span>',
			2: '<span class="badge badge-danger"><?= Yii::t('app', 'Locked') ?></span>',
		};

        return statusLabels[value] || '<span class="badge badge-secondary">UNKNOWN</span>';
    }
</script>