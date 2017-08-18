<?php // Markup for the widget ?>
    <div id="mcsw-signup">
        <form method="POST" id="mcsw-form">
            <div class="mcsw-form-group">
                <label for="mcsw-firstName">First Name</label>
                <input type="text" name="mcsw-firstName" id="mcsw-firstName">
            </div>
            
            <div class="mcsw-form-group">
                <label for="mcsw-lastName">Last Name</label>
                <input type="text" name="mcsw-lastName" id="mcsw-lastName">
            </div>
            
            <div class="mcsw-form-group">
                <label for="mcsw-email">Email Address</label>
                <input type="email" name="mcsw-email" id="mcsw-email">
            </div>

            <input type="text" value="pending" id="mcsw-status" name="status" hidden>

            <input type="submit" value="Sign Up" class="mcsw-button">
        </form>

        <div id="mcsw-message"></div>
    </div>