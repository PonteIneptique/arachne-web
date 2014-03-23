<article class="shadow-content padding">
    <h2>Your informations</h2>
    <div class="gamification">
        <table class="gamification">
            <tr>
                <td>
                    Name
                </td>
                <td style="text-indent:10px;">
                    <?= $_SESSION['user']['name']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Mail
                </td>
                <td style="text-indent:10px;">
                    <?= $_SESSION['user']['mail']; ?>
                </td>
            </tr>
            <tr>
                <td class="image-container">
                    <?= $_SESSION['user']['game']['image']; ?>
                </td>
                <td style="text-indent:10px;">
                    <?= $_SESSION['user']['game']['message']; ?>
                </td>
            </tr>
        </table>
        <?php //var_dump($_SESSION); ?>
    </div>
</article>
<section class="background-like">&nbsp;</sectin>
<article class="shadow-content padding">
    <h2>You can change them if you want ...</h2>
    <form action="/account/update" method="POST" class="login-form signup-form" role="form">
        <?if(isset($error["update"]) && isset($error["update"]["message"]) ):?>
        <div class="alert alert-warning">
            <?= $error["update"]["message"]; ?>
        </div>
        <?endif;?>
        <div class="form-group  field">
            <label for="mail" class="control-label">Name</label>
            <div class="input">
                <input class="form-control" placeholder=" Name" type="text" id="name" name="name" value="" >
                <?if(isset($error["update"]) && isset($error["update"]["name"])):?><span class="help-block text-muted"><?= $error["update"]["name"] ?></span><?endif;?>
            </div>
        </div>
        <div class="form-group  field">
            <label for="mail" class="control-label">Email</label>
            <div class="input">
                <input class="form-control" placeholder=" Email" type="email" id="mail" name="mail" value="" >
                <?if(isset($error["update"]) && isset($error["update"]["mail"])):?><span class="help-block text-muted"><?= $error["update"]["mail"] ?></span><?endif;?>
            </div>
        </div>
        <div class="form-group  field">
            <label for="password" class="control-label">Password</label>
            <div class="input">
                <input class="form-control" placeholder=" Password" type="password" id="password" name="password" >
                <?if(isset($error["update"]) && isset($error["update"]["password"])):?><span class="help-block text-muted"><?= $error["update"]["password"] ?></span><?endif;?>
            </div>
        </div>
        <div class="form-group  field">
            <label for="confirm" class="control-label">Confirm Password</label>
            <div class="input">
                <input class="form-control" placeholder=" Confirm Password" type="password" id="confirm" name="confirm" >
                <?if(isset($error["update"]) && isset($error["update"]["confirm"])):?><span class="help-block text-muted"><?= $error["update"]["confirm"] ?></span><?endif;?>
            </div>
        </div>
        <div class="form-group field">
            <div class="input">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</article>