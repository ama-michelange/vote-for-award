;<?php die();?>
[cache]
enabled=0
lifetime= 0
autoload.enabled=0

[encodage]
;indique l'encodage des fichiers de code, utilise par defaut pour plugin_html::encode($texte)
charset=UTF-8

[language]
;fr / en... //sLangue
default=fr
allow=fr,en

[auth]
;note :  php5.2 dans le php.ini
session.cookie_httponly=1
session.use_cookies = 1
session.use_only_cookies = 1
session.cookie_secure=1
enabled=0
;class=plugin_auth
;module=auth::login
class=plugin_authent
module=default::index
session.timeout.enabled=1
session.timeout.lifetime=1800

[acl]
;class=plugin_gestionuser
class=plugin_authorization
modules=awards,docs,groups,home_enable,invitations,nominees,selections,roles,users

[navigation]
scriptname=index.php
var=:nav
module.default=default
action.default=index
layout.erreur=site/layout/erreurprod.php

[urlrewriting]
enabled=0
class=plugin_routing
conf=conf/routing.php
use4O4=0

[security]
;XSRF ou CSRF,bSecuriteXSRF utilisation de jeton dans le CRUD, plus d'infos: http://fr.wikipedia.org/wiki/Cross-Site_Request_Forgeries
;XSS, bSecuriteXSS protection des variables GET,POST... via getParam( , plus d'infos http://fr.wikipedia.org/wiki/XSS
xsrf.enabled=1
xsrf.timeout.lifetime=900
xsrf.session.enabled=0
xss.enabled=1
xss.onlyspecialchars=1

[log]
class=plugin_log
error=0
warning=0
application=1
information=0

[check]
class=plugin_vfa_check

[site]
;Redirection
;header : header('location:$url ')
;http: <html><head><META http-equiv="refresh" content="0; URL=$url" /></head></html>
redirection.default=header
timezone=Europe/Paris

[path]
lib=../mkf/

upload=data/upload/
log=data/log/
view=view/
data=data/
conf=conf/
module=module/
plugin=plugin/
model=model/
img=data/img/
i18n=data/i18n/
cache=data/cache/
layout=site/layout/
base=/vfa/vfa-app/

[model]
ini.var=db

[vfa-app]
title=Vote For Award
mail.from=alicesaward@free.fr
mail.from.label=Alices Award
