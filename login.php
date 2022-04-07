
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
		
		 	$user=$_POST['username'];
		 	$pass=$_POST['password'];
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

<div class="container login-page">
		<h1 class="text-center">
			<span class="selected " data-class="login" >Login</span> | <span  data-class="signup">Signup</span>
		</h1>

		<!-- Start Login Form-->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >

				<div class="input-container">
					<input 
						   class="form-control" 
						   type="text" 
						   name="username" 
						   autocomplete="off" 
						   placeholder="Type Your Username" 
						   required>
				</div>
				<div class="input-container">
					<input 
						   class="form-control" 
						   type="password" 	
						   name="password" 
						   autocomplete="new-password" 
						   placeholder="Type Your Password" 
						   required >
				</div>
				<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">

		</form>
		<!-- End Login Form-->

		<!-- Start Signup Form-->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >

				<div class="input-container">
					<input 	 
							 pattern=".{4,}" 
							 title="Username Must Be  4 Characters" 
							 class="form-control"
							 type="text" 
							 name="username"
							 autocomplete="off"  
							 placeholder="Type Your Username"
							 required >
				</div>
				<div class="input-container">
					<input 	 
							 pattern=".{8,}" 
							 title="Full Name Must Be  8 Characters" 
							 class="form-control"
							 type="text" 
							 name="fullname"
							 autocomplete="off"  
							 placeholder="Type Your Full Name"
							 required >
				</div>
				<div class="input-container">
					<input  
							minlength="4" 
							class="form-control " 
							type="password" 
							name="password" 
							autocomplete="new-password" 
							placeholder="Type a Complex Password" 
							required >
				</div>
				<div class="input-container">
					<input 								
							minlength="4" 
							class="form-control " 
							type="password" 
							name="password2"
							autocomplete="new-password"
							placeholder="Type a Password Again" 
							required >
				</div>
				<div class="input-container">
					<input 
						class="form-control" 
						type="email" 	
						name="email"  
						placeholder="Type a Valid Email"  
						required>
				</div>
				<div class="input-container">
					<input 
						class="form-control" 
						type="text" 	
						name="number"  
						placeholder="Phone Number"  
						required>
				</div>
				<input class="btn btn-success btn-block"  name="signup" type="submit" value="Signup">

		</form>
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
				  <div class="header " >
	            <div class="overlay">
	                <ul class="bxslider another" >
						<li>
	                        <h2> <span>Hurry up to register</span> </h2>
	                    </li>
	                    <li> 
	                        <h2> <span id="demo">						
				                        	<script>
												let text = "\uD83D\uDC93";  // String written inside quotes
												document.getElementById("demo").innerHTML = text;
											</script>
										</span> 
							
	                        	Welcome
	                        		 <span id="demoo">						
				                        	<script>
												let text1 = "\uD83D\uDC93";  // String written inside quotes
												document.getElementById("demoo").innerHTML = text1;
											</script>
										</span> 
							

	                        </h2>
                    	</li>
	                </ul>
	            </div>
	        </div>


		</div>


</div>


<?php
 	include  $tpl .'footer.php'; 
 	ob_end_flush();
?>
