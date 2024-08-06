<?php
require __DIR__ .  "/../config/app.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "");
}

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary" style="width: 100%;">
    <div class="container">
        <a class="navbar-brand mr-auto" href="home.php">
            <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Logo" height="30" class="d-inline-block align-top mr-2">
            PT.Sinar Metrindo Perkasa
        </a>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><?= $_SESSION['name']; ?></button>
            <div class="dropdown-menu">
                <a type="button" class="dropdown-item" data-toggle="modal" data-target="#updateUserModal">Update Profile</a>
                <a type="button" href="<?= BASE_URL; ?>/modules/auth/logout.php" id="logoutButton" class="btn btn-danger d-block">Log Out</a>
            </div>
        </div>
    </div>
</nav>

<!-- Update Modal -->
<div class="modal fade mt-5" id="updateUserModal" tabindex="-1" aria-labelledby="updateUser" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModal">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= BASE_URL; ?>/modules/user/update.php" method="POST">
                    <div class="form-group row">
                        <div class="col-6">
                            <input type="text" name="tbl_user_id" id="updateUserID" hidden>
                            <label for="updateFirstName">First Name:</label>
                            <input type="text" class="form-control" id="updateFirstName" name="first_name">
                        </div>
                        <div class="col-6">
                            <label for="updateLastName">Last Name:</label>
                            <input type="text" class="form-control" id="updateLastName" name="last_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5">
                            <label for="updateContactNumber">Contact Number:</label>
                            <input type="number" class="form-control" id="updateContactNumber" name="contact_number" maxlength="11">
                        </div>
                        <div class="col-7">
                            <label for="updateEmail">Email:</label>
                            <input type="text" class="form-control" id="updateEmail" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="updateUsername">Username:</label>
                        <input type="text" class="form-control" id="updateUsername" name="username">
                    </div>
                    <div class="form-group">
                        <label for="updatePassword">Password:</label>
                        <input type="text" class="form-control" id="updatePassword" name="password">
                    </div>
                    <button type="submit" class="btn btn-dark login-register form-control">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>