<?php

	
	session_start();

	$pageTitle='Show Items';
	
	include 'init.php';
	
	// Check If Get Request item_ID Is Numeric & Get The Integer Value Of It 
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']) : 0;
	// Select All Data Depend On This ID
	$stmt=$con->prepare("SELECT 
							items.*,
							categories.Name AS category_name,
							users.Username AS Member_ADD,
							users.phonenumber AS phonenumber,
							users.Email AS Email

						FROM
							items
						INNER JOIN
							categories
						ON
							categories.ID=items.Cat_ID
						INNER JOIN
							users
						ON
							users.UserID=items.Member_ID	
						WHERE 
								item_ID= ? 
						AND
								Approve=1
						");
	// Execute Query
 	$stmt->execute(array($itemid));
 	// Fatch The  Data
 	$count=$stmt->rowCount();


 	
 	if($count>0){


 	$item=$stmt->fetch();


?>
<h1 class="text-center FAITEM"><em style="color:#333"><i class="fa fa-cart-plus" aria-hidden="true"> </i>
<b></b></em></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			 <img class="img-responsive img-thumbnail center-block" src="admin/uploads/avatars/<?php echo $item['avatar'];?>"alt="">
		</div>
		<div class="col-sm-9 item-info">
		<h2><?php echo $item['Name'];?></h2>
		<p><?php echo $item['Description'];?></p>
		<ul class="list-unstyled">
			<li>
				<i class="fa fa-calendar fa-fw"></i>
				<span>Added Date</span><?php echo $item['Add_Date'];?>
			</li>
			<li>
				<i class="fa fa-money fa-fw"></i>
				<span>Price</span> : $<?php echo $item['Price'];?>
			</li>
			<li>
				<i class="fa fa-globe fa-fw"></i>
				<span>Made In</span> : <?php echo $item['Country_Made'];?>
			</li>
			<li>
				<i class="fa fa-tag fa-fw"></i>
				<span>Category</span> :
				<a href="categories.php?pageid=<?php echo $item['Cat_ID'];?>&pagename=<?php echo $item['category_name'];?>">
				 	<?php echo $item['category_name'];?>
				</a>
			</li>
			<li>
				<i class="fa fa-user fa-fw"></i>
				<span>Added By</span> :<a href="#"><?php echo $item['Member_ADD']; ?></a>
			</li>
			<li>
				<i class="fa fa- fa-phone-square fa-fw"></i>
				<span>Phone Number</span> :<a href="#"><?php echo '0'.$item['phonenumber']; ?></a>
			</li>
			<li>
				<i class="fa fa-envelope fa-fw" ></i>
				<span>Email</span> :<a href="#"><?php echo $item['Email']; ?></a>
			</li>			
		</ul>											  
		</div>
	</div>
	<hr class="custom-hr">
	<?php
	if(isset($_SESSION['user'])){
	?>
	<!-- Start Add Comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form  action="<?php echo $_SERVER['PHP_SELF'].'?itemid='.$item['item_ID'];?>" method="POST">
					<textarea name="comment" class="form-control" required>
					
					</textarea>
					<input type="submit" value="Add Comment" class="btn btn-primary">
				</form>
				<?php if($_SERVER['REQUEST_METHOD'] =='POST'){
		
					$comment=filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
					$userid	=$_SESSION['uid'];
					$itemid	=$item['item_ID'];
					if(!empty($comment)){
						$stmt=$con->prepare("INSERT INTO 
												comments(comment,status,comment_date,item_id,user_id)
												VALUES(:zcomment,0,now() ,:zitemid ,:zuserid)");
						$stmt->execute(array(
							'zcomment' =>$comment,
							'zitemid' =>$itemid,
							'zuserid' =>$userid
						));

						if ($stmt) {// SQL IS DONE...
							$themassage='<div class="alert alert-success">Comment Added</div>';
							echo "<meta http-equiv='refresh' content='0'>";
						}else{
							echo '<div class="alert alert-danger">Comment Must Be Not Empty</div>';

						}
					}
						
				}
				?>
			</div>
		</div>
	</div>
	<!-- End Add Comment -->
	<?php } else {
		echo ' <a href="login.php">Login</a> To Add<a href="login.php"> Register </a>Add Comment';
	} ?>

	<hr class="custom-hr">

<?php 	
				$stmt = $con->prepare("SELECT 
										comments.*, users.Username AS Member 
									FROM
										comments
									INNER JOIN
										users
									ON
										users.UserID=comments.user_id
									WHERE
									 item_id=?
									 AND
									 Status=1
									ORDER BY 
										c_id DESC
											");
		// Execute The Statement
		$stmt->execute(array($item['item_ID']));
		// Assign To Variable
		$comments=$stmt->fetchAll();
	
		foreach($comments as $comment){
			?>
			<div class="comment-box">
				<div class="row">
					<div class="col-sm-2 text-center">
						 <img class="img-responsive img-thumbnail img-circle center-block" src="user.png" alt="">
						 <?php echo $comment['Member'];?>
					</div>
					<div class="col-sm-10">
						<p class="lead"><?php echo $comment['comment'];?></p>
					</div>
			 	</div>
			</div>
			<hr  class="custom-hr">

	<?php	}?>



</div>


<?php
	

	}else{

		echo'<div class="alert alert-danger">There\'s No Such ID Or This Item Is Waiting Approval</div>';
		echo redirectHome('','back');
	}

 	include  $tpl .'footer.php'; 
 	
?>

