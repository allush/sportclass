<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type"     content="text/html; charset=utf-8" />
        <meta http-equiv="pragma"           content="no-cache" />
        <meta http-equiv="content-language" content="ru" />

        <link rel="icon" type="image/x-icon" href="img/icon.png" />
        <link rel="stylesheet" type="text/css" href="css/auth.css" />
        
        
        <script type="text/javascript" src="js/jquery.js"></script>
         <script type="text/javascript">
            $(document).ready(function () {
                $("form[name=auth] input[name=auth]").focus();
            });
        </script>
    </head>
    <body>
        <form name="auth" action="auth.php" method="post">
            <p>e-mail:</p>
            <input type="text"      name="email" required="yes"/>
            <p>пароль:</p>
            <input type="password"  name="password"  required="yes"/>
            <input type="submit"    name="auth" value="Войти" />
            <a href="../">Вернуться на сайт</a>
        </form>  
    </body>
</html>