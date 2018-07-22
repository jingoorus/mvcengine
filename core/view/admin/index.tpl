<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{metatitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{THEME}/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{THEME}/dist/css/flat-ui.min.css" rel="stylesheet">
    <link href="{THEME}/dist/css/adminzone.css" rel="stylesheet">
    <link href="{THEME}/editor/summernote.css" rel="stylesheet">
   <!--<link rel="shortcut icon" href="{THEME}/img/favicon.ico">-->
    <!--[if lt IE 9]>
      <script src="{THEME}/js/vendor/html5shiv.js"></script>
      <script src="{THEME}/js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <!--[not-login]-->
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01"><span class="sr-only">Свернуть</span></button>
                <a class="navbar-brand" href="https://github.com/jingoorus/mvcengine" target="_blank">JMVC 0.3</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-01">
                <ul class="nav navbar-nav">
                    <li><a href="/admin/editpages/">Pages</a></li>
                    <li><a href="/admin/editsettings/">Settings</a></li>
                    <!--<li><a href="/admin/editextensions/">Extensions</a></li>-->
                    <li><a href="/admin/editusers/">Users</a></li>
                </ul>
                <form class="navbar-form navbar-right" action="/admin/searchpages/" role="search">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="search" placeholder="Enter page name">
                            <span class="input-group-btn">
                                <button type="submit" class="btn"><span class="fui-search"></span></button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </nav>
        <!--[/not-login]-->
        <div id="alertBlock">{info}</div>
        {content}
    </div>
    <script src="{THEME}/dist/js/vendor/jquery.min.js"></script>
    <!--Выпадающие окна в текстовом редакторе не работют на старой версии, а на новой надо отредактировать flat-ui.js-->
    <!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>-->
    <script src="{THEME}/dist/js/flat-ui.min.js"></script>
    <script src="{THEME}/editor/summernote.js"></script>
    <script src="{THEME}/dist/js/adminzone.js"></script>
    <script src="{THEME}/dist/js/vendor/video.js"></script>
  </body>
</html>
