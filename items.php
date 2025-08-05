<?php
	ob_start();
	session_start();
	$pageTitle = 'Show Items';
	$status_show = "";
	$sob_encomenda_show = "";
	$turno_show = "";
	include 'init.php';

	// Check If Get Request item Is Numeric & Get Its Integer Value
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

	// Select All Data Depend On This ID
	$stmt = $con->prepare("SELECT 
								items.*, 
								categories.Name AS category_name, 
								users.Username,
								users.UserID
							FROM 
								items
							INNER JOIN 
								categories 
							ON 
								categories.ID = items.Cat_ID 
							INNER JOIN 
								users 
							ON 
								users.UserID = items.Member_ID 
							WHERE 
								Item_ID = ?
							AND 
								Approve = 1");

	// Execute Query
	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();

	if ($count > 0) {

	// Fetch The Data
	$item = $stmt->fetch();
?>
<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php
				if (empty($item['picture'])) {
					echo "<img class='img-responsive img-thumbnail center-block' src='admin/uploads/default.png' alt='' />";
				} else {
					echo "<img class='img-responsive img-thumbnail center-block' src='admin/uploads/items/" . $item['picture'] . "' alt='' />";
				}
			?>
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['Name'] ?></h2>
			<p><?php echo $item['Description'] ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Data</span> : <?php echo $item['Add_Date'] ?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Preço</span> : R$<?php echo $item['Price'] ?>
				</li>
				<li>
					<i class="fa fa-heartbeat" aria-hidden="true"></i>
					<?php
						if($item['Status'] == 1){
							$status_show = "Novo";
						}
						elseif($item['Status'] == 2){
							$status_show = "Semi-Novo";
						}
						elseif($item['Status'] == 3){
							$status_show = "Usado";
						}
						elseif($item['Status'] == 4){
							$status_show = "Bastante Usado	";
						}
					?>
					<span>Condição</span> : <?php echo $status_show	 ?>
				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Categoria</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
				</li>
				<li>
					<i class="fa fa-bookmark-o" aria-hidden="true"></i>
					<?php
						if($item['Sob_Encomenda'] == 1){
							$sob_encomenda_show = "Sim";
						}
						elseif($item['Sob_Encomenda'] == 2){
							$sob_encomenda_show = "Não";
						}
					?>
					<span>Sob Encomenda</span> : <?php echo $sob_encomenda_show ?>
				</li>
				<li>
					<i class="fa fa-user fa-fw" aria-hidden="true"></i>
					<span>Por</span> : <a href="users.php?userid=<?php echo $item['UserID'] ?>"><?php echo $item['Username'] ?></a>
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Turma</span> : <?php echo $item['Country_Made'] ?>
				</li>
				<li>
					<i class="fa fa-clock-o" aria-hidden="true"></i>
					<?php
						if($item['Turno'] == 1){
							$turno_show = "Manhã";
						}
						elseif($item['Turno'] == 2){
							$turno_show = "Tarde";
						}
						elseif($item['Turno'] == 3){
							$turno_show = "Noite";
						}
					?>
					<span>Turno</span> : <?php echo $turno_show ?>
				</li>
				<li>
					<i class="fa fa-phone fa-fw"></i>
					<span>Telefone</span> : <?php echo $item['contact'] ?>
				</li>
				<li>	
					</br>
					<i class="fa fa-whatsapp" aria-hidden="true"></i>
					<span>Whatsapp</span> : <a href="https://wa.me/<?php echo $item['contact'] ?>?text=Estou interessado no item '<?php echo $item['Name'] ?>' por R$<?php echo $item['Price'] ?>"><img src="admin/layout/css/images/Whatsapp.webp" width=350></a>
				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<?php if (isset($_SESSION['user'])) { ?>
	<!-- Start Add Comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Diga o que você achou do produto</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
					<textarea name="comment" required></textarea>
					<input class="btn btn-primary" type="submit" value="Add Comment">
				</form>
				<?php 
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {

						$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
						$itemid 	= $item['Item_ID'];
						$userid 	= $_SESSION['uid'];

						if (! empty($comment)) {

							$stmt = $con->prepare("INSERT INTO 
								comments(comment, status, comment_date, item_id, user_id)
								VALUES(:zcomment, 1, NOW(), :zitemid, :zuserid)");

							$stmt->execute(array(

								'zcomment' => $comment,
								'zitemid' => $itemid,
								'zuserid' => $userid

							));

							if ($stmt) {

								echo '<div class="alert alert-success">Comment Added</div>';

							}

						} else {

							echo '<div class="alert alert-danger">You Must Add Comment</div>';

						}

					}
				?>
			</div>
		</div>
	</div>
	<!-- End Add Comment -->
	<?php } else {
		echo 'Faça <a href="login.php">Login</a> ou <a href="login.php">Registre-se</a> Para Poder Comentar';
	} ?>
	<hr class="custom-hr">
		<?php

			// Select All Users Except Admin 
			$stmt = $con->prepare("SELECT 
										comments.*, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									WHERE 
										item_id = ?
									AND 
										status = 1
									ORDER BY 
										c_id DESC");

			// Execute The Statement

			$stmt->execute(array($item['Item_ID']));

			// Assign To Variable 

			$comments = $stmt->fetchAll();
		?>
		
	<?php foreach ($comments as $comment) { 
		$myimage = getSingleValue($con, "SELECT avatar FROM users WHERE UserID=?", [$comment['user_id']]);		
	?>
		<div class="comment-box">
    <div class="row">
        <div class="col-sm-2 text-center">
            <?php
                echo '<img class="img-responsive img-thumbnail img-circle center-block" ';
                if (empty($myimage)) {
                    echo "src='admin/uploads/default.png' alt='' />";
                } else {
                    echo "src='admin/uploads/avatars/" . $myimage . "' alt='' />";
                }
            ?>
            
            <a href="users.php?userid=<?php echo $comment['user_id'] ?>"><?php echo $comment['Member'] ?></a>
        </div>
        <div class="col-sm-10">
            <p class="lead"><?php echo $comment['comment'] ?></p>
        </div>
    </div>
</div>
		<hr class="custom-hr">
	<?php } ?>
</div>
<?php
	} else {
		echo '<div class="container">';
			echo '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';
		echo '</div>';
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>