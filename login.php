<?php

include_once './config/db.php';

$errors = ['error' => '', 'email' => '', 'password' => ''];
if(isset($_POST['login-submit'])){

	$email = $_POST['email'];
	$password = $_POST['password'];

	//validation
	if(empty($_POST['email'])){
		$errors['email'] = 'Email field should not be empty';
	}
	else{
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors['email'] = 'please provide valid email.';
		}
	}
	if(empty($_POST['password'])){
		$errors['password'] = 'Password field should not be empty';
	}
	else{
		if(!preg_match('/^([a-zA-Z0-9]*){0,5}$/', $password)){
			$errors['password'] = 'password should be atleast 5 characters long.';
		}
	}

	if(array_filter($errors)){
		$errors['error'] = 'Error occur in your Form';
	}
	else{

		//create template
		$sql = "SELECT * FROM users WHERE email=?";
		//prepare statement
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			$errors['error'] = 'Mysql prepare statment error';
		}
		else{
			mysqli_stmt_bind_param($stmt, 's', $email);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if($row = mysqli_fetch_assoc($result)){
				//compare password
				$match = @password_verify($password, $row['password']);
				if($match){

					//save into session
					session_start();

					$_SESSION['id'] = $row['id'];
					$_SESSION['name'] = $row['name'];

					header("Location: ./home.php?login=success");
					exit();
				}else{
					$errors['error'] = 'Invalid password';
				}
			}else{
				$errors['error'] = 'No results found';
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
		<div class="col-md-5 m-auto">
			<div class="card card-body border-success">
				<form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
					<?php if($errors['error']): ?>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<div class="text-center"><?= $errors['error'] ?></div>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					<?php endif ?>
					<div class="row justify-content-center">
						<div class="col-md-10 mt-4">
							<?php if($errors['email']): ?>
								<input type="email" name="email" class="form-control border-danger"" id="email" placeholder="Email">
								<small class="text-danger"><?= $errors['email']?></small>
							<?php else: ?>
								<input type="email" name="email" class="form-control border-success"" id="email" placeholder="Email">
							<?php endif ?>
						</div>
						<div class="col-md-10 mt-3">
							<?php if($errors['password']): ?>
								<input type="password" name="password" class="form-control border-danger"" id="password" placeholder="Password">
								<small class="text-danger"><?= $errors['password']?></small>
							<?php else: ?>
								<input type="password" name="password" class="form-control border-success"" id="password" placeholder="Password">
							<?php endif ?>
						</div>
						<div class="col-md-10 mt-4">
							<button type="submit" name="login-submit" class="btn btn-success form-control" id="submit">Login</button>
						</div>
						<div class="col-md-10 mt-2 mb-3">
							<p class="lead">Don't Have an Account? <a href="register.php" class="text-success">Sign Up</a></p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	</div>
</section>

<?php include('./templates/footer.php');?>