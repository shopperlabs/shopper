<?php

namespace Shopper\Framework\Services;

use Exception;
use Throwable;
use ErrorException;
use ReflectionType;
use const PHP_VERSION;
use function is_object;
use ReeceM\Mocker\Mocked;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\View;
use Shopper\Framework\Traits\Mails\Mailables;
use Shopper\Framework\Traits\Mails\Templates;

class Mailable
{
    use Templates;
    use Mailables;

    /**
     * Default type examples for being passed to reflected classes.
     */
    public const TYPES = [
        'int' => 31,
        'string' => null,
        'bool' => false,
        'float' => 3.14159,
    ];

    /**
     * @param $simpleview
     * @param $view
     * @param false $template
     * @param null  $instance
     *
     * @return string|void
     *
     * @throws Throwable
     */
    public static function renderPreview($simpleview, $view, $template = false, $instance = null)
    {
        if (! View::exists($view)) {
            return;
        }

        if (! $template) {
            $obj = self::buildMailable($instance);
            $viewData = $obj->viewData;
            $_data = array_merge($instance->buildViewData(), $viewData);

            foreach ($_data as $key => $value) {
                if (! is_object($value)) {
                    $_data[$key] = '<span class="mailable-key" title="Variable">' . $key . '</span>';
                }
            }
        } else {
            $_data = [];
        }

        $_view = $view;

        try {
            if ($simpleview) {
                return htmlspecialchars_decode(view($_view, $_data)->render());
            }

            $_md = self::buildMailable(Markdown::class, 'make');

            return htmlspecialchars_decode($_md->render($_view, $_data));
        } catch (ErrorException $e) {
            $error = '<div class="alert alert-warning">
	    	<h5 class="alert-heading">Error:</h5>
	    	<p>' . $e->getMessage() . '</p>
	    	</div>';

            if ($template) {
                $error .= '<div class="alert alert-info">
				<h5 class="alert-heading">Notice:</h5>
				<p>You can\'t add variables within a template editor because they are undefined until you bind the template with a mailable that actually has data.</p>
	    	</div>';
            }

            return $error;
        }
    }

    /**
     * Gets any missing params that may not be collectable in the reflection.
     *
     * @param string $arg    the argument string|
     * @param array  $params the reflection param list
     *
     * @return array|\ReeceM\Mocker\Mocked|string
     */
    private static function getMissingParams($arg, $params)
    {
        /**
         * Determine if a builtin type can be found.
         * Not a string or object as a Mocked::class can work there.
         *
         * getName() is undocumented alternative to casting to string.
         * https://www.php.net/manual/en/class.reflectiontype.php#124658
         *
         * @var ReflectionType $reflection
         */
        $reflection = collect($params)->where('name', $arg)->first()->getType();

        if (version_compare(PHP_VERSION, '7.1', '>=')) {
            $type = null !== $reflection
                ? self::TYPES[$reflection->getName()]
                : null;
        } else {
            $type = null !== $reflection
                ? self::TYPES[
                    // @scrutinizer ignore-deprecated
                    $reflection->__toString()
                ]
                : null;
        }

        try {
            return null !== $type
                ? $type
                : new Mocked($arg, \ReeceM\Mocker\Utils\VarStore::singleton());
        } catch (Exception $e) {
            return $arg;
        }
    }
}
