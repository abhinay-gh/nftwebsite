<?php

declare(strict_types=1);

namespace Atk4\Ui;

use atk4\core\AppScopeTrait;
use atk4\core\DiContainerTrait;
use atk4\core\DynamicMethodTrait;
use atk4\core\FactoryTrait;
use atk4\core\HookTrait;
use atk4\core\InitializerTrait;
use atk4\data\Persistence;
use atk4\ui\Exception\ExitApplicationException;
use atk4\ui\Persistence\Ui as UiPersistence;
use Psr\Log\LoggerInterface;

class App
{
    use InitializerTrait {
        init as _init;
    }
    use HookTrait;
    use DynamicMethodTrait;
    use FactoryTrait;
    use AppScopeTrait;
    use DiContainerTrait;

    /** @const string */
    public const HOOK_BEFORE_EXIT = self::class . '@beforeExit';
    /** @const string */
    public const HOOK_BEFORE_RENDER = self::class . '@beforeRender';
    /** @const string not used, make it public if needed or drop it */
    private const HOOK_BEFORE_OUTPUT = self::class . '@beforeOutput';

    /** @const string */
    protected const HEADER_STATUS_CODE = 'atk4-status-code';

    /** @var array|false Location where to load JS/CSS files */
    public $cdn = [
        'atk' => 'https://cdn.jsdelivr.net/gh/atk4/ui@develop/public',
        'jquery' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1',
        'serialize-object' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-serialize-object/2.5.0',
        'semantic-ui' => 'https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.6',
    ];

    /** @var string Version of Agile UI */
    public $version = '2.3.0';

    /** @var string Name of application */
    public $title = 'Agile UI - Untitled Application';

    /** @var Layout */
    public $layout; // the top-most view object

    /**
     * Set one or more directories where templates should reside.
     *
     * @var string|array
     */
    public $template_dir;

    /** @var string Name of skin */
    public $skin = 'semantic-ui';

    /**
     * Will replace an exception handler with our own, that will output errors nicely.
     *
     * @var bool
     */
    public $catch_exceptions = true;

    /**
     * Will display error if callback wasn't triggered.
     *
     * @var bool
     */
    public $catch_runaway_callbacks = true;

    /**
     * Will always run application even if developer didn't explicitly executed run();.
     *
     * @var bool
     */
    public $always_run = true;

    /**
     * Will be set to true after app->run() is called, which may be done automatically
     * on exit.
     *
     * @var bool
     */
    public $run_called = false;

    /**
     * Will be set to true, when exit is called. Sometimes exit is intercepted by shutdown
     * handler and we don't want to execute 'beforeExit' multiple times.
     *
     * @var bool
     */
    private $exit_called = false;

    /** @var bool */
    public $is_rendering = false;

    /** @var UiPersistence */
    public $ui_persistence;

    /** @var View For internal use */
    public $html;

    /** @var LoggerInterface Target for objects with DebugTrait */
    public $logger;

    /** @var Persistence|Persistence\Sql */
    public $db;

    /** @var string[] Extra HTTP headers to send on exit. */
    protected $response_headers = [
        'cache-control' => 'no-store', // disable caching by default
    ];

    /**
     * @var bool whether or not semantic-ui vue has been initialised
     */
    private $is_sui_init = false;

    /**
     * @var string used in method App::url to build the url
     *
     * Used only in method App::url
     * Remove and re-add the extension of the file during parsing requests and building urls
     */
    protected $url_building_ext = '.php';

    /**
     * Call exit in place of throw Exception when Application need to exit.
     *
     * @var bool
     */
    public $call_exit = true;

    /**
     * @var string|null
     */
    public $page;

    /**
     * @var array global sticky arguments
     */
    protected $sticky_get_arguments = [
        '__atk_json' => false,
        '__atk_tab' => false,
    ];

    public $templateClass = Template::class;

    /**
     * Constructor.
     *
     * @param array $defaults
     */
    public function __construct($defaults = [])
    {
        $this->app = $this;

        // Process defaults
        if (is_string($defaults)) {
            $defaults = ['title' => $defaults];
        }

        if (isset($defaults[0])) {
            $defaults['title'] = $defaults[0];
            unset($defaults[0]);
        }

        /*
        if (is_array($defaults)) {
            throw (new Exception('Constructor requires array argument'))
                ->addMoreInfo('arg', $defaults);
        }*/
        $this->setDefaults($defaults);
        /*

        foreach ($defaults as $key => $val) {
            if (is_array($val)) {
                $this->{$key} = array_merge(is_array($this->{$key} ?? null) ? $this->{$key} : [], $val);
            } elseif ($val !== null) {
                $this->{$key} = $val;
            }
        }
         */

        $this->setupTemplateDirs();

        // Set our exception handler
        if ($this->catch_exceptions) {
            set_exception_handler(\Closure::fromCallable([$this, 'caughtException']));
            set_error_handler(
                static function ($severity, $msg, $file, $line) {
                    throw new \ErrorException($msg, 0, $severity, $file, $line);
                },
                \E_ALL
            );
        }

        // Always run app on shutdown
        if ($this->always_run) {
            $this->setupAlwaysRun();
        }

        // Set up UI persistence
        if (!isset($this->ui_persistence)) {
            $this->ui_persistence = new UiPersistence();
        }
    }

    protected function setupTemplateDirs()
    {
        if ($this->template_dir === null) {
            $this->template_dir = [];
        } elseif (!is_array($this->template_dir)) {
            $this->template_dir = [$this->template_dir];
        }

        $this->template_dir[] = dirname(__DIR__) . '/template/' . $this->skin;
    }

    /**
     * @param bool $for_shutdown if true will not pass in caughtException method
     */
    public function callExit($for_shutdown = false): void
    {
        if (!$this->exit_called) {
            $this->exit_called = true;
            $this->hook(self::HOOK_BEFORE_EXIT);
        }

        if ($for_shutdown) {
            return;
        }

        if (!$this->call_exit) {
            // case process is not in shutdown mode
            // App as already done everything
            // App need to stop output
            // set_handler to catch/trap any exception
            set_exception_handler(function (\Throwable $t) {});
            // raise exception to be trapped and stop execution
            throw new ExitApplicationException();
        }

        exit;
    }

    /**
     * Catch exception.
     */
    public function caughtException(\Throwable $exception): void
    {
        $this->catch_runaway_callbacks = false;

        // just replace layout to avoid any extended App->_construct problems
        // it will maintain everything as in the original app StickyGet, logger, Events
        $this->html = null;
        $this->initLayout([Layout\Centered::class]);

        $this->layout->template->setHtml('Content', $this->renderExceptionHtml($exception));

        // remove header
        $this->layout->template->tryDel('Header');

        if (($this->isJsUrlRequest() || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest')
                && !isset($_GET['__atk_tab'])) {
            $this->outputResponseJson([
                'success' => false,
                'message' => $this->layout->getHtml(),
            ]);
        } else {
            $this->setResponseStatusCode(500);
            $this->run();
        }

        // Process is already in shutdown/stop
        // no need of call exit function
        $this->callExit(true);
    }

    /**
     * Normalize HTTP headers to associative array with LC keys.
     *
     * @param string[] $headers
     *
     * @return string[]
     */
    protected function normalizeHeaders(array $headers): array
    {
        $res = [];
        foreach ($headers as $k => $v) {
            if (is_numeric($k) && ($p = strpos($v, ':')) !== false) {
                $k = substr($v, 0, $p);
                $v = substr($v, $p + 1);
            }

            $res[strtolower(trim($k))] = trim($v);
        }

        return $res;
    }

    /**
     * @return $this
     */
    public function setResponseStatusCode(int $statusCode): self
    {
        $this->setResponseHeader(self::HEADER_STATUS_CODE, (string) $statusCode);

        return $this;
    }

    /**
     * @return $this
     */
    public function setResponseHeader(string $name, string $value): self
    {
        $arr = $this->normalizeHeaders([$name => $value]);
        $value = reset($arr);
        $name = key($arr);

        if ($value !== '') {
            $this->response_headers[$name] = $value;
        } else {
            unset($this->response_headers[$name]);
        }

        return $this;
    }

    /**
     * Will perform a preemptive output and terminate. Do not use this
     * directly, instead call it form Callback, JsCallback or similar
     * other classes.
     *
     * @param string|array $output  Array type is supported only for JSON response
     * @param string[]     $headers content-type header must be always set or consider using App::terminateHtml() or App::terminateJson() methods
     */
    public function terminate($output = '', array $headers = []): void
    {
        $headers = $this->normalizeHeaders($headers);
        if (empty($headers['content-type'])) {
            $this->response_headers = $this->normalizeHeaders($this->response_headers);
            if (empty($this->response_headers['content-type'])) {
                throw new Exception('Content type must be always set');
            }

            $headers['content-type'] = $this->response_headers['content-type'];
        }

        $type = preg_replace('~;.*~', '', strtolower($headers['content-type'])); // in LC without charset

        if ($type === 'application/json') {
            if (is_string($output)) {
                $output = $this->decodeJson($output);
            }
            $output['modals'] = $this->getRenderedModals();

            $this->outputResponseJson($output, $headers);
        } elseif (isset($_GET['__atk_tab']) && $type === 'text/html') {
            // ugly hack for TABS
            // because fomantic ui tab only deal with html and not JSON
            // we need to hack output to include app modal.
            $keys = null;
            $remove_function = '';
            foreach ($this->getRenderedModals() as $key => $modal) {
                // add modal rendering to output
                $keys[] = '#' . $key;
                $output['atkjs'] = $output['atkjs'] . ';' . $modal['js'];
                $output['html'] = $output['html'] . $modal['html'];
            }
            if ($keys) {
                $ids = implode(',', $keys);
                $remove_function = '$(\'.ui.dimmer.modals.page\').find(\'' . $ids . '\').remove();';
            }
            $output = '<script>jQuery(function() {' . $remove_function . $output['atkjs'] . '});</script>' . $output['html'];

            $this->outputResponseHtml($output, $headers);
        } elseif ($type === 'text/html') {
            $this->outputResponseHtml($output, $headers);
        } else {
            $this->outputResponse($output, $headers);
        }

        $this->run_called = true; // prevent shutdown function from triggering.
        $this->callExit();
    }

    public function terminateHtml($output, array $headers = []): void
    {
        if ($output instanceof View) {
            $output = $output->render();
        } elseif ($output instanceof Template) {
            $output = $output->render();
        }

        $this->terminate(
            $output,
            array_merge($this->normalizeHeaders($headers), ['content-type' => 'text/html'])
        );
    }

    public function terminateJson($output, array $headers = []): void
    {
        if ($output instanceof View) {
            $output = $output->renderToJsonArr();
        }

        $this->terminate(
            $output,
            array_merge($this->normalizeHeaders($headers), ['content-type' => 'application/json'])
        );
    }

    /**
     * Initializes layout.
     *
     * @param string|Layout|array $seed
     *
     * @return $this
     */
    public function initLayout($seed)
    {
        $layout = Layout::fromSeed($seed);
        $layout->app = $this;

        if (!$this->html) {
            $this->html = new View(['defaultTemplate' => 'html.html']);
            $this->html->app = $this;
            $this->html->invokeInit();
        }

        $this->layout = $this->html->add($layout);

        $this->initIncludes();

        return $this;
    }

    /**
     * Initialize JS and CSS includes.
     */
    public function initIncludes()
    {
        // jQuery
        $this->requireJs($this->cdn['jquery'] . '/jquery.min.js');

        // Semantic UI
        $this->requireJs($this->cdn['semantic-ui'] . '/semantic.min.js');
        $this->requireCss($this->cdn['semantic-ui'] . '/semantic.min.css');

        // Serialize Object
        $this->requireJs($this->cdn['serialize-object'] . '/jquery.serialize-object.min.js');

        // Agile UI
        $this->requireJs($this->cdn['atk'] . '/atkjs-ui.min.js');
        $this->requireCss($this->cdn['atk'] . '/agileui.css');

        // Set js bundle dynamic loading path.
        $this->html->template->trySet('InitJsBundle', (new JsExpression('window.__atkBundlePublicPath = [];', [$this->cdn['atk']]))->jsRender(), false);
    }

    /**
     * Adds a <style> block to the HTML Header. Not escaped. Try to avoid
     * and use file include instead.
     *
     * @param string $style CSS rules, like ".foo { background: red }".
     */
    public function addStyle($style)
    {
        if (!$this->html) {
            throw new Exception('App does not know how to add style');
        }
        $this->html->template->appendHtml('HEAD', $this->getTag('style', $style));
    }

    /**
     * Add a new object into the app. You will need to have Layout first.
     *
     * @param View|string|array $seed   New object to add
     * @param string|array|null $region
     */
    public function add($seed, $region = null): AbstractView
    {
        if (!$this->layout) {
            throw (new Exception('App layout is missing'))
                ->addSolution('If you use $app->add() you should first call $app->initLayout()');
        }

        return $this->layout->add($seed, $region);
    }

    /**
     * Runs app and echo rendered template.
     */
    public function run()
    {
        $isExitException = false;
        try {
            $this->run_called = true;
            $this->hook(self::HOOK_BEFORE_RENDER);
            $this->is_rendering = true;

            // if no App layout set
            if (!isset($this->html)) {
                throw new Exception('App layout should be set.');
            }

            $this->html->template->set('title', $this->title);
            $this->html->renderAll();
            $this->html->template->appendHtml('HEAD', $this->html->getJs());
            $this->is_rendering = false;
            $this->hook(self::HOOK_BEFORE_OUTPUT);

            if (isset($_GET['__atk_callback']) && $this->catch_runaway_callbacks) {
                throw new Exception('Callback requested, but never reached. You may be missing some arguments in request URL.');
            }

            $output = $this->html->template->render();
        } catch (ExitApplicationException $e) {
            $output = '';
            $isExitException = true;
        }

        if (!$this->exit_called) { // output already send by terminate()
            if ($this->isJsUrlRequest()) {
                $this->outputResponseJson($output);
            } else {
                $this->outputResponseHtml($output);
            }
        }

        if ($isExitException) {
            $this->callExit();
        }
    }

    /**
     * Initialize app.
     */
    protected function init(): void
    {
        $this->_init();
    }

    /**
     * Load template by template file name.
     *
     * @param string $name
     *
     * @return Template
     */
    public function loadTemplate($name)
    {
        $template = new $this->templateClass();
        $template->app = $this;

        if (in_array($name[0], ['.', '/', '\\'], true) || strpos($name, ':\\') !== false) {
            return $template->load($name);
        }

        $dir = is_array($this->template_dir) ? $this->template_dir : [$this->template_dir];
        foreach ($dir as $td) {
            if ($t = $template->tryLoad($td . '/' . $name)) {
                return $t;
            }
        }

        throw (new Exception('Can not find template file'))
            ->addMoreInfo('name', $name)
            ->addMoreInfo('template_dir', $this->template_dir);
    }

    protected function getRequestUrl()
    {
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
            $request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) { // Apache
            $request_uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0, PHP as CGI
            $request_uri = $_SERVER['ORIG_PATH_INFO'];
        // This one comes without QUERY string
        } else {
            $request_uri = '';
        }
        $request_uri = explode('?', $request_uri, 2);

        return $request_uri[0];
    }

    /**
     * Make current get argument with specified name automatically appended to all generated URLs.
     */
    public function stickyGet(string $name, bool $isDeleting = false): ?string
    {
        $this->sticky_get_arguments[$name] = !$isDeleting;

        return $_GET[$name] ?? null;
    }

    /**
     * Remove sticky GET which was set by stickyGet.
     */
    public function stickyForget(string $name)
    {
        unset($this->sticky_get_arguments[$name]);
    }

    /**
     * Build a URL that application can use for loading HTML data.
     *
     * @param array|string $page                URL as string or array with page name as first element and other GET arguments
     * @param bool         $needRequestUri      Simply return $_SERVER['REQUEST_URI'] if needed
     * @param array        $extraRequestUriArgs additional URL arguments, deleting sticky can delete them
     *
     * @return string
     */
    public function url($page = [], $needRequestUri = false, $extraRequestUriArgs = [])
    {
        if ($needRequestUri) {
            $page = $_SERVER['REQUEST_URI'];
        }

        if ($this->page === null) {
            $requestUrl = $this->getRequestUrl();
            if (substr($requestUrl, -1, 1) === '/') {
                $this->page = 'index';
            } else {
                $this->page = basename($requestUrl, $this->url_building_ext);
            }
        }

        $pagePath = '';
        if (is_string($page)) {
            $page_arr = explode('?', $page, 2);
            $pagePath = $page_arr[0];
            parse_str($page_arr[1] ?? '', $page);
        } else {
            $pagePath = $page[0] ?? $this->page; // use current page by default
            unset($page[0]);
            if ($pagePath) {
                $pagePath .= $this->url_building_ext;
            }
        }

        $args = $extraRequestUriArgs;

        // add sticky arguments
        foreach ($this->sticky_get_arguments as $k => $v) {
            if ($v && isset($_GET[$k])) {
                $args[$k] = $_GET[$k];
            } else {
                unset($args[$k]);
            }
        }

        // add arguments
        foreach ($page as $k => $v) {
            if ($v === null || $v === false) {
                unset($args[$k]);
            } else {
                $args[$k] = $v;
            }
        }

        // put URL together
        $pageQuery = http_build_query($args, '', '&', PHP_QUERY_RFC3986);
        $url = $pagePath . ($pageQuery ? '?' . $pageQuery : '');

        return $url;
    }

    /**
     * Build a URL that application can use for js call-backs. Some framework integration will use a different routing
     * mechanism for NON-HTML response.
     *
     * @param array|string $page                URL as string or array with page name as first element and other GET arguments
     * @param bool         $needRequestUri      Simply return $_SERVER['REQUEST_URI'] if needed
     * @param array        $extraRequestUriArgs additional URL arguments, deleting sticky can delete them
     *
     * @return string
     */
    public function jsUrl($page = [], $needRequestUri = false, $extraRequestUriArgs = [])
    {
        // append to the end but allow override
        $extraRequestUriArgs = array_merge($extraRequestUriArgs, ['__atk_json' => 1], $extraRequestUriArgs);

        return $this->url($page, $needRequestUri, $extraRequestUriArgs);
    }

    /**
     * Request was made using App::jsUrl().
     */
    public function isJsUrlRequest(): bool
    {
        return isset($_GET['__atk_json']) && $_GET['__atk_json'] !== '0';
    }

    /**
     * Adds additional JS script include in application template.
     *
     * @param string $url
     * @param bool   $isAsync whether or not you want Async loading
     * @param bool   $isDefer whether or not you want Defer loading
     *
     * @return $this
     */
    public function requireJs($url, $isAsync = false, $isDefer = false)
    {
        $this->html->template->appendHtml('HEAD', $this->getTag('script', ['src' => $url, 'defer' => $isDefer, 'async' => $isAsync], '') . "\n");

        return $this;
    }

    /**
     * Adds additional CSS stylesheet include in application template.
     *
     * @param string $url
     *
     * @return $this
     */
    public function requireCss($url)
    {
        $this->html->template->appendHtml('HEAD', $this->getTag('link/', ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => $url]) . "\n");

        return $this;
    }

    /**
     * A convenient wrapper for sending user to another page.
     *
     * @param array|string $page Destination page
     */
    public function redirect($page, bool $permanent = false): void
    {
        $this->terminateHtml('', ['location' => $this->url($page), self::HEADER_STATUS_CODE => $permanent ? '301' : '302']);
    }

    /**
     * Generate action for redirecting user to another page.
     *
     * @param string|array $page Destination URL or page/arguments
     */
    public function jsRedirect($page, bool $newWindow = false): JsExpression
    {
        return new JsExpression('window.open([], [])', [$this->url($page), $newWindow ? '_blank' : '_top']);
    }

    /**
     * Construct HTML tag with supplied attributes.
     *
     * $html = getTag('img/', ['src'=>'foo.gif','border'=>0]);
     * // "<img src="foo.gif" border="0"/>"
     *
     *
     * The following rules are respected:
     *
     * 1. all array key=>val elements appear as attributes with value escaped.
     * getTag('div/', ['data'=>'he"llo']);
     * --> <div data="he\"llo"/>
     *
     * 2. boolean value true will add attribute without value
     * getTag('td', ['nowrap'=>true]);
     * --> <td nowrap>
     *
     * 3. null and false value will ignore the attribute
     * getTag('img', ['src'=>false]);
     * --> <img>
     *
     * 4. passing key 0=>"val" will re-define the element itself
     * getTag('img', ['input', 'type'=>'picture']);
     * --> <input type="picture" src="foo.gif">
     *
     * 5. use '/' at end of tag to close it.
     * getTag('img/', ['src'=>'foo.gif']);
     * --> <img src="foo.gif"/>
     *
     * 6. if main tag is self-closing, overriding it keeps it self-closing
     * getTag('img/', ['input', 'type'=>'picture']);
     * --> <input type="picture" src="foo.gif"/>
     *
     * 7. simple way to close tag. Any attributes to closing tags are ignored
     * getTag('/td');
     * --> </td>
     *
     * 7b. except for 0=>'newtag'
     * getTag('/td', ['th', 'align'=>'left']);
     * --> </th>
     *
     * 8. using $value will add value inside tag. It will also encode value.
     * getTag('a', ['href'=>'foo.html'] ,'click here >>');
     * --> <a href="foo.html">click here &gt;&gt;</a>
     *
     * 9. you may skip attribute argument.
     * getTag('b','text in bold');
     * --> <b>text in bold</b>
     *
     * 10. pass array as 3rd parameter to nest tags. Each element can be either string (inserted as-is) or
     * array (passed to getTag recursively)
     * getTag('a', ['href'=>'foo.html'], [['b','click here'], ' for fun']);
     * --> <a href="foo.html"><b>click here</b> for fun</a>
     *
     * 11. extended example:
     * getTag('a', ['href'=>'hello'], [
     *    ['b', 'class'=>'red', [
     *        ['i', 'class'=>'blue', 'welcome']
     *    ]]
     * ]);
     * --> <a href="hello"><b class="red"><i class="blue">welcome</i></b></a>'
     *
     * @param string|array $tag
     * @param string       $attr
     * @param string|array $value
     *
     * @return string
     */
    public function getTag($tag = null, $attr = null, $value = null)
    {
        if ($tag === null) {
            $tag = 'div';
        } elseif (is_array($tag)) {
            $tmp = $tag;

            if (isset($tmp[0])) {
                $tag = $tmp[0];

                if (is_array($tag)) {
                    // OH a bunch of tags
                    $output = '';
                    foreach ($tmp as $subtag) {
                        $output .= $this->getTag($subtag);
                    }

                    return $output;
                }

                unset($tmp[0]);
            } else {
                $tag = 'div';
            }

            if (isset($tmp[1])) {
                $value = $tmp[1];
                unset($tmp[1]);
            } else {
                $value = null;
            }

            $attr = $tmp;
        }

        $tag = strtolower($tag);

        if ($tag[0] === '<') {
            return $tag;
        }
        if (is_string($attr)) {
            $value = $attr;
            $attr = null;
        }

        if ($value !== null) {
            $result = [];
            foreach ((array) $value as $v) {
                if (is_array($v)) {
                    $result[] = $this->getTag(...$v);
                } elseif (in_array($tag, ['script', 'style'], true)) {
                    // see https://mathiasbynens.be/notes/etago
                    $result[] = preg_replace('~(?<=<)(?=/\s*' . preg_quote($tag, '~') . '|!--)~', '\\\\', $v);
                } elseif (is_array($value)) { // todo, remove later and fix wrong usages, this is the original behaviour, only directly passed strings were escaped
                    $result[] = $v;
                } else {
                    $result[] = $this->encodeHtml($v);
                }
            }
            $value = implode('', $result);
        }

        if (!$attr) {
            return "<{$tag}>" . ($value !== null ? $value . "</{$tag}>" : '');
        }
        $tmp = [];
        if (substr($tag, -1) === '/') {
            $tag = substr($tag, 0, -1);
            $postfix = '/';
        } elseif (substr($tag, 0, 1) === '/') {
            return '</' . ($attr[0] ?? substr($tag, 1)) . '>';
        } else {
            $postfix = '';
        }
        foreach ($attr as $key => $val) {
            if ($val === false) {
                continue;
            }
            if ($val === true) {
                $tmp[] = "{$key}";
            } elseif ($key === 0) {
                $tag = $val;
            } else {
                $tmp[] = "{$key}=\"" . $this->encodeAttribute($val) . '"';
            }
        }

        return "<{$tag}" . ($tmp ? (' ' . implode(' ', $tmp)) : '') . $postfix . '>' . ($value !== null ? $value . "</{$tag}>" : '');
    }

    /**
     * Encodes string - removes HTML special chars.
     *
     * @param string $val
     *
     * @return string
     */
    public function encodeAttribute($val)
    {
        return htmlspecialchars((string) $val);
    }

    /**
     * Encodes string - removes HTML entities.
     */
    public function encodeHtml(string $val): string
    {
        return htmlentities($val);
    }

    public function decodeJson(string $json)
    {
        $data = json_decode($json, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);

        return $data;
    }

    public function encodeJson($data, bool $forceObject = false): string
    {
        $options = JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        if ($forceObject) {
            $options |= JSON_FORCE_OBJECT;
        }

        $json = json_encode($data, $options | JSON_THROW_ON_ERROR, 512);

        // IMPORTANT: always convert large integers to string, otherwise numbers can be rounded by JS
        // replace large JSON integers only, do not replace anything in JSON/JS strings
        $json = preg_replace_callback('~(?:"(?:[^"\\\\]+|\\\\.)*")?+\K|(?:\'(?:[^\'\\\\]+|\\\\.)*\')?+\K|(?:^|[{\[,:])'
            . '[ \n\r\t]*\K-?[1-9]\d{15,}(?=[ \n\r\t]*(?:$|[}\],:]))~s', function ($matches) {
                if ($matches[0] === '' || abs((int) $matches[0]) < (2 ** 53)) {
                    return $matches[0];
                }

                return '"' . $matches[0] . '"';
            }, $json);

        return $json;
    }

    /**
     * Return exception message using HTML block and Semantic UI formatting. It's your job
     * to put it inside boilerplate HTML and output, e.g:.
     *
     *   $app = new \atk4\ui\App();
     *   $app->initLayout([\atk4\ui\Layout\Centered::class]);
     *   $app->layout->template->setHtml('Content', $e->getHtml());
     *   $app->run();
     *   $app->callExit(true);
     */
    public function renderExceptionHtml(\Throwable $exception): string
    {
        return (string) new \atk4\core\ExceptionRenderer\Html($exception);
    }

    protected function setupAlwaysRun(): void
    {
        register_shutdown_function(
            function () {
                if (!$this->run_called) {
                    try {
                        $this->run();
                    } catch (ExitApplicationException $e) {
                        // let the process go and stop on ->callExit below
                    } catch (\Throwable $e) {
                        // process is already in shutdown
                        // must be forced to catch exception
                        $this->caughtException($e);
                    }

                    // call with true to trigger beforeExit event
                    $this->callExit(true);
                }
            }
        );
    }

    // RESPONSES

    /** @var string[] */
    private static $_sentHeaders = [];

    /**
     * Output Response to the client.
     *
     * This can be overridden for future PSR-7 implementation
     */
    protected function outputResponse(string $data, array $headers): void
    {
        $this->response_headers = $this->normalizeHeaders($this->response_headers);
        $headersAll = array_merge($this->response_headers, $this->normalizeHeaders($headers));
        $headersNew = array_diff_assoc($headersAll, self::$_sentHeaders);

        $isCli = \PHP_SAPI === 'cli'; // for phpunit

        $lateError = null;
        foreach (ob_get_status(true) as $status) {
            if ($status['buffer_used'] !== 0 && !$isCli) {
                $lateError = 'Unexpected output detected.';

                break;
            }
        }

        if ($lateError === null && count($headersNew) > 0 && headers_sent() && !$isCli) {
            $lateError = 'Headers already sent, more headers can not be set at this stage.';
        }

        if (!headers_sent() || $isCli) {
            if ($lateError !== null) {
                $headersNew = ['content-type' => 'text/plain', self::HEADER_STATUS_CODE => '500'];
            }

            foreach ($headersNew as $k => $v) {
                if (!$isCli) {
                    if ($k === self::HEADER_STATUS_CODE) {
                        http_response_code($v === (string) (int) $v ? (int) $v : 500);
                    } else {
                        $kCamelCase = preg_replace_callback('~(?<![a-zA-Z])[a-z]~', function ($matches) {
                            return strtoupper($matches[0]);
                        }, $k);

                        header($kCamelCase . ': ' . $v);
                    }
                }

                self::$_sentHeaders[$k] = $v;
            }
        }

        if ($lateError !== null) {
            echo "\n" . '!! FATAL UI ERROR: ' . $lateError . ' !!' . "\n";

            exit(1);
        }

        echo $data;
    }

    /**
     * Output HTML response to the client.
     *
     * @param string[] $headers
     */
    private function outputResponseHtml(string $data, array $headers = []): void
    {
        $this->outputResponse(
            $data,
            array_merge($this->normalizeHeaders($headers), ['content-type' => 'text/html'])
        );
    }

    /**
     * Output JSON response to the client.
     *
     * @param string|array $data
     * @param string[]     $headers
     */
    private function outputResponseJson($data, array $headers = []): void
    {
        if (!is_string($data)) {
            $data = $this->encodeJson($data);
        }

        $this->outputResponse(
            $data,
            array_merge($this->normalizeHeaders($headers), ['content-type' => 'application/json'])
        );
    }

    /**
     * Generated html and js for modals attached to $html view.
     */
    public function getRenderedModals(): array
    {
        // prevent looping (calling App::terminateJson() recursively) if JsReload is used in Modal
        unset($_GET['__atk_reload']);

        $modals = [];
        foreach ($this->html !== null ? $this->html->elements : [] as $view) {
            if ($view instanceof Modal) {
                $modals[$view->name]['html'] = $view->getHtml();
                $modals[$view->name]['js'] = $view->getJsRenderActions();
            }
        }

        return $modals;
    }
}
