yii2-ica-authentication
=======================

Authentication mechanism for Yii-based projects. Provides a password reset mechanism and an editor for assigning roles. Updated for Yii 2

Usage:
------
This authentication mechanism extends the default Yii 2 RBAC mechanism (see: http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#role-based-access-control-rbac)
and more specifically the DbManager (see http://www.yiiframework.com/doc-2.0/yii-rbac-dbmanager.html). Configure these and don't forget
to initialize the database using the command

```
php yii migrate --migrationPath=@yii/rbac/migrations/
```

Note you will also need to add authentication config to your console config.

Next, setup i18n, both in your console config and in your web config.

Once you've done all that, run the migration:

```
php yii migrate --migrationPath=@icalab/auth/migrations
```

Next run the init command:

```
php yii ica-init-auth
```

This will set up the user table and the resetpassword table. It will also create an admin user with admin@admin.com as the email / login
and "admin" as the password and the roles that are required for editing and updating other users.

Next you will have to tell the application to use the ICALab User model
instead of the default one. Change:

```
'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
```

To:

```
'user' => [
            'identityClass' => 'icalab\auth\models\User',
            'enableAutoLogin' => true,
        ],
```

You also need to make the default SiteController use the ICALab LoginForm
model instead of the default one. Change:

```
use app\models\LoginForm;
```

To:

```
use icalab\auth\models\LoginForm;
```

The URL for adding a new user is user/create.

The URL for listing existing users is user/index.

Users can request new passwords by first calling password-reset-generate with a post variable named email. If the email address 
points to a known user, a new reset token is generated and an email message is sent out containing the link to the reset page. If the email address is unknown, nothing happens.
In both cases, no output is generated.

TODO:
-----
This is a quick update of the Yii 1 ICA Authentication mechanism (https://github.com/HAN-ICA-ICT/yii-ica-authentication). Since we're
in a hurry the editor for roles and permissions has not been implemented yet. Tests also have not been implemented yet.
However, it does work and has been proven useful in a number of projects
already.
