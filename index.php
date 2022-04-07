<?php
	ob_start();
	session_start();

	$pageTitle='Homepage';
	
	include 'init.php';
	
?>
<!-- <h1  class="text-center homepageh1">NT</h1>  -->


<div class="container" style="">
			  <div class="header" >
            <div class="overlay">
                <ul class="bxslider">
                	<li><img src="bodybackground/banner1.jpg"><li>
                    <li><img src="bodybackground/banner2.jpg"><li>
                    <li><img src="bodybackground/banner3.jpg"><li>
                    <li><img src="bodybackground/banner4.jpg"><li>

                 
                    <li><img src="bodybackground/banner3.jpg"><li>
                </ul>
            </div>
        </div>
	<div class="row" >
	<?php 

	$allitem=getLlFromDateAdditem('items');
		foreach($allitem as $item){
			echo '<div class="col-sm-7 col-md-3  " >';
				echo '<div class="thumbail item-box" >'; 
					echo '<span class="price-tag">$'.$item['Price'].'</span>';
					echo '<img class="img-responsive img-thumbnail" src="admin/uploads/avatars/'.$item['avatar']. '" style="height:245px;   max-width: 75%;" alt="" >';
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
	
      
<?php
 	include  $tpl .'footer.php'; 
 	ob_end_flush();
?>
