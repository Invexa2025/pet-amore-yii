<?php
    use yii\helpers\Url;
?>

<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>
		<div class="col-sm-3 col-md-3 col-lg-3 " id="__divGlobalSearch__"></div>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item nav-right dropdown mr-2">
				<a class="nav-link dropdown-toggle" href="user" id="user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= Yii::$app->session['FIRST_NAME'] ?><i class="fa-solid fa-circle-user fa-lg"></i></a>
				<div class="dropdown-menu dropdown-menu-right shadow animate slideIn" aria-labelledby="user-dropdown">
                <a class="dropdown-item" onclick="showChangePassModal();"><?= Yii::t('app', 'Change Password') ?></a>
					<a class="dropdown-item" onclick="reloadSession();"><?= Yii::t('app', 'Reload Session') ?></a>
					<a class="dropdown-item" onclick="logout();"><?= Yii::t('app', 'Log out') ?></a>
				</div>
			</li>
        </ul>
        <!--end::End Navbar Links-->
    </div>
  <!--end::Container-->
</nav>
<!--end::Header-->

<!-- Change Password modal -->
<div class="modal fade" id="modalChangePassword" aria-hidden="true" aria-labelledby="modalChangePasswordTitle" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalChangePasswordTitle"><?= Yii::t('app', 'Change Password') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body ml-4 mr-4">
				<form id="formChangePassword" class="form" autocomplete="off">
					<div class="form-group row required">
						<div class="col-sm-12">
							<input id="inputCurrentPass" type="password" class="form-control" placeholder="<?= Yii::t('app', 'Current Password') ?>" maxlength="20" data-parsley-required data-parsley-maxlength="20" data-parsley-whitespace="trim" onkeypress="return event.charCode != 32">
						</div>
					</div>
					<div class="form-group row required">
						<div class="col-sm-12">
							<div class="input-group">
								<input id="inputNewPass" type="password" class="form-control" placeholder="<?= Yii::t('app', 'New Password') ?>" maxlength="20" data-parsley-required data-parsley-maxlength="20" data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*?[?!@$%&*--+~)(><:;]).*" data-parsley-pattern-message="<?= Yii::t('app', 'This value should have at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.') ?>" data-parsley-length="[8, 20]" data-parsley-whitespace="trim" onkeypress="return event.charCode != 32" data-parsley-errors-container="#errPassChgPass">
								<div class="input-group-append">
									<button class="btn btn-secondary side-btn-icon" id="btnShowNewPassword" type="button"><i class="bi bi-eye-fill"></i></button>
								</div>
							</div>
							<div class="help-block text-danger" id="errPassChgPass"></div>
						</div>
					</div>
					<div class="form-group row required">
						<div class="col-sm-12">
							<div class="input-group">
								<input id="inputNewPass2" type="password" class="form-control" placeholder="<?= Yii::t('app', 'Confirm New Password') ?>" maxlength="20" data-parsley-required data-parsley-maxlength="20" data-parsley-whitespace="trim" onkeypress="return event.charCode != 32" data-parsley-equalto="#inputNewPass" data-parsley-equalto-message="<?= Yii::t('app', 'New password value is not the same') ?>" data-parsley-errors-container="#errPass2ChgPass">
								<div class="input-group-append">
									<button class="btn btn-secondary side-btn-icon" id="btnShowNewPassword2" type="button"><i class="bi bi-eye-fill"></i></button>
								</div>
							</div>
							<div class="help-block text-danger" id="errPass2ChgPass"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" form="formChangePassword" type="submit"><i class="fa fa-check"></i> <?= Yii::t('app', 'Submit') ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(function() {
		$('#btnShowNewPassword').on('click', function()
		{
			if ($('#inputNewPass').attr('type') == 'password')
			{
				$('#inputNewPass').attr('type', 'text');
				$('#btnShowNewPassword').html('<i class="bi bi-eye-fill"></i>');
			}
			else
			{
				$('#inputNewPass').attr('type', 'password');
				$('#btnShowNewPassword').html('<i class="bi bi-eye-slash"></i>');
			}
		});

		$('#btnShowNewPassword2').on('click', function()
		{
			if ($('#inputNewPass2').attr('type') == 'password')
			{
				$('#inputNewPass2').attr('type', 'text');
				$('#btnShowNewPassword2').html('<i class="bi bi-eye-fill"></i>');
			}
			else
			{
				$('#inputNewPass2').attr('type', 'password');
				$('#btnShowNewPassword2').html('<i class="bi bi-eye-slash"></i>');
			}
		});

		$('#formChangePassword').parsley({
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formChangePassword').submit(function(e)
		{
			e.preventDefault();
			
			changePassword();
		});
    });

    function showChangePassModal()
	{	
		$('#inputCurrentPass').val(''),
		$('#inputNewPass').val('')
		$('#inputNewPass2').val('')
		$('#formChangePassword').parsley().reset();
		$('#modalChangePassword').appendTo("body").modal('show');
	}

	function changePassword()
	{			
		BootstrapModalWrapperFactory.confirm({
			title          : '<?= Yii::t('app', 'Confirm') ?>',
			message        : '<?= Yii::t('app', 'Are you sure you want to change password') ?>?',
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
							currPassword: $('#inputCurrentPass').val(),
							newPassword: $('#inputNewPass').val()
						}

						$.ajax({
							type : 'POST',
							data : data,
							dataType : 'JSON',
							url : '<?= Yii::$app->getUrlManager()->createUrl('admin/user/change-password') ?>',
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
													$('#modalChangePassword').modal('hide');
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

    function reloadSession() {
        $.ajax
		({
			type : 'POST',
			url : '<?= Yii::$app->getUrlManager()->createUrl('login/reload-session') ?>',
			dataType : 'JSON',
			success : function(result)
			{
				location.reload(1);
			}
		});
    }

	function logout() {
		const roleType = "<?= Yii::$app->session['ROLE_TYPE']; ?>";
        const isAdminDashboard = () => roleType == "SSA" || roleType == "SA";
		
		if (isAdminDashboard()) {
			window.location.href = '<?php echo Yii::$app->getUrlManager()->createUrl('admin-dashboard/logout') ?>';
		} else {
			window.location.href = '<?php echo Yii::$app->getUrlManager()->createUrl('dashboard/logout') ?>';
		}
	}
</script>