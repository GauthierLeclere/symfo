const $ = require('jquery');
require('bootstrap');
require('../scss/admin/admin.scss');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
import 'jquery';
import 'startbootstrap-sb-admin-2/js/sb-admin-2.js';

import 'datatables.net-bs4/css/dataTables.bootstrap4.css';
import 'datatables.net-bs4/js/dataTables.bootstrap4.js';
import 'datatables.net-buttons/js/dataTables.buttons.js';

// window.FontAwesomeConfig = { autoReplaceSvg: 'yes' }

global.$ = global.jQuery = $;

const routes = require('../../public/js/fos_js_routes.json');
const Routing = require('../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js');

Routing.setRoutingData(routes);
global.Routing = Routing;


$(function () {
    console.log('test admin');
});

window.loaderButton = function(button){
    button.addClass('disabled').children().attr('data-icon', 'spinner').addClass('fa-pulse');
}

window.unLoaderButton = function(button, classToAdd = ''){
    button.removeClass('disabled').children().attr('data-icon', classToAdd).removeClass('fa-pulse');
}