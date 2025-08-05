<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'init.php';
	if (isset($_SESSION['user'])) {
		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
		$getUser->execute(array($sessionUser));
		$info = $getUser->fetch();
		$userid = $info['UserID'];
?>
<h1 class="text-center">Meu Perfil</h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Minha Informação</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Usuario</span> : <?php echo $info['Username'] ?>
					</li>
					<li>
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>Email</span> : <?php echo $info['Email'] ?>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Nome Completo</span> : <?php echo $info['FullName'] ?>
					</li>
					<li>
						<i class="fa fa-building fa-fw"></i>
						<span>Turma</span> : <?php echo $info['Turma'] ?>
					</li>
					<li>
						<i class="fa fa-clock-o"></i>
						<span>Turno</span> : <?php echo $info['Turno'] ?>
					</li>
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Data Reg.</span> : <?php echo $info['Date'] ?>
					</li>
				</ul>
				<a href="editProfil.php" class="btn btn-default">Editar Infromações</a>
			</div>
		</div>
	</div>
</div>
<div id="my-ads" class="my-ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Meus Items</div>
			<div class="panel-body">
			<?php
				$myItems = getAllFrom("*", "items", "where Member_ID = $userid", "", "Item_ID");
				if (! empty($myItems)) {
					echo '<div class="row">';
					foreach ($myItems as $item) {
						echo '<div class="col-sm-6 col-md-3">';
							echo '<div class="thumbnail item-box">';
								if ($item['Approve'] == 0) { 
									echo '<span class="approve-status">Waiting Approval</span>'; 
								}
								echo '<span class="price-tag">$' . $item['Price'] . '</span>';
								echo '<img class="img-responsive" src="img.png" alt="" />';
								echo '<div class="caption">';
									echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
									echo '<p>' . $item['Description'] . '</p>';
									echo '<div class="date">' . $item['Add_Date'] . '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
					echo '</div>';
				} else {
					echo 'Sem anuncios pra mostrar, Crie um <a href="newad.php">Novo Anuncio</a>';
				}
			?>
			</div>
		</div>
	</div>
</div>
<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Últimos Comentários</div>
			<div class="panel-body">
			<?php
				$myComments = getAllFrom("comment", "comments", "where user_id = $userid", "", "c_id");
				if (! empty($myComments)) {
					foreach ($myComments as $comment) {
						echo '<p>' . $comment['comment'] . '</p>';
					}
				} else {
					echo 'Sem comentários pra mostrar';
				}
			?>
			</div>
		</div>
	</div>
</div>
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>