<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Global Variables';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
			<button type="button" class="btn btn-primary" id="btnAddGv"><i class="bi bi-plus"></i> <?= Yii::t('app', 'Add') ?></button>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblGv" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="var_name" data-sortable="true" data-formatter="nameFormatter" data-width="30%"><?= Yii::t('app', 'Variable Name') ?></th>
                    <th data-field="var_desc" data-width="40%"><?= Yii::t('app', 'Description') ?></th>
                    <th data-field="var_group" data-sortable="true" data-width="25%"><?= Yii::t('app', 'Group') ?></th>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal Insert/Update Global Variable -->
<div class="modal fade" id="modalGvForm" aria-hidden="true" aria-labelledby="modalGvTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalGvTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetGvForm();">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formGvData" class="form" autocomplete="off">
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Variable Name') ?></label>
						<div class="col-sm-8">
							<input id="inputVarName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Variable Name') ?>" maxlength="128" data-parsley-required data-parsley-maxlength="128">
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Description') ?></label>
						<div class="col-sm-8">
							<input id="inputVarDesc" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Description') ?>" maxlength="512" data-parsley-required data-parsley-maxlength="512">
						</div>
					</div>
                    <div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Var Value') ?></label>
						<div class="col-sm-8">
							<input id="inputVarValue" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Var Value') ?>" maxlength="64" data-parsley-maxlength="64">
						</div>
					</div>
                    <div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Var Number') ?></label>
						<div class="col-sm-8">
							<input id="inputVarNumber" type="number" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Var Number') ?>" maxlength="32" data-parsley-maxlength="32" data-parsley-pattern="[0-9]+" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be numeric.') ?>">
						</div>
					</div>
                    <div class="form-group row required">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Group') ?></label>
						<div class="col-sm-8">
                            <select id="inputVarGroup" name="inputVarGroup" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Group') ?>" data-parsley-required data-parsley-errors-container="#errVarGroup">
							</select>
							<div class="help-block text-danger" id="errVarGroup"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" onclick="resetGvForm();"><i class="bi bi-arrow-counterclockwise"></i> <?= Yii::t('app', 'Reset') ?></button>
				<button class="btn btn-sm btn-primary" form="formGvData" type="submit"><i class="bi bi-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _gvList = <?= $gvList ?>;
    var _businessApplicationList = <?= $businessApplicationList ?>;
    var _action = '';
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblGv = {
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
		sortName        : ['var_name'],
		sortOrder       : ['ASC'],
		data 			: _gvList.rows,
		totalRows 		: _gvList.total,
		url             : '<?= Url::to(['sa/global-variables/get-gv-list']) ?>',
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
				p.search = [$('#inputSearchVarName').val(), $('#inputSearchVarDesc').val(), $('#inputSearchVarGroup option:selected').val()];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {
		$('#formGvData').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formGvData').submit(function(e) {
			e.preventDefault();

			if (_action == 'add') {
				insertGv();
			} else if (_action == 'edit') {
				updateGv();
			}
		});
        
        $('#tblGv').bootstrapTableWrapper(_tblGv);
		generateGlobalSearchCity();
        generateBusinessAppList();

        $('#btnAddGv').on('click', function() {
			_action = 'add';
			resetGvForm();
			$('#modalGvTitle').html('<?= Yii::t('app', 'Add Global Variable') ?>');
			$('#modalGvForm').modal('show');
		});
    });

	function insertGv() {
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to add Global Variable') ?>?',
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
							'gvVarName'         : $('#inputVarName').val(),
							'gvVarDesc'         : $('#inputVarDesc').val(),
                            'gvVarValue'        : $('#inputVarValue').val(),
                            'gvVarNumber'       : $('#inputVarNumber').val(),
                            'gvVarGroup'        : $('#inputVarGroup option:selected').val(),
						};

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/global-variables/insert-gv') ?>',
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
													$('#modalGvForm').modal('hide');
													resetGvForm();
													$('#tblGv').bootstrapTable('refresh');
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
			message        : '<?= Yii::t('app', 'Are you sure you want to edit this Global Variable') ?>?',
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
							'gvVarName'         : $('#inputVarName').val(),
							'gvVarDesc'         : $('#inputVarDesc').val(),
                            'gvVarValue'        : $('#inputVarValue').val(),
                            'gvVarNumber'       : $('#inputVarNumber').val(),
                            'gvVarGroup'        : $('#inputVarGroup option:selected').val(),
						};
						
						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('sa/global-variables/update-gv') ?>',
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
													$('#modalGvForm').modal('hide');
													resetGvForm();
													$('#tblGv').bootstrapTable('refresh');
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

	function searchGvList(flag) {
		_isAdvanceSearch = flag;
		$('#tblGv').bootstrapTable('refresh');
	}

	function generateGlobalSearchCity() {
		$.globalSearch({
			searchFunction			: function() {return searchGvList(false);},
			searchFunctionAdvanced	: function() {return searchGvList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Global Variable by Name') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Variable Name') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchVarName" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Variable Name') ?>" maxlength="128" data-parsley-maxlength="128">\
					</div>\
				</div>\
				<div class="form-group row">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Description') ?></label>\
					<div class="col-sm-8">\
						<input id="inputSearchVarDesc" type="text" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Description') ?>" maxlength="512" data-parsley-maxlength="512">\
					</div>\
				</div>\
				<div class="form-group row">\
                    <label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Group') ?></label>\
                    <div class="col-sm-8">\
                        <select id="inputSearchVarGroup" name="inputSearchVarGroup" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Group') ?>" data-parsley-errors-container="#errSearchGroup">\
                        </select>\
                        <div class="help-block text-danger" id="errSearchGroup"></div>\
                    </div>\
                </div>\
			'
		});
	}

    function generateBusinessAppList() {
        [...new Set(_businessApplicationList)].forEach(c => {
            const opt = `<option value="${c.code}">${c.name}</option>`;
            $('#inputVarGroup, #inputSearchVarGroup').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

    function resetGvForm() {
		$('#formGvData').trigger('reset');
		$('#formGvData').parsley().reset();
		$('#inputVarName').attr('readonly', false);

		if (_action == 'edit') {
			$('#inputVarName').val(_temporaryData.var_name);
			$('#inputVarName').attr('readonly', true);
			$('#inputVarDesc').val(_temporaryData.var_desc);
            $('#inputVarValue').val(_temporaryData.var_value);
            $('#inputVarNumber').val(_temporaryData.var_number);
            $('#inputVarGroup').selectpicker('val', _temporaryData.var_group);
		}

		$('.selectpicker').selectpicker('refresh');
	}

	function getGvDetail(varName)
	{
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/global-variables/get-gv-detail-by-var-name']) ?>',
			data 	: 
			{
				'gvVarName' : varName
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);

				$('#modalGvTitle').html('<?= Yii::t('app', 'Edit Global Variable') ?>');
				resetGvForm();
				$('#modalGvForm').modal('show');
			}
		});
	}

	function showModalCityDetail(varName) {
		_action = 'edit';
		getGvDetail(varName);
	}

    function nameFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.var_name}" onclick="showModalCityDetail(this.dataset.code)">${value}</label>`;
	}
</script>