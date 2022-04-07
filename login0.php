
	<?php
	ob_start();

	session_start();
	$noNavbar='';
	$pageTitle='Login';
 	if (isset($_SESSION['user'])){
 		header('Location: index.php');//Register To index Page
	 }

	 include 'init.php';	

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if (isset($_POST['login'])) {
		
		 	$user=$_POST['email'];
		 	$pass=$_POST['pass'];
		 	$hashedPass = sha1($pass);

		 //	check If The User Exist In Database
		 	$stmt=$con->prepare("SELECT 
		 								UserID,Username, Password 
		 						 FROM 
		 								users 
		 						 WHERE 
		 						 		Username= ?
		 						 AND 
		 						 		Password= ?
		 						  
		 						 ");
		 	$stmt->execute(array($user ,$hashedPass));
		 	$get=$stmt->fetch();
		 	$count=$stmt->rowCount();
		 	

			 //If Count > 0 This Mean The Database Contain Record About This Username

		 	if ($count > 0){
				$_SESSION['user'] =$user;//Register Session Name
				$_SESSION['uid']=$get['UserID'];//Register Session UserId
				header('Location:index.php');//Register To Dashbord Page
				exit();
			 	}	

		} elseif(isset($_POST['signup'])){

			$formErrors=array();
			$username=$_POST['username'];
			$fullname=$_POST['fullname'];
			$password=$_POST['password'];
			$password2=$_POST['password2'];
			$email=$_POST['email'];
			$numberph=$_POST['number'];


			if(isset($username)){

			 	$filterdUser=filter_var($username,FILTER_SANITIZE_STRING);

			 	if(strlen($filterdUser)<4){

			 		$formErrors[]='<div class="msg">Username Must Be Larger Then 4 Characters</div>';
			 	}
		 	}

		 	if(isset($password) && isset($password2)){
		 			if(empty($password)){ 
		 				$formErrors[]='Sorry Password Cant Be Empty ';}
			 		$pass1=sha1($password);
			 		$pass2=sha1($password2);
			 		if($pass1!==$pass2){
			 			$formErrors[]='<div class="msg">Sorry Password Is Not Match</div>';
			 		}
		 	}

			if(isset($email)){

			 	$filterdEmail=filter_var($email,FILTER_SANITIZE_EMAIL);

			 	if(filter_var($filterdEmail,FILTER_SANITIZE_EMAIL) != true){

			 		$formErrors[]='<div class="msg">This Email Is Not Valid</div>';
			 	}}


		 		if(empty($formErrors)){

					$check=checkItem("Username","users",$username);
						if($check == 1)
						{

							$formErrors[]='<div class="msg">'. 'Sorry This User Is Exist '.'</div>';

						}else{

							$stmt=$con->prepare("INSERT INTO users(Username,Password,Email,Fullname,RegStatus,Date,phonenumber)
														     VALUES(:zuser,:zpass,:zmail,:zname,0 ,now(),:numberph)"); 
							$stmt->execute(array(
								'zuser'=>$username,
								'zpass'=>sha1($password),
								'zmail'=>$email,
								'zname'=>$fullname,
								'numberph'=>$numberph
							));

							// Echo Success Message

							$successmsg= 'Congrats You Are Now Registerd User';
							}
				}


		}
	}
 	 	
?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="login/images/img-01.png" alt="IMG">
				</div>

				<form class="login login100-form validate-form " action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
					<span class="login100-form-title">
						Member Login
					</span>

					<div class="wrap-input100 "  data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" name="login" type="submit" value="Login">
							Login
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
			<!-- End Signup Form-->
			<div class="the-errors text-center">
				<?php
					if (!empty($formErrors)){
		
						foreach($formErrors as $errors){
							echo $errors ;
						}
					}
				if (!empty($successmsg)) {
						echo '<div class="msg">'.$successmsg.'</div>';
					}
				?>
		
				</div>
		</div>

	
  





<?php
 	include  $tpl .'footer.php'; 
 	ob_end_flush();
?>
