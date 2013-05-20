<?php
/***************************
*  includes/register.php
*	
*   this file is included
*	from the user_test.php
*   from the header.php
*
***************************/
?>
<div id="content">
    <b>Registration form</b>
    <form action="index.php" method="post">
        <label>Username: <input type="text" name="username"></label>
        <label>Email: <input type="text" name="email"></label>
        <label>First Name: <input type="text" name="firstname"></label>
        <label>Last Name: <input type="text" name="lastname"></label>
        <label>Password: <input type="password" name="password1"></label>
        <div class="notice"><b>Note:</b> Password must contain: uppercase, lowercase and digit. Must be alteast 8 characters long.</div>
        <label>Confirm Password: <input type="password" name="password2"></label>
            <input type="hidden" name="do" value="register"/>
        <input type="submit" name="Submit" value="Register" >
    </form>
</div>