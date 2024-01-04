<?php

defined('BASEPATH') || exit('No direct script access allowed');

use Proxy\Http\Request;
use Proxy\Proxy;
use Proxy\Plugin\ProxifyPlugin;
use Proxy\Plugin\CorsPlugin;

class Pricing extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->use_footer     = false;
        $this->use_navigation = false;
        $this->use_submenu    = false;

        $this->app_modules->is_inactive('saas') ? access_denied() : '';

        if (IS_TENANT) {
            redirect(site_url());
        }
    }

    public function index()
    {
        $data = [];
        // if staff or admin logged in then redirect to dashborad page
        if (is_staff_logged_in()) {
            redirect(site_url('admin'));
        }

        // module is not enabled and Landing page option is not enable then redirect to login page
        if (('1' != get_option('superadmin_enabled')) && ('1' != get_option('tenants_landing'))) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }

        // LOAD CUSTOM LANDING PAGE START
        if (!empty(get_option('perfex_saas_landing_page_url'))) {
            $this->customThemeUrl();
        }
        if (!empty(get_option('perfex_saas_landing_page_theme'))) {
            $this->customThemePage();
            return;
        }
        // OVER HERE
        $data['title']         = _l('landing');
        $data['announcements'] = '';

        $data['displaySettings'] = [
            'heroBanner'   => true,
            'heroFeature'  => true,
            'features'     => true,
            'includes'     => true,
            'testimonials' => true,
            'partners'     => true,
            'getApp'       => true,
            'footerMenus'  => true,
        ];

        $this->load->model('Superadmin_model', 'saas_model');
        $data['plans'] = $this->saas_model->get_saas_plan();

        $this->load->config('features_limitation_config');
        $data['limitations'] = config_item('limitations');

        $this->data($data);
        $this->view('landing/front');
        $this->layout(false);
    }

    public function layout($notInThemeViewFiles = false)
    {
        /*
         * Navigation and submenu
         * @deprecated 2.3.2
         * @var boolean
         */

        $this->data['use_navigation'] = true == $this->use_navigation;
        $this->data['use_submenu']    = true == $this->use_submenu;

        /*
         * @since  2.3.2 new variables
         * @var array
         */
        $this->data['navigationEnabled'] = true == $this->use_navigation;
        $this->data['subMenuEnabled']    = true == $this->use_submenu;

        /*
         * Theme head file
         * @var string
         */
        $this->template['head'] = $this->load->view(SUPERADMIN_MODULE.'/front_theme/head', $this->data, true);

        $GLOBALS['customers_head'] = $this->template['head'];

        /**
         * Load the template view.
         *
         * @var string
         */
        $module                       = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;
        $viewPath                     = null !== $module || $notInThemeViewFiles ?
        $this->view :
        $this->createThemeViewPath($this->view);

        $this->template['view']    = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        /*
         * Load the theme compiled template
         */
        $this->load->view(SUPERADMIN_MODULE.'/front_theme/index', $this->template);
    }

    public function customThemeUrl()
    {
        $customeUrl = get_option('perfex_saas_landing_page_url');
        if (get_option('perfex_saas_landing_page_url_mode') == 'redirection') {
            redirect($customeUrl);
        }

        $request = Request::createFromGlobals();

        $proxy = new Proxy();

        $proxy->getEventDispatcher()->addListener('request.before_send', function ($event) {

            $event['request']->headers->set('X-Forwarded-For', 'php-proxy');
        });

        $proxy->getEventDispatcher()->addListener('request.sent', function ($event) {

            if ($event['response']->getStatusCode() != 200) {
                show_error("Bad status code!", $event['response']->getStatusCode(), "Landing");
            }
        });

        $proxy->getEventDispatcher()->addListener('request.complete', function ($event) {

            $content = $event['response']->getContent();
            $content .= '<!-- via php-proxy -->';
            $event['response']->setContent($content);
        });

        $dispatcher = $proxy->getEventDispatcher();
        $proxify = new ProxifyPlugin();
        $proxify->subscribe($dispatcher);

        $cors = new CorsPlugin();
        $cors->subscribe($dispatcher);

        if (isset($_GET['q'])) {
            $customeUrl = url_decrypt($_GET['q']);
        }

        $response = $proxy->forward($request, $customeUrl);

        // send the response back to the client
        $response->send();
    }

    public function customThemePage()
    {
        list($themePath, $themeUrl) = get_theme_path_url();
        $landingFile = $themePath . get_option('perfex_saas_landing_page_theme');
        $themeName = dirname(str_ireplace($themePath, '', $landingFile));
        $themeUrl = $themeUrl . $themeName;

        $html = file_get_contents($landingFile);
        $html = str_ireplace(
            ['"assets/', '\'assets/',],
            ['"' . $themeUrl . '/assets/', "'$themeUrl/assets/"],
            $html
        );
        $html = str_ireplace(['(assets/', '(&quot;assets/'], ["(" . $themeUrl . '/assets/', '(&quot;' . $themeUrl . '/assets/'], $html);
        $html = str_ireplace(['[csrf_token_name]', '[csrf_token_hash]'], [$this->security->get_csrf_token_name(), $this->security->get_csrf_hash()], $html);

        $data['landing_page_content'] = $html;
        $this->data($data);
        $this->view('landing/custom_theme');
        $this->layout(false);
    }
}

/* End of file Pricing.php */
/* Location: ./modules/saas/controllers/Pricing.php */
