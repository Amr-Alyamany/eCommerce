<?php
	session_start();
	$noNavbar = '';
	$pageTitle = 'Login';
	/*if (isset($_SESSION['Username'])) {
		header('Location: dashboard.php');
	}*/

	include 'init.php';

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = $_POST['user'];
			$password = $_POST['pass'];
			$hashedPass = sha1($password);
		//Check if user found in database ... 
			$stmt = $con->prepare("SELECT UserID , Username , Password FROM users WHERE Username = ? 
				AND Password = ? AND GroupID = 1 LIMIT 1");
			$stmt->execute(array($username, $hashedPass));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) {
				$_SESSION['Username'] = $username; 
				$_SESSION['ID'] = $row['UserID'];
				header('Location: dashboard.php');
				exit();
			}
		}

	?>
	<div class="gradient">
			<table>
				<tbody>
					<tr>
						<td>
							<div class="container">
								<div class="form">
									<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST">
										<span>Welcome Admin</span>
										<h5>Username</h5>
										<input type="text" name="user" placeholder="User Name"/>
										<h5>Password</h5>
										<input class="password" type="password" name="pass" placeholder="Your Password"/>
										<input type="submit" value="Login"/>
									</form>
									
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
	</div>

<?php
	include $tpl . 'footer.php';

	?>