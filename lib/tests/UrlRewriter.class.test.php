<?php

require_once('../UrlRewriter.class.php');

$test = UrlRewriter::Transform("/static/js/hello.js");
printf("<h1>%s</h1>", $test);

putenv("ASSETS_HOSTNAME=");
putenv("ASSETS_VERSION=");
$test = UrlRewriter::Transform("/static/js/hello.js");
printf("<h1>%s</h1>", $test);

putenv("ASSETS_HOSTNAME=assets.foo.com");
putenv("ASSETS_VERSION=202e");
$test = UrlRewriter::Transform("/static/js/hello.js");
printf("<h1>%s</h1>", $test);

putenv("ASSETS_HOSTNAME=assets.foo.com");
putenv("ASSETS_VERSION=");
$test = UrlRewriter::Transform("/static/js/hello.min.js");
printf("<h1>%s</h1>", $test);

putenv("ASSETS_HOSTNAME=");
putenv("ASSETS_VERSION=202e");
$test = UrlRewriter::Transform("/static/js/hello.js");
printf("<h1>%s</h1>", $test);
