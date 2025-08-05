<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';
	$random_number = 0;
	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}
	include 'init.php';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass);

			// Check If The User Exist In Database

			$stmt = $con->prepare("SELECT 
										UserID, Username, Password, avatar
									FROM 
										users 
									WHERE 
										Username = ? 
									AND 
										Password = ?");

			$stmt->execute(array($user, $hashedPass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			// If Count > 0 This Mean The Database Contain Record About This Username

			if ($count > 0) {

				$_SESSION['user'] = $user; // Register Session Name

				$_SESSION['uid'] = $get['UserID']; // Register User ID in Session

				$_SESSION['avatar'] = $get['avatar'];

				header('Location: index.php'); // Redirect To Dashboard Page

				exit();
			}

		} else {
			

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];			// Upload Variables

			$avatarName = $_FILES['pictures']['name'];
			$avatarSize = $_FILES['pictures']['size'];
			$avatarTmp	= $_FILES['pictures']['tmp_name'];
			$avatarType = $_FILES['pictures']['type'];

			// List Of Allowed File Typed To Upload

			$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

			// Get Avatar Extension
				
			$ref = explode('.', $avatarName);
			$avatarExtension = strtolower(end($ref));
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];
			$fullname	= $_POST['fullname'];
			$turma	= $_POST['turma'];
			$turno	= $_POST['turno'];


			
			// Get Variables From The Form

			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

				if (strlen($filterdUser) < 4) {

					$formErrors[] = 'Nome de usuario precisa ser maior do que 4 caracteres';

				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'O campo de senha não pode ficar vazio';

				}

				if (sha1($password) !== sha1($password2)) {

					$formErrors[] = 'As duas senhas não são iguais';

				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'Email inválido';

				}

			}

			// Check If There's No Error Proceed The User Add

			if (empty($formErrors)) {
				$random_number = rand(0, 10000000000);
				$avatar = $random_number . '_' . $avatarName;
				
				// Define o caminho completo onde a imagem redimensionada será salva
				$destination_path = "admin/uploads/avatars/" . $avatar;

				// Tenta redimensionar a imagem. $avatarTmp é o arquivo temporário do upload.
				$resize_success = redimensionarImagem($avatarTmp, $destination_path, 225, 225);

				if ($resize_success) {
					// Se a imagem foi redimensionada e salva com sucesso, continue com o registro
					
					// Check If User Exist in Database
					$check = checkItem("Username", "users", $username);

					if ($check == 1) {
						$formErrors[] = 'Desculpa, esse usuário já existe';
					} else {
						// Insert Userinfo In Database
						$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, RegStatus, Date, avatar, Turma, Turno)
												VALUES(:zuser, :zpass, :zmail, :zname, 0, now(), :zpic, :zturma, :zturno)");
						$stmt->execute(array(
							'zuser' => $username,
							'zpass' => sha1($password),
							'zmail' => $email,
							'zname' => $fullname,
							'zpic'  => $avatar, // Salva o nome do novo arquivo no banco de dados
							'zturma'=> $turma,
							'zturno'=> $turno
						));

						// Echo Success Message
						$succesMsg = 'Registro efetuado com sucesso';
					}
				} else {
					// Se o redimensionamento falhar, adicione um erro
					$formErrors[] = 'Erro ao processar a imagem. Por favor, tente um arquivo JPG, PNG ou GIF.';
				}
			}

		}

	}

?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | 
		<span data-class="signup">Signup</span>
	</h1>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Nome de Usuario" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Senha" 
				required />
		</div>
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
	</form>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"  enctype="multipart/form-data">
		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="Username Must Be Between 4 Chars"
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Nome de Usuario" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Senha" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="Confirme sua Senha" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				placeholder="Email" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="fullname" 
				placeholder="Nome Completo" 
				required />
		</div>
		<div class="input-container">
			<select class="form-control" name="turma" required>
				<option value="">----------- Turma -----------</option>
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
		<div class="input-container">
			<select class="form-control" name="turno" required>
				<option value="">----------- Turno -----------</option>
				<option value="Manha">Manhã</option>
				<option value="Tarde">Tarde</option>
				<option value="Noite">Noite</option>
			</select>
</br>
</br>
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="file" 
				name="pictures" 
				required />
		</div>
		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
	</form>
	<!-- End Signup Form -->
	<div class="the-errors text-center">
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div class="msg error">' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div class="msg success">' . $succesMsg . '</div>';

			}

		?>
	</div>
</div>

<?php 
	include $tpl . 'footer.php';
	ob_end_flush();
?>