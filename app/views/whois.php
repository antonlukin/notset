<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Modern and convenient lookup service">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>notset services</title>

    <link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet">
    <link href="https://lukin.ams3.digitaloceanspaces.com/notset/favicon.png" rel="icon" type="image/png">
    <link rel="stylesheet" href="/assets/styles.min.css" type="text/css" media="all" />

    <meta name="theme-color" content="#000000">
</head>

<body>
    <section class="form-banner user-output">
        <p>Welcome to whois search service for domain names and IP address</p>

        <ul>
            <li>Version:       2.2</li>
            <li>License:       MIT</li>
            <li>Author:        Anton Lukin</li>
             <li>Author URI:    <a href="https://lukin.me">https://lukin.me</a></li>
            <li>Description:   Modern and convenient lookup service</li>
            <li>Project URI:   <a href="https://github.com/antonlukin/notset">https://github.com/antonlukin/notset</a></li>
        </ul>

         <p>Enter the domain or IP you wish to get whois information</p>
    </section>

    <form class="form-terminal user-input" action="/" method="get">
        <span class="prompt"><?php echo date('H:i'); ?> <b>user</b>@<i>notset</i>:/&gt;</span>
        <input type="text" name="q" value="<?php echo $query; ?>" autofocus>

        <input type="submit" tabindex="-1">
    </form>

<?php if(isset($reply)) : ?>
    <section class="form-response user-output">
        <code><?php echo $reply; ?></code>
    </section>
<?php endif; ?>

</body>
</html>
