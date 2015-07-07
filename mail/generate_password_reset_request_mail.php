<?php
/* @var $request ResetPassword */
/* @var $user User */
?>
<?php
use yii\helpers\Url;

$url = Url::base('http') . Url::toRoute(['/password-reset/request',
'token' => $request->request_token,
'email' => $request->user->email]);
?>
<html>
<head>
<title>Reset password</title>
<style type="text/css">
body {
/* font-family: Helvetica, Arial, sans-serif; */
}
</style>
</head>
<body>

Dear user,

<br /><br />

Someone, presumably you, has requested a new password for the account you use for the activiteitenweger app.

<br /><br />

If you want to do this, you can click the link below:

<br /><br />

<a href="<?php echo $url; ?>">
<?php echo $url; ?>
</a>

<br /><br />

Alternatively you can copy the url below to the address bar of your browser:

<br /><br />

<a href="<?php echo $url; ?>">
<?php echo $url; ?>
</a>

