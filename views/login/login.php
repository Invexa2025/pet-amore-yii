<?php
	$this->title = 'Login';
?>

<!-- Section: Design Block -->
<section class="overflow-hidden">
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
            <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                <h1 class="my-5 display-5 fw-bold ls-tight">
                    <?= Yii::t('app', 'The best offer') ?> <br />
                    <span><?= Yii::t('app', 'for your business') ?></span>
                </h1>
                    <p class="mb-4 opacity-70">
                        <?= Yii::t('app', '"Dream big, work hard, and never stop believing!". Log in and keep pushing towards your success.') ?>
                    </p>
            </div>
            <div class="col-lg-5 mb-5 mb-lg-0 position-relative">
                <div class="card">
                    <div class="card-body px-4 py-5 px-md-5">
                        <div class="row">
                            <h1 class="text-center">
                                <?= Yii::t('app', 'Welcome to Invexa') ?>
                            </h1>
                        </div>
                        <div class="row mb-4">
                            <h5 class="text-center">
                                <?= Yii::t('app', 'Login to your account to access our system') ?>
                            </h5>
                        </div>
                        <form id="formLogin" class="form" autocomplete="off">
                            <!-- User ID input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="inputUserId"><?= Yii::t('app', 'User ID') ?></label>
                                <input type="text" class="form-control" id="inputUserId" autocomplete="off" maxlength="512" data-parsley-required data-parsley-maxlength="64" data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+$" data-parsley-pattern-message="<?= Yii::t('app', 'This value should be user_id@domain') ?>" placeholder="<?= Yii::t('app', 'Enter User ID') ?>">
                            </div>
                            <!-- Password input -->
                            <div data-mdb-input-init class="form-outline mb-2">
                                <label class="form-label" for="inputPassword"><?= Yii::t('app', 'Password' ) ?></label>
                                <input type="password" class="form-control" id="inputPassword" maxlength="512" data-parsley-required data-parsley-maxlength="512" placeholder="<?= Yii::t('app', 'Password') ?>">
                            </div>
                            <div class="form-outline mb-4 text-right">
                                <label class="pull-right link-label text-primary" for=""><?= Yii::t('app', 'Forgot Password' ) ?>?</label>
                            </div>
                            <!-- Submit button -->
                            <button type="submit" id="btnLogin" data-mdb-button-init data-mdb-ripple-init class="btn btn-block btn-primary">
                                <?= Yii::t('app', 'Login') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section: Design Block -->
<script>
    $(function(){
        $('#formLogin, #formForgotPass').parsley({
			errorClass: 'is-invalid text-danger',
			errorsWrapper: '<span class="form-text text-danger"></span>',
			errorTemplate: '<span></span>',
			trigger: 'change'
		});

		$('#formLogin').submit(function(e) {
			e.preventDefault();

            if ($('#formLogin').parsley().isValid()) {
                login();
            }
		});
    });

    function login() {
        var data = {
			userId: $('#inputUserId').val(),
			password: $('#inputPassword').val()
		}

		$.ajax({
            type : 'POST',
            dataType : 'JSON',
            url : '<?= Yii::$app->getUrlManager()->createUrl('login/login') ?>',
            data : data,
            success: function(result) {
                if (result.errNum == 0) {
					if (typeof(Storage) !== "undefined") {
						localStorage.setItem('login', true);
					}

					window.location.href = '<?php echo Yii::$app->getUrlManager()->createUrl('dashboard') ?>';
				} else {
                    $('#inputPassword').val('');
                    
					Swal.fire({
                        title: '<?= Yii::t('app', 'Alert') ?>',
                        text: result.errStr,
                        icon: "error"
                    });
				};
            }
        });
    }
    
    function forgotPass() {

    }
</script>