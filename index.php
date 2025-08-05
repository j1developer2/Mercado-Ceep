<?php
	ob_start();
	session_start();
	$pageTitle = 'Homepage';
	include 'init.php';
?>
<div style="padding-top:50px;" class="container">
	<div class="row">
		<?php
			$allItems = getAllFrom('*', 'items', 'where Approve = 1', '', 'Item_ID');
			foreach ($allItems as $item) {
				echo '<div class="col-sm-6 col-md-4">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">R$' . $item['Price'] . '</span>';
						if (empty($item['picture'])) {
							echo "<img style='width:350px;height:300px' src='admin/uploads/default.png' alt='' />";
						} else {
							echo "<img style='width:350px;height:300px' src='admin/uploads/items/" . $item['picture'] . "' alt='' />";
						}
						echo '<div class="caption">';

							// --- CÓDIGO PARA LIMITAR O TÍTULO ---
							$title = $item['Name'];
							if (mb_strlen($title, 'UTF-8') > 50) {
								$short_title = mb_substr($title, 0, 50, 'UTF-8') . '...';
							} else {
								$short_title = $title;
							}
							// Exibe o título (curto ou completo) dentro de um link com o título completo no hover
							echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'" title="' . htmlspecialchars($title) . '">' . $short_title .'</a></h3>';
							// --- FIM DA ALTERAÇÃO DO TÍTULO ---

							// Pega a descrição original
							$description = $item['Description'];

							// Verifica se a descrição é maior que 50 caracteres
							if (mb_strlen($description, 'UTF-8') > 65) {
								// Se for, corta em 50 caracteres e adiciona "..."
								$short_description = mb_substr($description, 0, 65, 'UTF-8') . '...';
							} else {
								// Se não for, usa a descrição completa
								$short_description = $description;
							}
							// Exibe a descrição (curta ou completa)
							echo "<p style='overflow-wrap: normal;overflow: hidden;'>" . $short_description . "</p>";

							echo '<div class="date">' . $item['Add_Date'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</div>
<?php
	include $tpl . 'footer.php'; 
	ob_end_flush();
?>