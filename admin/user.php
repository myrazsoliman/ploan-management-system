<?php
date_default_timezone_set("Etc/GMT+8");
require_once '../session.php';
require_once '../class.php';
$db = new db_class();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Loan Management System</title>

    <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


    <!-- Custom styles for this page -->
    <link href="../css/dataTables.bootstrap4.css" rel="stylesheet">

</head>

<body>
    <?php
    include('sidebar.php');
    ?>

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Users</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tbl_user = $db->display_user();

                            while ($fetch = $tbl_user->fetch_array()) {
                                ?>

                                <tr>
                                    <td><?php echo $fetch['username'] ?></td>
                                    <td><?php echo $fetch['firstname'] . " " . $fetch['lastname'] ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item bg-warning text-white" href="#" data-toggle="modal"
                                                    data-target="#updateModal<?php echo $fetch['user_id'] ?>"><i
                                                        class="fa fa-edit fa-1x"></i> Edit</a>
                                                <?php
                                                if ($fetch['user_id'] == $_SESSION['user_id']) {
                                                    ?>
                                                    <a class="dropdown-item bg-danger text-white" href="#"
                                                        disabled="disabled"><i class="fa fa-exclamation fa-1x"></i>
                                                        Cannot Delete</a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="dropdown-item bg-danger text-white" href="#" data-toggle="modal"
                                                        data-target="#deleteModal<?php echo $fetch['user_id'] ?>"><i
                                                            class="fa fa-trash fa-1x"></i> Delete</a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>


                                    </td>
                                </tr>


                                <!-- Update User Modal -->
                                <div class="modal fade" id="updateModal<?php echo $fetch['user_id'] ?>" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="updateUser.php">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white">Edit User</h5>
                                                    <button class="close" type="button" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Username</label>
                                                        <input type="text" name="username"
                                                            value="<?php echo $fetch['username'] ?>" class="form-control"
                                                            required="required" />
                                                        <input type="hidden" name="user_id"
                                                            value="<?php echo $fetch['user_id'] ?>" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" name="password"
                                                            value="<?php echo $fetch['password'] ?>" class="form-control"
                                                            required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Firstname</label>
                                                        <input type="text" name="firstname"
                                                            value="<?php echo $fetch['firstname'] ?>" class="form-control"
                                                            required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Lastname</label>
                                                        <input type="text" name="lastname"
                                                            value="<?php echo $fetch['lastname'] ?>" class="form-control"
                                                            required="required" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update" class="btn btn-warning">Update</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>



                                <!-- Delete User Modal -->

                                <div class="modal fade" id="deleteModal<?php echo $fetch['user_id'] ?>" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white">System Information</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this record?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button"
                                                    data-dismiss="modal">Cancel</button>

                                                <form action="deleteUser.php" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    <input type="hidden" name="user_id"
                                                        value="<?php echo $fetch['user_id']; ?>">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Add User Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="addUser.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add User</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required="required" />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required="required" />
                        </div>
                        <div class="form-group">
                            <label>Firstname</label>
                            <input type="text" name="firstname" class="form-control" required="required" />
                        </div>
                        <div class="form-group">
                            <label>Lastname</label>
                            <input type="text" name="lastname" class="form-control" required="required" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="confirm" class="btn btn-primary">Confirm</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">System Information</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.bundle.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="js/jquery.easing.js"></script>


    <!-- Page level plugins -->
    <script src="js/jquery.dataTables.js"></script>
    <script src="js/dataTables.bootstrap4.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "order": [[3, "asc"]]
            });
        });
    </script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>

</html>