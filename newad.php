<?php
	ob_start();
	$pageTitle='Create New Ad';
	session_start();

	
	
	include 'init.php';
	
	if(isset($_SESSION['user'])){


 	if($_SERVER['REQUEST_METHOD']=='POST'){
 		
 		$formErrors=array();

		$avatarName=$_FILES['avatar']['name'];
		$avatarSize=$_FILES['avatar']['size'];
		$avatarTmp=$_FILES['avatar']['tmp_name'];
		$avatarType=$_FILES['avatar']['type'];
		$avatarExtensionAllowed=array("jpeg","jpg","pang","png","gif");
		$avatarA=explode('.',$avatarName);
		$avatarB=end($avatarA);
		$avatarExtension=strtolower($avatarB);

 		//avatar Upload
 		$name 		= filter_var(	$_POST['name']			, 	FILTER_SANITIZE_STRING); 		
 		$desc 		= filter_var(	$_POST['description']	, 	FILTER_SANITIZE_STRING);
 		$price		= filter_var(	$_POST['price']			, 	FILTER_SANITIZE_NUMBER_INT);
 		$country	= filter_var(	$_POST['country']		, 	FILTER_SANITIZE_STRING);
 		$status		= filter_var(	$_POST['status']		, 	FILTER_SANITIZE_NUMBER_INT);
 		$category	= filter_var(	$_POST['category']		, 	FILTER_SANITIZE_NUMBER_INT);

 		if(strlen($name)<4){$formErrors[]='Item Title Must Be At Least 4 Characters';}
 		if(strlen($desc)<10){$formErrors[]='Item Description Must Be At Least 10 Characters';} 		
 		if(strlen($country)<2){$formErrors[]='Item Country Must Be At Least 2 Characters';}
 		if(empty($price)){$formErrors[]='Item Price Must Be Not Empty ';}
 		if(empty($status)){$formErrors[]='Item status Must Be Not Empty ';}
 		if(empty($category)){$formErrors[]='Item category Must Be Not Empty ';}
		if(!in_array($avatarExtension, $avatarExtensionAllowed)){$formErrors[]='This Extansion Is Not <strong>Allowed</strong>';}
		if(!empty($avatarName)&&!in_array($avatarExtension, $avatarExtensionAllowed)){$formErrors[]='This Extansion Is Not <strong>Allowed</strong>';}
		if(empty($avatarName)){$formErrors[]='Avatar Is <strong>Required</strong>';}
		if($avatarSize>4194304){$formErrors[]='Avatar Cant be larger Than<strong> 4Mg</strong>';}	 	
 					 	// Insert UserInformation In  Database 
	 	if(empty($formErrors)){

			$avatar=rand(0,1000000).'_'.$avatarName;
			move_uploaded_file($avatarTmp,"admin\uploads\avatars\\".$avatar);	 		
			$stmt=$con->prepare("INSERT INTO
								 items(Name,Description,Price,Country_Made,avatar,Status,Add_Date ,Cat_ID, Member_ID)
								 VALUES(:zname, :zdesc,:zprice, :zcountry,:zavatar, :zstatus, now(), :zcat, :zmember)"); 
			$stmt->execute(array(
				'zname'		=>$name,
				'zdesc'		=>$desc,
				'zprice'	=>$price,
				'zcountry'	=>$country,
				'zavatar'	=>$avatar,
				'zstatus'	=>$status,
				'zcat'		=>$category,
				'zmember'	=>$_SESSION['uid']
			));


			// Echo Success Message
			echo "<div class='container'>";
			if ($stmt){	//Procedured Was Done In SQL PHP Will Be Print Success Message
				echo "Item Added";}
			echo "</div>";
		}

 	}



?>
<h1 class="text-center">
	<em style="color:#333">
		<i class="fa fa-shopping-bag" aria-hidden="true"></i>
		<b><?php echo $pageTitle;?></b>
	</em>
</h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-8">

<!--Form To Add Item We Copy From Form Page Admin ADD Item-->
						
						<div class="container">
							<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
									<!-- Start Name Field -->
									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Name</label>
									  <div class="col-sm-10 col-md-4">
									  	 <input 
									  	 type="text"
									  	 name="name"
									  	 class="form-control live"
									  	 placeholder="Name Of The Item"
									  	  required		
									  	   	data-class=".live-title"
									  	 />
									  </div>
									</div>
									<!-- End Name Field -->
									<!-- Start avatar Field -->
									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Name</label>
									  <div class="col-sm-10 col-md-4">
									  	 <input 
									  	 type="file"
									  	 name="avatar"
									  	 class="form-control live"
									  	 placeholder="Image Item"
									  	  required		
									  	   	data-class=".live-img"
									  	 />
									  </div>
									</div>
									<!-- End avatar Field -->
									<!-- Start Description Field -->

									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Description</label>
									  <div class="col-sm-10 col-md-4">
									  	 <input 
									  	 type="text"
									  	 name="description"
									  	 class="form-control live"
									  	 placeholder="Description Of The Item "
									  	 data-class=".live-desc"
										  required		  	/>
									  </div>
									</div>
									<!-- End Description Field -->
									<!-- Start Price Field -->

									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Price</label>
									  <div class="col-sm-10 col-md-4">
									  	 <input 
									  	 type="text"
									  	 name="price"
									  	 class="form-control live"
									  	 placeholder="Price Of The Item "	
									  	 data-class=".live-price"
									  	  required/>
									  </div>
									</div>
									<!-- End Price Field -->
									<!-- Start Country_Made Field -->

									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Country Made</label>
									  <div class="col-sm-10 col-md-4">
									  	 <input 
									  	 type="text"
									  	 name="country"
									  	 class="form-control"
									  	 placeholder="Country Of Made"
									  	 required
									  	 />
									  </div>
									</div>
									<!-- End Country_Made Field -->
									<!-- Start Status Field -->

									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Status</label>
									  <div class="col-sm-10 col-md-4">
									 	<select class="form-control" name="status">
									 		<option value="">...</option>
									 		<option value="1">New</option>
									 		<option value="2">Like New</option>
									 		<option value="3">Used</option>
									 		<option value="4">Very Old</option>
									 	</select>
									  </div>
									</div>
									<!-- End Status Field -->
				
									<!-- Start Categories Field -->

									<div class="form-group form-group-lg ">
									<label class="col-sm-3 control-label">Category</label>
									  <div class="col-sm-10 col-md-4">
									 	<select class="form-control" name="category">
									 		<option value="0">...</option>
									 		<?php
									 		$cats =getLlFrom('categories');
									 		foreach ($cats as $cat){

									 			echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";

									 		}
									 		?>
									 	</select>
									  </div>
									</div>
									<!-- End Categories Field -->										
									<!-- Start Item Field -->
									<div class="form-group from-group-lg">
									 <div class="col-sm-offset-3 col-sm-4">
									  	<input 
									  	type="submit"
									  	value="Add Item"
									  	class="btn btn-primary btn-sm">
									  	<input 
									  	type="reset"
									  	value="Clear"
									  	class="btn btn-warning btn-sm">
									  </div>
									</div>
									<!-- End Item Field -->	
							    </form>
							</div>	
<!--Form To Add Item We Copy From Form Page Admin ADD Item-->

					</div>
					<div class="col-md-4">
						 <div class="thumbail item-box live-preview"><!--Name Class?(live-preview)To Conncted with form added item and to become live with cheange-->
							 <span class="price-tag">$<span class="live-price">0</span></span>
							 <img class="img-responsive " src="images1.png" class="live-img" alt="">
							 <div class="caption">				
								 <h3 class="live-title">Name</h3>
								 <p class="live-desc">Description</p>
							 </div>
						</div>
					</div>
				</div>
				<!-- Start Looping Through Errors-->
				<?php 

					if(!empty($formErrors)){
						foreach ($formErrors as $error){
							echo '<div class="alert alert-danger">'.$error.'</div>';
						}
					}

				?>
				<!-- End Looping Through Errors-->				
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
 	ob_end_flush();
?>

