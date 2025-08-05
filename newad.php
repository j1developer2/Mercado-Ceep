<?php
	ob_start();
	session_start();
	$pageTitle = 'Criar um novo anúncio';
	include 'init.php';
	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			// Upload Variables

			$avatarName = $_FILES['itempic']['name'];
			$avatarSize = $_FILES['itempic']['size'];
			$avatarTmp	= $_FILES['itempic']['tmp_name'];
			$avatarType = $_FILES['itempic']['type'];

			// List Of Allowed File Typed To Upload

			$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

			// Get Avatar Extension
				
			$ref = explode('.', $avatarName);
			$avatarExtension = strtolower(end($ref));

			$formErrors = array();

			$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$turno 	= filter_var($_POST['turno'], FILTER_SANITIZE_NUMBER_INT);
			$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$sob_encomenda 	= filter_var($_POST['sob_encomenda'], FILTER_SANITIZE_NUMBER_INT);
			$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$contact 	= filter_var($_POST['contact'], FILTER_SANITIZE_STRING);

			if (strlen($name) < 4) {

				$formErrors[] = 'O título do item deve ter pelo menos 4 caracteres';

			}

			if (strlen($desc) < 10) {

				$formErrors[] = 'A descrição do item deve ter pelo menos 10 caracteres';

			}

			if (strlen($country) < 2) {

				$formErrors[] = 'O título do item deve ter pelo menos 2 caracteres';

			}

			if (empty($price)) {

				$formErrors[] = 'O preço do item não pode estar vazio';

			}

			if (empty($turno)) {

				$formErrors[] = 'O Turno deve ser especificado';

			}

			if (empty($status)) {

				$formErrors[] = 'O Condição do item não pode estar vazia';

			}

			if (empty($sob_encomenda)) {

				$formErrors[] = 'Diga se o item é Sob Encomenda ou não';

			}

			if (empty($category)) {

				$formErrors[] = 'A categoria do item não pode ficar vazia';

			}

			if (empty($contact)) {

				$formErrors[] = 'O número de contato não pode estar vazio';

			}

			if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
				$formErrors[] = 'Essa extensão de arquivo não é <strong>Permitida</strong>';
			}

			if (empty($avatarName)) {
				$formErrors[] = '<strong>É Necessário</strong> ter uma foto';
			}

			if ($avatarSize > 4194304) {
				$formErrors[] = 'A tamanho do arquivo não pode ultrapassar <strong>4MB</strong>';
			}

			// Check If There's No Error Proceed The Update Operation

			if (empty($formErrors)) {

				$avatar = rand(0, 10000000000) . '_' . $avatarName;

				move_uploaded_file($avatarTmp, "admin\uploads\items\\" . $avatar);

				// Insert Userinfo In Database

				$stmt = $con->prepare("INSERT INTO 

					items(Name, Description, Price, Country_Made, Status, Sob_Encomenda, Turno, Add_Date, Cat_ID, Member_ID, picture, contact)

					VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, :zsob_encomenda, :zturno, now(), :zcat, :zmember, :zpicture, :zcontact)");

				$stmt->execute(array(

					'zname' 	=> $name,
					'zdesc' 	=> $desc,
					'zprice' 	=> $price,
					'zcountry' 	=> $country,
					'zturno'	=> $turno,
					'zstatus' 	=> $status,
					'zsob_encomenda' => $sob_encomenda,
					'zcat'		=> $category,
					'zmember'	=> $_SESSION['uid'],
					'zpicture'	=> $avatar,
					'zcontact'	=> $contact

				));

				// Echo Success Message

				if ($stmt) {

					$succesMsg = 'Item Has Been Added';
					
				}

			}

		}

?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pageTitle ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Titulo</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{4,}"
										title="This Field Require At Least 4 Characters"
										type="text" 
										name="name" 
										class="form-control live"  
										placeholder="Nome do item"
										data-class=".live-title"
										required />
								</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Descrição</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{10,}"
										title="This Field Require At Least 10 Characters"
										type="text" 
										name="description" 
										class="form-control live"   
										placeholder="Descrição do item" 
										data-class=".live-desc"
										required />
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Whatsapp</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="contact" 
										class="form-control"   
										placeholder="Escreva tudo junto (ex:41997698922)" 
										required />
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Price Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Preço</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="price" 
										class="form-control live" 
										placeholder="Escreva com virgula (ex: 0,75)" 
										data-class=".live-price" 
										required />
								</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group form-group-lg">
							<label class="col-sm-3 control-label">Turma</label>
							<div class="col-sm-10 col-md-9">
								<select class="form-control" name="country" required>
									<option value="">...</option>
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
							<!-- End Country Field -->
							<!-- Start Turno Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Turno</label>
								<div class="col-sm-10 col-md-9">
									<select name="turno" required>
										<option value="">...</option>
										<option value="1">Manhã</option>
										<option value="2">Tarde</option>
										<option value="3">Noite</option>
									</select>
								</div>
							</div>
							<!-- End Turno Field -->
							<!-- Start Status Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Condição</label>
								<div class="col-sm-10 col-md-9">
									<select name="status" required>
										<option value="">...</option>
										<option value="1">Novo</option>
										<option value="2">Semi-Novo</option>
										<option value="3">Usado</option>
										<option value="4">Bastante Usado</option>
									</select>
								</div>
							</div>
							<!-- End Status Field -->
							<!-- Start Sob_Encomenda Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Sob Encomenda</label>
								<div class="col-sm-10 col-md-9">
									<select name="sob_encomenda" required>
										<option value="">...</option>
										<option value="1">Sim</option>
										<option value="2">Não</option>
									</select>
								</div>
							</div>
							<!-- End Sob_Encomenda Field -->
							<!-- Start Categories Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Categoria</label>
								<div class="col-sm-10 col-md-9">
									<select name="category" required>
										<option value="">...</option>
										<?php
											$cats = getAllFrom('*' ,'categories', '', '', 'ID');
											foreach ($cats as $cat) {
												echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Categories Field -->
							<!-- Start Image Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Foto</label>
								<div class="col-sm-10 col-md-9">
									<input 
										id='imginp'
										type="file" 
										name="itempic" 
										class="form-control" 
										onchange="loadFile(event)" />
								</div>
							</div>
							<!-- End Image Field -->
							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-3 col-sm-9">
									<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
								</div>
							</div>
							<!-- End Submit Field -->
						</form>
					</div>
					<div class="col-md-4">
						<div class="thumbnail item-box live-preview">
							<span class="price-tag">
								<span class="live-price">$ 0</span>
							</span>
							<img id="output" class="img-responsive" alt="" />
							<div class="caption">
								<h3 class="live-title">Title</h3>
								<p class="live-desc">Description</p>
							</div>
						</div>
					</div>
				</div>
				<!-- Start Loopiong Through Errors -->
				<?php 
					if (! empty($formErrors)) {
						foreach ($formErrors as $error) {
							echo '<div class="alert alert-danger">' . $error . '</div>';
						}
					}
					if (isset($succesMsg)) {
						echo '<div class="alert alert-success">' . $succesMsg . '</div>';
					}
				?>
				<!-- End Loopiong Through Errors -->
			</div>
		</div>
	</div>
</div>
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>