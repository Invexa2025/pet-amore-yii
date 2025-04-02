<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Groups';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddGroup"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblGroup" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="name" data-sortable="true" data-formatter="nameFormatter" data-width="20%"><?= Yii::t('app', 'Name') ?></th>
                    <th data-field="description" data-width="35%"><?= Yii::t('app', 'Description') ?></th>
                    <th data-field="create_time" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Create Time') ?><br>(UTC <?= $officeUtc ?>)</th>
					<th data-field="create_by" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Create By') ?></th>
					<th data-field="update_time" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Update Time') ?><br>(UTC <?= $officeUtc ?>)</th>
					<th data-field="update_by" data-sortable="true" data-width="10%"><?= Yii::t('app', 'Update By') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update/Delete Groups -->
<div class="modal fade" id="modalGroupsForm" aria-hidden="true" aria-labelledby="modalGroupsTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalGroupsTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetGroupsForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formGroupsData" class="form" autocomplete="off">
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Group Name') ?></label>
						<div class="col-sm-8">
							<input id="inputGroupName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Group Name') ?>" maxlength="64" data-parsley-required data-parsley-minlength="1" data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Description') ?></label>
						<div class="col-sm-8">
							<input id="inputGroupDesc" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Description') ?>" maxlength="320" data-parsley-required data-parsley-minlength="1" data-parsley-maxlength="320" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'App Selection') ?></label>
						<div class="col-sm-8" id="divInputApps">
							<select id="inputApps" name="inputApps" class="form-control form-control-sm selectpicker" data-actions-box="true" data-selected-text-format="count" multiple data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select App') ?>" data-parsley-required data-parsley-errors-container="#errApps">
							</select>
							<div class="help-block text-danger" id="errApps"></div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea id="groupAppsSummary" name="summary" rows="5" class="form-control form-control-sm no-resize" disabled placeholder="<?= Yii::t('app', 'Summary') ?>"></textarea>
						</div>
					</div>                  
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetGroupsForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button type="button" id="btnDelete" class="btn btn-sm btn-danger d-none"><i class="bi bi-trash"></i> <?= Yii::t('app', 'Delete') ?></button>
				<button class="btn btn-sm btn-primary" form="formGroupsData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _appList = <?= $appList ?>;
    var _groupList = <?= $groupList ?>;
	var _arrPrivList = [];
	var _arrSelected = [];
	var _selectedApp = [];
	var _clickedApp = '';
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblGroup = {
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
		data 			: _groupList.rows,
		totalRows 		: _groupList.total,
		url             : '<?= Url::to(['sa/groups/get-group-list']) ?>',
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
				p.search = [$('#inputSearchGroupName').val(), $('#inputSearchGroupDesc').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formGroupsData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formGroupsData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertGv();
			} else if (_action == 'edit') {
				updateGv();
			}
		});
        
        $('#tblGroup').bootstrapTableWrapper(_tblGroup);
		generateGlobalSearchGroup();
		generateGroupApps();

        $('#btnAddGroup').on('click', function() {
			_action = 'add';
			resetGroupsForm();
			$('#btnDelete').addClass('d-none');
			$('#modalGroupsTitle').html('<?= Yii::t('app', 'Add Group') ?>');
			$('#modalGroupsForm').modal('show');
		});

		$('#btnDelete').on('click', function() {
			deleteGroup();
		});

		$('#inputApps, #inputSearchApps').on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) 
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
    });

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

		$('#groupAppsSummary').html(summaryArr.join('\r\n'));
		$('#divInputApps > .bootstrap-select.form-control').removeClass('is-invalid text-danger');
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

	function insertGv() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add Group') ?>?',
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
							'groupName'     : $('#inputGroupName').val(),
							'groupDesc'     : $('#inputGroupDesc').val(),
							'groupApp'  	: $('#inputApps').val().join(),
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/groups/insert-group') ?>',
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
													$('#modalGroupsForm').modal('hide');
													resetGroupsForm();
													$('#tblGroup').bootstrapTable('refresh');
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

	function updateGv() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this Group') ?>?',
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
							'groupName'     : $('#inputGroupName').val(),
							'groupDesc'     : $('#inputGroupDesc').val(),
							'apps'  		: $('#inputApps').val(),
						}
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/groups/update-group') ?>',
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
													$('#modalGroupsForm').modal('hide');
													resetGroupsForm();
													$('#tblGroup').bootstrapTable('refresh');
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

	function deleteGroup() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to delete Group') ?>?',
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
							'groupId' : _temporaryData['group'].id
						};
				
						$.ajax({
							type    : 'POST',
							dataType: 'JSON',
							url     : '<?= Url::to(['sa/groups/delete-group']) ?>',
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
													$('#modalGroupsForm').modal('hide');
													resetGroupsForm();
													$('#tblGroup').bootstrapTable('refresh');
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

	function generateGroupApps() {
		if (_appList.length === 0) {
			$('#inputApps, #inputSearchApps').prop('title', 'No available App exists');
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

			$('#inputApps, #inputSearchApps').append(options.join('')).selectpicker('refresh');
		}

	}

	function searchGroupList(flag) {
		_isAdvanceSearch = flag;
		$('#tblGroup').bootstrapTable('refresh');
	}

	function generateGlobalSearchGroup() {
		$.globalSearch({
			searchFunction			: function() {return searchGroupList(false);},
			searchFunctionAdvanced	: function() {return searchGroupList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Group by Name') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Group Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchGroupName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Group Name') ?>" maxlength="64" data-parsley-minlength="1" data-parsley-maxlength="64" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Description') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchGroupDesc" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Description') ?>" maxlength="320" data-parsley-minlength="1" data-parsley-maxlength="320" data-parsley-pattern="[a-zA-Z0-9 ]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be alphanumeric.') ?>">\
					</div>\
				</div>\
			'
		});
	}

    function resetGroupsForm() {
		$('#formGroupsData').trigger('reset');
		$('#formGroupsData').parsley().reset();
		$('#inputVarName').attr('readonly', false);
		_selectedApp = [];
		_arrSelected = [];
		$('#groupAppsSummary').html('');

		if (_action != 'add') {
			if (_action == 'edit') {
				$('#inputGroupName').val(_temporaryData['group'].name);
				$('#inputGroupDesc').val(_temporaryData['group'].description);
			}

			for (var i = 0; i < _temporaryData['group_apps'].length; i++) {
				_selectedApp.push(_temporaryData['group_apps'][i]['app_code']);
				$('#inputApps').val(_selectedApp);
			}
			
			generateSummaryText();
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getGroupDetail(id) {
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/groups/get-group-detail-by-id']) ?>',
			data 	: 
			{
				'groupId' : id
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);
				resetGroupsForm();

				if (_action == 'edit') {
					$('#modalGroupsTitle').html('<?= Yii::t('app', 'Edit Group') ?>');
					$('#btnDelete').removeClass('d-none');
				} else if (_action == 'copy') {
					$('#modalGroupsTitle').html('<?= Yii::t('app', 'Copy Group') ?>');
					$('#btnDelete').addClass('d-none');
				}
				
				$('#modalGroupsForm').modal('show');
			}
		});
	}

	function showModalGroupDetail(id) {
		_action = 'edit';
		getGroupDetail(id);
	}

	function showCopyGroupModal(id)
	{
		_action = 'copy';
		getGroupDetail(id);
	}

    function nameFormatter(value, row, index) {
		return `
			<label class="link-label" data-code="${row.id}" onclick="showModalGroupDetail(this.dataset.code)">
				${value}
			</label>
			<button class="copy-btn" onclick="showCopyGroupModal('${row.id}')">COPY</button>
		`;
	}
</script>