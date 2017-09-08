<?php
	
	session_start();
	$pageTitle = 'Members';
	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') { 

			$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1");
			$stmt->execute();
			$rows = $stmt->fetchAll();


			?>
			<h1 class="text-center">Manage Member</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Username</td>
							<td>E-mail</td>
							<td>FullName</td>
							<td>Registerd Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>" . $row['Username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['RDate'] ."</td>";
									echo "<td> 
												<a class='btn btn-success' href='members.php?do=Edit&userid="
												. $row['UserID'] ."'><i class='fa fa-edit'></i>Edit</a>
												<a class='btn btn-danger confirm' href='members.php?do=Delete&userid="
												. $row['UserID'] ."'><i class='fa fa-close'></i>Delete</a>
										 </td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<a class="btn btn-primary" href='members.php?do=Add'><i class="fa fa-plus"></i> Add NEW Member </a>
			</div>

	<?php	
			} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add Member</h1>

				<div class="container">
					<form class="form-horizontel" action="?do=Insert" method="POST">

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> UserName </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="text" name="username" class="form-control" 
								autocomplete="" required="required" placeholder="Don't be less than 3 CHAR">	
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> Password </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="password" name="password" class="password form-control" autocomplete=""
								required="required" placeholder="Must be hard">
								<i class="show-pass fa fa-eye"></i>
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> Email </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="email" name="email" class="form-control" 
								required="required" placeholder="Must be valid">	
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> Full Name </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="text" name="fullname" class="form-control" 
								required="required" placeholder="Name your Profile">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<input type="submit" value="Add Member" class="btn btn-primary btn-lg">	
							</div>
						</div>

					</form>
				</div>
			
	<?php	} elseif ($do == "Insert") {

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					echo "<h1 class='text-center'>Insert Members</h1>";
					echo "<div class='container'>";

					$username 	= $_POST['username'];
					$password 	= $_POST['password'];
					$email 		= $_POST['email'];
					$fullname 	= $_POST['fullname'];
					$hashpass   = sha1($_POST['password']);
					
					//Validate the Form

					$formErrors = array();

					if (empty($username) || empty($email) || empty($fullname) || empty($password)) {
						$formErrors[] = "You must full all empties!";
					} if (strlen($username) < 3) {
						$formErrors[] = "Username can't be less than <strong>3 CHAR</strong>";
						//LOOP ento Errors array and echo it
					} foreach ($formErrors as $errors) {
						echo "'<div class='alert alert-danger'>" . $errors . "</div>";
					}

					if (empty($formErrors)) {
						/*$check = checkItem("Username" , "users" , $username);

						if ($check == 1) {
							$theMsg = "<div class='alert alert-danger'>Sorry This User is EXIST!</div>";
							redirectHome($theMsg , 'back');
						} else {*/
						// Insert on Database
						$stmt = $con->prepare("INSERT INTO users(Username , Password , Email , FullName , RDate) 
							VALUES(:user , :pass , :email , :full , now())");

						$stmt -> execute(array(
						'user' => $username,
						'pass' => $hashpass,
						'email'=> $email,
						'full' => $fullname
						
						));
						
						echo "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>';

						}
					
					} else {
						$errorMsg = "SORRY! You can not Browse this page ..";
						redirectHome($errorMsg , 4);
					}

			} elseif ($do == 'Edit') {

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) {
?>

				<h1 class="text-center">Edit Member</h1>

				<div class="container">
					<form class="form-horizontel" action="?do=Update" method="POST">

						<input type="hidden" name="userid" value="<?php echo $userid ?>">

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> UserName </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="text" name="username" class="form-control" 
								value="<?php echo $row['Username']; ?>" autocomplete="off" required="required">	
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> Password </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="If you don't want change password, leave the blank">
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> Email </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="email" name="email" class="form-control" 
								value="<?php echo $row['Email']; ?>" required="required">	
							</div>
						</div>

						<div class="form-group form-group-lg">
							<label class="col-sm-offset-2 col-sm-2 control-label"> Full Name </label>
							<div class="col-sm-6 col-sm-onset-2">
								<input type="text" name="fullname" class="form-control" 
								value="<?php echo $row['FullName']; ?>" required="required">	
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<input type="submit" value="Save" class="btn btn-primary btn-lg">	
							</div>
						</div>

					</form>
				</div>

<?php  
		} else {
			 $errorMsg = "There is NO SUCH ID!!";

			redirectHome($errorMsg);
		}
		//Update Page Codeing...

	} elseif ($do == 'Update') {
		
		echo "<h1 class='text-center'>Update Members</h1>";
		echo "<div class='container'>";

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$id 		= $_POST['userid'];
			$username 	= $_POST['username'];
			$email 		= $_POST['email'];
			$fullname 	= $_POST['fullname'];
			//Password Trick...

			$pass = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);
			
			//Validate the Form

			$formErrors = array();

			if (empty($username) || empty($email) || empty($fullname)) {
				$formErrors[] = "<div class='alert alert-danger'>You must full all empties!</div>";
			} if (strlen($username) < 3) {
				$formErrors[] = "<div class='alert alert-danger'>Username can't be less than <strong>3 CHAR</strong></div>";
				//LOOP ento Errors array and echo it
			} foreach ($formErrors as $errors) {
				echo $errors;
			}

			if (empty($formErrors)) {
				// Update on Database
				$stmt = $con->prepare("UPDATE users SET Username = ? , Password = ? , Email = ? , FullName = ? WHERE UserID = ?");
				$stmt->execute(array($username , $pass , $email , $fullname , $id));

				echo "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>';
			}
			
		} else {
			$errorMsg = "SORRY! You can not Browse this page ..";

			redirectHome($errorMsg , 5);
		}
		echo "</div>";

		/*================================== Delete Page =============================*/
	} elseif ($do == 'Delete') {

		echo "<h1 class='text-center'>Delete Members</h1>";
		echo "<div class='container'>";

		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

		$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
		$stmt->execute(array($userid));
		$count = $stmt->rowCount();

		if ($stmt->rowCount() > 0) {

		$stmt = $con->prepare("DELETE FROM users WHERE UserID = :user");
		$stmt->bindParam(":user" , $userid);
		$stmt->execute();

		echo "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted' . "</div>";
		} else {
			echo "<div class='alert alert-danger'> This ID is not Exist! </div>";
		}
		echo "</div>";
	}

		include $tpl . 'footer.php';
	
	} else { 
	
		header('Location: index.php');

		exit();
	
	}