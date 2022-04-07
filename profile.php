<?php

	
	session_start();

	$pageTitle='Profile';
	
	include 'init.php';
	
	if(isset($_SESSION['user'])){

	 $getUser=$con->prepare("SELECT * FROM users WHERE Username = ?");
	 $getUser->execute(array($sessionUser));
	 $info=$getUser->fetch();
		
					//Select All Users Expect Admin 
				
				if ($_SERVER['REQUEST_METHOD']=='POST') {
					echo "<h1 class='text-center'>Insert Member</h1>";
					echo "<div class='container'>";
					// Get Variables From The avatar
					$avatarName=$_FILES['avatar']['name'];
					$avatarSize=$_FILES['avatar']['size'];
					$avatarTmp=$_FILES['avatar']['tmp_name'];
					$avatarType=$_FILES['avatar']['type'];
					$avatarExtensionAllowed=array("jpeg","jpg","pang","gif");
					$avatarA=explode('.',$avatarName);
					$avatarB=end($avatarA);
					$avatarExtension=strtolower($avatarB);
					if(!in_array($avatarExtension, $avatarExtensionAllowed)){$formErrors[]='This Extansion Is Not <strong>Allowed</strong>';}
					if(!empty($avatarName)&&!in_array($avatarExtension, $avatarExtensionAllowed)){$formErrors[]='This Extansion Is Not <strong>Allowed</strong>';}
					if(empty($avatarName)){$formErrors[]='Avatar Is <strong>Required</strong>';}
					if($avatarSize>4194304){$formErrors[]='Avatar Cant be larger Than<strong> 4Mg</strong>';}	

					$avatar=rand(0,1000000).'_'.$avatarName;
					move_uploaded_file($avatarTmp,"admin\uploads\avatars\\".$avatar);
					$value="omar";

					$stmt=$con->prepare("UPDATE users SET avatar=? WHERE UserID = ? ");
					$stmt->execute(array($avatar,$info['UserID']));
										// Echo Success Message
					echo "<div class='container'>";
					$theMsg= "<div Class='alert alert-success'>" . $stmt->rowCount().' Record Inserted</div>';
					redirectHome($theMsg,'back');	
					echo "</div'>";
					}
							


				
?>


		<h1 class="text-center"><em><b>MyProfile</b></em></h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">

			<form class="form-horizontal md-form main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
									<!-- Start avatar Field -->
									<div class="file-field col-sm-10 col-md-3">
										<h5 class="text-center"><em style="color:#333">	<img class="img-responsive img-thumbnail" src="admin/uploads/avatars/<?php echo $info['avatar'];?>" style="width: 100%; height: 284px;margin-top: 34px;" alt="" ></em>	</h5>
									
									<label class="">Select Picture
										<i class="fa fa-upload" ></i>
									</label>
									  <div class="ALLinput">
									  	 <input 
									  	 type="file"
									  	 name="avatar"
									  	 class="form-control "
									  	 placeholder="User Picture" 		
									  	 id="selectedFile"
									  	 style="display: none;"/>
									<!-- End avatar Field -->
									<!-- Start Item Field -->
										  	<input 
											type="submit"
										  	value="Add Item"
										  	class="btn btn-primary btn-sm">
										  	<input 
										  	type="reset"
										  	value="Clear"
										  	class="btn btn-warning btn-sm">
										  	<input type="button" class="UploadFileBtn" value="Browse..." onclick="document.getElementById('selectedFile').click();" />
									</div>
								</div>
									<!-- End Item Field -->	
			</form>		

			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li> 
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Name:</span><?php  echo $info['Username']; ?>
					</li>
					<li>
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<span>Email:</span><?php  echo $info['Email'];    ?>	
					</li>
					<li>
						<i class="fa fa-user" aria-hidden="true"></i>						
						<span>FullName:</span><?php  echo $info['FullName']; ?>	
					</li>
					<li>
						<i class="fa fa-calendar"  aria-hidden="true"></i>
						<span>Register Date:</span><?php  echo $info['Date'];     ?>	
					</li>
					<li>
						<i class="fa fa-star"></i>
						<span>Favourite:</span>Cell Phone

					</li>
					<li> 
						<i class="fa fa-mobile" aria-hidden="true"></i>
						<span>Mobile:</span><?php  echo '0'.$info['phonenumber']; ?>
					</li>
					<li> 
						<i class="fa fa-check-square" aria-hidden="true"></i>
						<span>Status:</span><?php  if($info['RegStatus']==1){ echo 'Activated';}else{echo 'Waiting Activation';} ?>
					</li>

				</ul>
			</div>
		</div>
	</div>
</div>
<div class="my-ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Adertisment</div>
			<div class="panel-body">

					
					<?php 
					if(!empty(getItems('Member_ID', $info['UserID']))){
						echo '<div class="row">';
						foreach(getItems('Member_ID', $info['UserID'],'without approve') as $item){
							echo '<div class="col-sm-6 col-md-4">';
								echo '<div class="thumbail item-box">';
								if($item['Approve']==0){ echo '<span class ="approve-sataus">Waiting Approval</span>';}
									echo '<span class="price-tag">$'.$item['Price'].'</span>';
									echo '<img class="img-responsive img-thumbnail" src="admin/uploads/avatars/'.$item['avatar']. '" style="height: 245px;     max-width: 75%;" alt="" >';
									echo '<div class="caption">';							
										echo '<h3>
												<a href="items.php?itemid='.$item['item_ID'].'">
												'.$item['Name'].
												'</a>
											  </h3>';

										echo '<p>'.$item['Description'].'</p>';
										echo '<div class="date">'.$item['Add_Date'].'</div>';

									echo '</div>';

								echo '</div>';
							echo '</div>';	}
						echo '</div>';

					}else{
						echo ' There\'s Ads To Show, Create <a href="newad.php">New Ad</a>';}
					

					?>
					
			</div>
		</div>
	</div>
</div>
<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
			<?php 
					//Select All Users Expect Admin 
				
					$stmt = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
					// Excute The Statment 

					$stmt->execute(array($info['UserID']));

					// Assign To Variable

					$comments =$stmt->fetchAll();
					if(!empty($comments)){
						foreach ($comments as $comment){
							echo '<p>'.$comment['comment'].'</p>';
						}
					}else{
						echo 'There\'s Comments To Show';}
			?>
			</div>
		</div>
	</div>
</div>
<?php

 	}else {

 		header('Location:login.php');
 		exit();
 	}

 	include  $tpl .'footer.php'; 
 	
?>

