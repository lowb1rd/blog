<?php# INIT_::init(array(    'templates_dir' => './views'));# LOG$log = _::Log();_::Registry()->set('log', $log);function l($msg, $lvl) {    _::Registry()->get('log')->log($msg, $lvl);}    # ROUTES$routes = array(    '' => array('index', 'index'), // Default route    #'#\d{4}/\d{2}\d{2}/.+#' => array('generate', 'post'),        //'#show/(\d+)/.+/?#' => array('article', 'show', array('id' => '$1')),    //'go/:id/aa' => array('url', 'show', array('id' => ':id')),);_::Router($routes, array())->route();# AUTH_::Auth()->setup(new AuthBackendArray());# ACL$acl = _::ACL();$acl->addRole('mod');$acl->addRole('admin', 'mod');$acl->allow('admin', 'admin');# VIEW$view = new _View();$view->setLayout('layout');_::Controller()->setView($view);# CONTROLLER_::Controller()->registerSlot('sidebar', 'slots/sidebar.php', 'views/_aside.phtml');_::Controller()->go();