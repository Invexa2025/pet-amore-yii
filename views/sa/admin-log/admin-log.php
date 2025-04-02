<?php
	Use yii\helpers\Url;
	$this->title = 'Pet Amore - Admin Log';
?>

<div class="row">
	<div class="col-lg-12">
		<div class="toolbar">
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped" id="tblAdminLog" width="100%">
				<thead>
					<th data-formatter="runningFormatter" data-sortable="false" data-width="5%"><?= Yii::t('app', 'No') ?></th>
					<th data-field="action_by" data-sortable="true" data-width="20%"><?= Yii::t('app', 'Action By') ?></th>
                    <th data-field="action" data-formatter="actionFormatter" data-sortable="true" data-width="20%"><?= Yii::t('app', 'Action') ?></th>
                    <th data-field="action_to" data-sortable="true" data-width="20%"><?= Yii::t('app', 'Action For') ?></th>
					<th data-field="action_time" data-sortable="false" data-width="20%"><?= Yii::t('app', 'Action Time') ?><br>(UTC <?= $officeUtc ?>)</th>
                    <th data-field="id" data-formatter="viewChangeFormatter" data-sortable="false" data-width="15%"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" id="modalAdminLog" aria-hidden="true" aria-labelledby="modalAdminLogTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalAdminLogTitle"><?= Yii::t('app', 'Admin Log Details') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-3 mr-3">
				<form id="formAdminLog" class="form" autocomplete="off">			
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Old Value') ?></label>
						<div class="col-sm-8"></div>
					</div>
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea id="txtOldValue" name="oldValue" rows="10" class="form-control form-control-sm no-resize" disabled placeholder="<?= Yii::t('app', 'Old Value') ?>"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'New Value') ?></label>
						<div class="col-sm-8"></div>
					</div>
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea id="txtNewValue" name="newValue" rows="10" class="form-control form-control-sm no-resize" disabled placeholder="<?= Yii::t('app', 'New Value') ?>"></textarea>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var _actionList = <?= $actionList ?>;
    var _adminLogList = <?= $adminLogList ?>;
    var _temporaryData = [];
	var _isAdvanceSearch = false;

    var _tblAdminLog = {
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
		sortName        : ['id'],
		sortOrder       : ['DESC'],
		data 			: _adminLogList.rows,
		totalRows 		: _adminLogList.total,
		url             : '<?= Url::to(['sa/admin-log/get-admin-log-list']) ?>',
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
				p.search = [$('#inputSearchAction').val(), ($('#inputSearchStartDate').val() + ' ' + $('#inputSearchStartDateTime').val()), ($('#inputSearchEndDate').val() + ' ' + $('#inputSearchEndDateTime').val())];
			}

			return p;	
		},
		formatNoMatches	: function()
		{
			return '<?= Yii::t('app', 'No data found') ?>';
		}
	};

    $(function() {        
        $('#tblAdminLog').bootstrapTableWrapper(_tblAdminLog);
		generateGlobalSearchAdminLog();
        generateActionList();

        $('.date-range').datetimepicker({
			format: 'DD/MM/YYYY',
			useCurrent: 'day'
		});

		$('#inputSearchStartDate').on('dp.change', function (e)
		{
			$('#inputSearchStartDate').parsley().reset();
		});

		$('#inputSearchEndDate').on('dp.change', function (e)
		{
			$('#inputSearchEndDate').parsley().reset();
		});
    });

	function searchAdminListList(flag) {
		_isAdvanceSearch = flag;
		$('#tblAdminLog').bootstrapTable('refresh');
	}

	function generateGlobalSearchAdminLog() {
		$.globalSearch({
			searchFunction			: function() {return searchAdminListList(false);},
			searchFunctionAdvanced	: function() {return searchAdminListList(true);},
			useDropdown				: true,
			placeholder				: '<?= Yii::t('app', 'Search Admin Log by Action') ?>',
			dropdownHtml			: '\
				<div class="form-group row">\
 					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Action') ?></label>\
					<div class="col-sm-8">\
						<select id="inputSearchAction" name="inputSearchAction" class="form-control form-control-sm selectpicker" data-actions-box="true" data-size="6" data-live-search="true" title="<?= Yii::t('app', 'Select Action') ?>" data-parsley-errors-container="#errSearchAction">\
                        </select>\
                        <div class="help-block text-danger" id="errSearchAction"></div>\
					</div>\
				</div>\
				<div class="form-group row required">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'Start Date') ?></label>\
					<div class="col-sm-5">\
						<input name="inputSearchStartDate" id="inputSearchStartDate" type="text" class="form-control form-control-sm date-range" placeholder="<?= Yii::t('app', 'Start Date') ?>" maxlength="16" data-parsley-required data-parsley-maxlength="16" data-parsley-required>\
					</div>\
					 <div class="col-sm-3">\
                        <input id="inputSearchStartDateTime" type="text" class="timeStart form-control form-control-sm"" maxlength="5" data-parsley-required data-parsley-maxlength="5" placeholder="<?= Yii::t('app', 'HH:MM') ?>">\
                    </div>\
				</div>\
				<div class="form-group row required">\
					<label class="col-sm-4 col-form-label"><?= Yii::t('app', 'End Date') ?></label>\
					<div class="col-sm-5">\
						<input name="inputSearchEndDate" id="inputSearchEndDate" type="text" class="form-control form-control-sm date-range" data-parsley-range-date placeholder="<?= Yii::t('app', 'End Date') ?>" maxlength="16" data-parsley-required data-parsley-maxlength="16" data-parsley-required>\
					</div>\
					 <div class="col-sm-3">\
                        <input id="inputSearchEndDateTime" type="text" class="timeEnd form-control form-control-sm"" maxlength="5" data-parsley-required data-parsley-maxlength="5" placeholder="<?= Yii::t('app', 'HH:MM') ?>">\
                    </div>\
				</div>\
			'
		});

        window.Parsley.addValidator('rangeDate', {
            validateString: function (value, requirement) {
                var startDateParts = $('#inputSearchStartDate').val().split('/');
                var endDateParts = $('#inputSearchEndDate').val().split('/');

                var startDate = new Date(startDateParts[2], startDateParts[1] - 1, startDateParts[0]);
                var endDate = new Date(endDateParts[2], endDateParts[1] - 1, endDateParts[0]);

                var difference = endDate - startDate;

                var daysDifference = Math.floor(difference / (1000 * 60 * 60 * 24));

                return daysDifference <= 7;
            },
            messages: {
                en: 'Date range must be within 1 week.'
            }
        });

		$('.timeStart').datetimepicker({
			date: moment().subtract(1, 'hours'),
            format: 'HH:mm'
        });

		$('.timeEnd').datetimepicker({
			date: moment(),
            format: 'HH:mm'
        });
	}

    function generateActionList() {
        [...new Set(_actionList)].forEach(c => {
            const opt = `<option value="${c.action}">${c.action}</option>`;
            $('#inputSearchAction').append(opt);
        });

        $('.selectpicker').selectpicker('refresh');
    }

	function getAdminLogDetail(id)
	{
		$.ajax({
			type 	: 'POST',
			datatype: 'JSON',
			url     : '<?= Url::to(['sa/admin-log/get-admin-log-detail-by-id']) ?>',
			data 	: 
			{
				'adminHistoryId' : id
			},
			success	: function(response)
			{
				_temporaryData = $.parseJSON(response);

                $('#formAdminLog').trigger('reset');
		        $('#formAdminLog').parsley().reset();

                $('#txtOldValue').val(_temporaryData.old_value);
		        $('#txtNewValue').val(_temporaryData.new_value);
				$('#modalAdminLog').modal('show');
			}
		});
	}

	function showModalAdminLogDetail(id) {
		getAdminLogDetail(id);
	}

    function actionFormatter(value, row, index) {
        return ('<span class="badge badge-primary">' + value + '</span>');
    }
    
    function viewChangeFormatter(value, row, index) {
		return `<label class="link-label" data-code="${row.id}" onclick="showModalAdminLogDetail(this.dataset.code)"><?= Yii::t('app', 'View Changes') ?></label>`;
	}
</script>