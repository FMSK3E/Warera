<?php ob_start();

    // On affiche un message d'erreur personnalisé si on a renvoyé une erreur en créant un compte
    if (isset($_GET['error'])) {
        if ($_GET['error'] == "emptyfields") {
            echo '<p class="text-danger" style="margin-left: 48px; margin-top: 15px">Fill in all fields !</p>';
        } else if ($_GET['error'] == "invalidemailuid") {
            echo '<p class="text-danger" style="margin-left: 48px; margin-top: 15px">Id and email are incorrect !</p>';
        } else if ($_GET['error'] == "invalidemail") {
            echo '<p class="text-danger" style="margin-left: 48px; margin-top: 15px">Email is incorrect !</p>';
        } else if ($_GET['error'] == "invaliduid") {
            echo '<p class="text-danger" style="margin-left: 48px; margin-top: 15px">Id is incorrect !</p>';
        } else if ($_GET['error'] == "password") {
            echo '<p class="text-danger" style="margin-left: 48px; margin-top: 15px">Passwords are not the same !</p>';
        } else if ($_GET['error'] == "usernameoremailtaken") {
            echo '<p class="text-danger" style="margin-left: 48px; margin-top: 15px">This username or email is already taken !</p>';
        }
    }
?>

<form class="ml-5" action="index.php?action=signup" method="POST">
    <div class="form-group my-2">
        <label for="uid">Username :</label>
        <?php
        if (isset($_GET['uid'])) {
            $uid = $_GET['uid'];
            echo '<input class="form-control" style="width: 250px;" type="text" id="uid" name="uid" value="'.$uid.'">';
        } else {
            echo '<input class="form-control" style="width: 250px;" type="text" id="uid" name="uid">';
        }
        ?>
    </div>
    <div class="form-group my-4">
        <label for="email">Email address :</label>
        <?php
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
            echo '<input class="form-control" style="width: 250px;" type="email" id="email" name="email" value="'.$email.'">';
        } else {
            echo '<input class="form-control" style="width: 250px;" type="email" id="email" name="email">';
        }
        ?>
    </div>
    <div class="form-group my-4">
        <label for="pwd">Password :</label>
        <input class="form-control" style="width: 250px;" type="password" id="pwd" name="pwd">
    </div>
    <div class="form-group my-4">
        <label for="repwd">Repeat Password :</label>
        <input class="form-control" style="width: 250px;" type="password" id="repwd" name="repwd">
    </div>
    <div class="form-group my-4">
        <label for="nationality">Select your country :</label>
        <select class="form-control" style="width: 250px;" type="text" id="nationality" name="nationality">
        <?php
        if (isset($_GET['nationality'])) {
            $nationality = $_GET['nationality'];
            foreach ($countries as $country) ?>
                <option <?php if ($country == $nationality) { echo 'selected="selected"'; } ?> value="<?= $country; ?>"><?= $country; ?></option>
        <?php
        } else {
            foreach ($countries as $country)
                echo '<option value="'.$country.'">'.$country.'</option>';
        }
        ?>
        </select>
    </div>
    <button class="btn btn-primary" type="submit" name="signup_submit">Submit</button>
</form>

<?php $content = ob_get_clean();

require 'base.php';