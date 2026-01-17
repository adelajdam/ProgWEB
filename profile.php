<?php
session_start();
require_once "config.php";

// Kontroll login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}


$user_id = $_SESSION['user_id'];

// Kontrollo nëse përdoruesi është i verifikuar
$stmtCheck = $conn->prepare("SELECT is_verified FROM users WHERE id=?");
$stmtCheck->bind_param("i", $user_id);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result();
$userCheck = $resCheck->fetch_assoc();

if (!$userCheck || $userCheck['is_verified'] != 1) {
    die("Duhet të verifikoni email-in për të hapur profilin.");
}

// Merr të dhënat e përdoruesit
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Siguro çelësat
$user = array_merge([
    'full_name' => '',
    'phone' => '',
    'profile_image' => '',
    'address' => ''
], $user);

// Process form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = $_POST['name'];
    $phone   = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg','jpeg','png','gif'];
        $filename = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $newFileName = 'profile_'.$user_id.'_'.time().'.'.$ext;
            $uploadPath = 'uploads/' . $newFileName;

            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                // Update DB me emrin e file
                $user['profile_image'] = $newFileName;
            }
        }
    }

    $update = $conn->prepare("
        UPDATE user_profiles 
        SET full_name=?, phone=?, address=?, profile_image=? 
        WHERE user_id=?
    ");
    $update->bind_param("ssssi", $name, $phone, $address, $user['profile_image'], $user_id);
    $update->execute();

    header("Location: profile.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | MyShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="Profile/style.css">
</head>
<body>

<div class="container light-style flex-grow-1 container-p-y">
    <h4>Account settings</h4>

    <div class="top-icons">
        <a href="index.php" title="Home">
            <i class="fas fa-home"></i>
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="row no-gutters row-bordered row-border-light">

            <!-- SIDEBAR -->
            <div class="col-md-3 pt-0">
                <div class="list-group list-group-flush account-settings-links">
                    <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">Info</a>
                    <a class="list-group-item list-group-item-action logout-btn" href="logout.php">Logout</a>
                </div>
            </div>


            <!-- CONTENT -->
            <div class="col-md-9">
                <form method="post" enctype="multipart/form-data">

                    <div class="tab-content">

                        <!-- GENERAL -->
                        <div class="tab-pane fade show active" id="account-general">

                            <div class="card-body media align-items-center">
                                <?php $img = $user['profile_image'] ? 'uploads/'.$user['profile_image'] : 'https://static.vecteezy.com/system/resources/previews/009/292/244/original/default-avatar-icon-of-social-media-user-vector.jpg'; ?>
                                <img src="<?= $img ?>" alt="User avatar" class="d-block ui-w-80">

                                <div class="media-body ml-4">
                                    <label class="btn btn-outline-primary mb-0">
                                        Upload new photo
                                        <input type="file" name="profile_image" class="d-none" accept="image/*">
                                    </label>

                                    <button type="button" class="btn btn-secondary ml-2">
                                        Reset
                                    </button>

                                    <div class="text-muted small mt-1">
                                        Allowed JPG, GIF or PNG. Max size of 800KB
                                    </div>
                                </div>
                            </div>

                            <hr class="border-light m-0">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" name="name" type="text" class="form-control"
                                           value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <?php
                                    $stmtEmail = $conn->prepare("SELECT email FROM users WHERE id=?");
                                    $stmtEmail->bind_param("i", $user_id);
                                    $stmtEmail->execute();
                                    $resEmail = $stmtEmail->get_result();
                                    $userEmail = $resEmail->fetch_assoc();
                                    ?>
                                    <input id="email" name="email" type="email" class="form-control"
                                           value="<?= htmlspecialchars($userEmail['email'] ?? '') ?>" required readonly>


                                </div>
                            </div>
                        </div>

                        <!-- CHANGE PASSWORD -->
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label for="currentPassword">Current password</label>
                                    <input id="currentPassword" type="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="newPassword">New password</label>
                                    <input id="newPassword" type="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="repeatPassword">Repeat new password</label>
                                    <input id="repeatPassword" type="password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- INFO -->
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Contacts</h6>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input id="phone" name="phone" type="tel" class="form-control"
                                           value="<?= htmlspecialchars($user['phone']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input id="address" name="address" type="text" class="form-control"
                                           value="<?= htmlspecialchars($user['address']) ?>">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- BUTTONS -->
                    <div class="text-right mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js" defer></script>

</body>
</html>
