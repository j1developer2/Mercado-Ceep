<?php 
	session_start();
	include 'init.php';
?>

<div class="container">
		<?php
		if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {

			$category = intval($_GET['pageid']);
			$allItems = getAllFrom("*", "items", "where Cat_ID = {$category}", "AND Approve = 1", "Item_ID");
			$myCategory = getSingleValue($con, "SELECT Name FROM categories WHERE id=?", [$category]);

			echo '<h1 class="text-center">'.$myCategory.'</h1>';
			echo '<div class="row">';

			foreach ($allItems as $item) {

				// -------------------------------
				// LIMITES COM RETICÊNCIAS
				// -------------------------------

				// Título até 27 caracteres
				$title = mb_substr($item['Name'], 0, 27, 'UTF-8');
				if (mb_strlen($item['Name'], 'UTF-8') > 27) {
					$title .= "...";
				}

				// Descrição até 65 caracteres
				$desc = mb_substr($item['Description'], 0, 65, 'UTF-8');
				if (mb_strlen($item['Description'], 'UTF-8') > 65) {
					$desc .= "...";
				}

				echo '<div class="col-sm-6 col-md-4">';
					echo '<div class="thumbnail item-box">';
						
						echo '<span class="price-tag">$' . $item['Price'] . '</span>';

						if (empty($item['picture'])) {
							echo "<img style='width:350px;height:300px' src='admin/uploads/default.png' alt='' />";
						} else {
							echo "<img style='width:350px;height:300px' src='admin/uploads/items/" . $item['picture'] . "' alt='' />";
						}

						echo '<div class="caption">';

							// TÍTULO LIMITADO + "..."
							echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $title . '</a></h3>';

							// DESCRIÇÃO LIMITADA + "..."
							echo "<p style='overflow-wrap: normal; overflow: hidden;'>" . $desc . "</p>";

							echo '<div class="date">' . $item['Add_Date'] . '</div>';

						echo '</div>';

					echo '</div>';
				echo '</div>';
			}

			echo '</div>';

		} else {
			echo 'You Must Add Page ID';
		}
		?>
</div>

<?php include $tpl . 'footer.php'; ?>