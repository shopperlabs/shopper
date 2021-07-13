<?php

namespace Shopper\Framework\Services;

use Illuminate\Support\Arr;
use Shopper\Framework\Exceptions\InvalidEmailException;

class Gravatar
{
    /**
     * Gravatar base url
     *
     * @var string
     */
    private $publicBaseUrl = 'https://www.gravatar.com/avatar/';

    /**
     * Gravatar secure base url
     *
     * @var string
     */
    private $secureBaseUrl = 'https://secure.gravatar.com/avatar/';

    /**
     * Email address to check
     *
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $fallback;

    /**
     * Override the default image fallback set in the config.
     * Can either be a public URL to an image or a valid themed image.
     * For more info, visit http://en.gravatar.com/site/implement/images/#default-image
     *
     * @param string|bool $fallback
     *
     * @return $this
     */
    public function fallback($fallback)
    {
        if (
            filter_var($fallback, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)
            || in_array($fallback, ['mm', 'identicon', 'monsterid', 'wavatar', 'retro', 'blank'])
        ) {
            $this->fallback = $fallback;
        } else {
            $this->fallback = false;
        }

        return $this;
    }

    /**
     * Check if Gravatar has an avatar for the given email address
     *
     * @param $email
     *
     * @return bool
     *
     * @throws InvalidEmailException
     */
    public function exists($email)
    {
        $this->checkEmail($email);
        $this->email = $email;
        $this->setConfig(['fallback' => 404]);
        $headers = @get_headers($this->buildUrl());

        return (bool) strpos($headers[0], '200');
    }

    /**
     * Get the gravatar url
     *
     * @param $email
     *
     * @return string
     *
     * @throws InvalidEmailException
     */
    public function get($email)
    {
        $this->checkEmail($email);
        $this->setConfig();
        $this->email = $email;

        return $this->buildUrl();
    }

    /**
     * Helper function to retrieve config settings.
     *
     * @param string $value
     * @param string|null $default
     *
     * @return bool
     */
    protected function c($value, $default = null)
    {
        return array_key_exists($value, $this->config) ? $this->config[$value] : $default;
    }

    /**
     * Helper function for setting the config based on either:
     * 1. The name of a config group
     * 2. A custom array
     * 3. The default group in the config
     *
     * @param string|array|null $group
     *
     * @return $this
     */
    private function setConfig($group = null)
    {
        $default = [
            'size' => 80,
            'fallback' => 'mm',
            'secure' => false,
            'maximumRating' => 'g',
            'forceDefault' => false,
            'forceExtension' => 'jpg',
        ];

        if (is_array($group)) {
            $this->config = Arr::dot(array_replace_recursive($default, $group));
        } else {
            $this->config = Arr::dot($default);
        }

        return $this;
    }

    /**
     * Helper function to md5 hash the email address
     *
     * @return string
     */
    private function hashEmail()
    {
        return md5(strtolower(trim($this->email)));
    }

    /**
     * @return string
     */
    private function getExtension()
    {
        $v = $this->c('forceExtension');

        return $v ? '.' . $v : '';
    }

    /**
     * @return string
     */
    private function buildUrl()
    {
        $url = $this->c('secure') === true ? $this->secureBaseUrl : $this->publicBaseUrl;
        $url .= $this->hashEmail();
        $url .= $this->getExtension();
        $url .= $this->getUrlParameters();

        return $url;
    }

    /**
     * @return string
     */
    private function getUrlParameters()
    {
        $build = [];
        foreach (get_class_methods($this) as $method) {
            if (substr($method, -strlen('Parameter')) !== 'Parameter') {
                continue;
            }
            if ($called = call_user_func([$this, $method])) {
                $build = array_replace($build, $called);
            }
        }

        return '?' . http_build_query($build);
    }

    /**
     * Check if the provided email address is valid
     *
     * @param $email
     *
     * @throws InvalidEmailException
     */
    private function checkEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException(__('Please specify a valid email address'));
        }
    }
}
