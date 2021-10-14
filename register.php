<?php

include_once './config/db.php';

$success = ['message' => ''];
$errors = ['error' => '', 'name' => '', 'email' => '', 'password' => '', 'repeat-password' => ''];
if(isset($_POST['submit'])){

	//validation
	if(empty($_POST['name'])){
		$errors['name'] = 'Name field should not be Empty';
	}else{
		$name = $_POST['name'];
		if(!preg_match('/^[a-zA-Z0-9]*$/', $name)){
			$errors['name'] = 'Please enter valid username. special characters are not allowed';
		}
	}
	if(empty($_POST['email'])){
		$errors['email'] = 'Email field should not be Empty';
	}else{
		$email = $_POST['email'];
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors['email'] = 'Please provide valid email.';
		}
	}
	if(empty($_POST['password'])){
		$errors['password'] = 'Password field should not be Empty';
	}else{
		$password = $_POST['password'];
		if(!preg_match('/^([a-zA-Z0-9]*){0,5}$/', $password)){
			$errors['password'] = 'Password should be atleast 5 characters long';
		}
	}
	if(empty($_POST['repeat-password'])){
		$errors['repeat-password'] = 'Repeat password field should not be Empty';
	}else{
		$repeatPassword = $_POST['repeat-password'];
		if($password !== $repeatPassword){
			$errors['repeat-password'] = 'Password do not match. please try again.';
		}
	}

	if(array_filter($errors)){
		$errors['error'] = 'Error occur in your Form';
	}
	else{
		//check user already exist

		//create temlplate
		$sql = "SELECT email FROM users WHERE email=?";
		//prepared statement
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			$errors['error'] = 'mysqli prepare statment error.';
		}
		else{
			mysqli_stmt_bind_param($stmt, 's', $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
			if($result > 0){
				$errors['error'] = 'Email already taken'; 
			}
			else{
				// insert data into database
				$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
				//prepare statment
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt, $sql)){
					$errors['error'] = "mysqli prepare statement error.";
				}
				else{
					//hash password
					$hashPassword = password_hash($password, PASSWORD_DEFAULT);

					mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashPassword);
					mysqli_stmt_execute($stmt);

					$success['message'] = "You are siggned in successfully";
					header("Location: ./login.php");
					exit();
				}
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	
}

?>

<?php include('./templates/header.php');?>

<section>
	<div class="container">
	<div class="row my-5">
		<div class="col-md-7 m-auto">
			<div class="card card-body border-success">
				<form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
					<?php if($errors['error']): ?>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<div class="text-center"><?= $errors['error'] ?></div>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					<?php endif ?>
					<div class="row justify-content-center">
						<div class="col-md-10 mt-5">
							<?php if($errors['name']): ?>
								<input type="text" name="name" class="form-control border-danger" id="name" placeholder="Name">
								<small class="text-danger"><?= $errors['name']?></small>
							<?php else: ?>
								<input type="text" name="name" class="form-control border-success" id="name" placeholder="Name">
							<?php endif ?>
						</div>
						<div class="col-md-10 mt-3">
							<?php if($errors['name']): ?>
								<input type="email" name="email" class="form-control border-danger"" id="email" placeholder="Email">
								<small class="text-danger"><?= $errors['email']?></small>
							<?php else: ?>
								<input type="email" name="email" class="form-control border-success"" id="email" placeholder="Email">
							<?php endif ?>
						</div>
						<div class="col-md-10 mt-3">
							<?php if($errors['name']): ?>
								<input type="password" name="password" class="form-control border-danger"" id="password" placeholder="Password">
								<small class="text-danger"><?= $errors['password']?></small>
							<?php else: ?>
								<input type="password" name="password" class="form-control border-success"" id="password" placeholder="Password">
							<?php endif ?>
						</div>
						<div class="col-md-10 mt-3">
							<?php if($errors['name']): ?>
								<input type="password" name="repeat-password" class="form-control border-danger"" id="repeat-password" placeholder="Repeat Password">
								<small class="text-danger"><?= $errors['repeat-password']?></small>
							<?php else: ?>
								<input type="password" name="repeat-password" class="form-control border-success"" id="repeat-password" placeholder="Repeat Password">
							<?php endif ?>
						</div>
						<div class="col-md-10 mt-4">
							<button type="submit" name="submit" class="btn btn-success form-control" id="submit">Sign Up</button>
						</div>
						<div class="col-md-10 mt-2 mb-3">
							<p class="lead">Already Have an Account? <a href="login.php" class="text-success">Login</a></p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	</div>
</section>

<?php include('./templates/footer.php');?>