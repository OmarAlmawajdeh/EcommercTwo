<?php
$pageTitle='MMA';


 include 'init.php';

 ?>

<div class="container">
	<h1 class="text-center" style="font-style: italic;color: #28a745;text-shadow: 5px -2px #777;"><?php echo str_replace('-',' ', $_GET['pagename']); ?></h1>
	<div class="row">
	<?php 
		foreach(getItems('Cat_ID', $_GET['pageid']) as $item){
			echo '<div class="col-sm-6 col-md-3">';
				echo '<div class="thumbail item-box">'; 
					echo '<span class="price-tag">$'.$item['Price'].'</span>';
					echo '<img class="img-responsive img-thumbnail" src="admin/uploads/avatars/'.$item['avatar']. '" style="height: 245px;     max-width: 75%;" alt="" >';
					echo '<div class="caption">';							
						echo '<a href="items.php?itemid='.$item['item_ID'].'">';
							echo '<h3>'.$item['Name'].'</h3>';
						echo '</a>';
	
							echo '<p>'.$item['Description'].'</p>';
							echo '<div class="date">'.$item['Add_Date'].'</div>';

					echo '</div>';

				echo '</div>';
			echo '</div>';

		}
	?>
	</div>

</div>

 
<?php include $tpl.'footer.php';?>