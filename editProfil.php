<?php 
	session_start();
	include 'init.php';

// Get the User ID from Session
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
$userid = getSingleValue($con, "SELECT UserID FROM users WHERE username=?", [$_SESSION['user']]);

// Select All Data Depend On This ID

$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

// Execute Query

$stmt->execute(array($userid));

// Fetch The Data

$row = $stmt->fetch();

// The Row Count

$count = $stmt->rowCount();

// If There's Such ID Show The Form

if ($count > 0) { ?>

    <h1 class="text-center">Editar Meu Perfil</h1>
    <div class="container">
        <form class="form-horizontal" action="UpdateProfile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="userid" value="<?php echo $userid ?>" />
            <!-- Start Username Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Nome de Usuario</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off"/>
                </div>
            </div>
            <!-- End Username Field -->
            <!-- Start Password Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Senha</label>
                <div class="col-sm-10 col-md-6">
                    <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                    <input type="password" name="newpassword" class="form-control" autocomplete="nova-senha" placeholder="Deixe em branco se você não quer muda-la" />
                </div>
            </div>
            <!-- End Password Field -->
            <!-- Start Email Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10 col-md-6">
                    <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control"/>
                </div>
            </div>
            <!-- End Email Field -->
            <!-- Start Full Name Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Nome Completo</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name="full" value="<?php echo $row['FullName']; ?>" class="form-control"x />
                </div>
            </div>
            <!-- End Full Name Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Turma*</label>
                <div class="col-sm-10 col-md-6">
			    <select class="form-control" name="turma" required>
				    <option value="">----------- Obrigatorio -----------</option>
				    <option value="">----------- 1 ANOS -----------</option>
				    <option value="1A">1A</option>
				    <option value="1B">1B</option>
				    <option value="1C">1C</option>
				    <option value="1D">1D</option>
				    <option value="1E">1E</option>
				    <option value="1F">1F</option>
			    	<option value="1G">1G</option>
			    	<option value="">----------- 2 ANOS -----------</option>
			    	<option value="2A">2A</option>
				    <option value="2B">2B</option>
			    	<option value="2C">2C</option>
			    	<option value="2D">2D</option>
			    	<option value="2E">2E</option>
			    	<option value="2F">2F</option>
			    	<option value="2G">2G</option>
				    <option value="">----------- 3 ANOS -----------</option>
			    	<option value="3A">3A</option>
			    	<option value="3B">3B</option>
			    	<option value="3C">3C</option>
			    	<option value="3D">3D</option>
			    	<option value="3E">3E</option>
			       	<option value="3F">3F</option>
			    	<option value="3G">3G</option>
			    </select>
                </div>
		    </div>
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Turno*</label>
                <div class="col-sm-10 col-md-6">
		    	<select class="form-control" name="turno" required>
                <option value="">----------- Obrigatorio -----------</option>
			    	<option value="Manha">Manhã</option>
			    	<option value="Tarde">Tarde</option>
			    	<option value="Noite">Noite</option>
			    </select>
                </div>
            </div>
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Foto de Perfil</label>
                <div class="col-sm-10 col-md-6">
                    <input type="file" name="avatar" class="form-control" />
                    <small class="form-text text-muted">Deixe em branco se você não quer mudar a foto.</small>
                </div>
            </div>
          <!-- Start Submit Field -->
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                </div>
            </div>
            <!-- End Submit Field -->
        </form>
    </div>


<?php include $tpl . 'footer.php'; }?>