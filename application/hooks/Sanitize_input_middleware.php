<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sanitize incoming request data (Laravel SanitizeInput-style for CI3).
 *
 * - strip_tags on string values
 * - recursive for arrays
 * - skips passwords and Stripe webhooks
 */
class Sanitize_input_middleware
{
    public function run()
    {
        $CI =& get_instance();
        $CI->config->load('sanitize', TRUE);

        if (!$CI->config->item('sanitize_enabled', 'sanitize')) {
            return;
        }

        $directory = strtolower((string) $CI->router->directory);
        $exceptDirs = $CI->config->item('sanitize_except_directories', 'sanitize');

        if (is_array($exceptDirs)) {
            foreach ($exceptDirs as $prefix) {
                if (strpos($directory, strtolower($prefix)) === 0) {
                    return;
                }
            }
        }

        $exceptFields = $CI->config->item('sanitize_except_fields', 'sanitize');
        if (!is_array($exceptFields)) {
            $exceptFields = array();
        }
        $exceptFields = array_map('strtolower', $exceptFields);

        if (!empty($_POST)) {
            $_POST = $this->cleanArray($_POST, $exceptFields);
        }

        if (!empty($_GET)) {
            $_GET = $this->cleanArray($_GET, $exceptFields);
        }

        // CI may also expose these
        if (!empty($_REQUEST)) {
            $_REQUEST = $this->cleanArray($_REQUEST, $exceptFields);
        }
    }

    /**
     * @param array $data
     * @param array $exceptFields lowercase field names
     * @return array
     */
    protected function cleanArray(array $data, array $exceptFields)
    {
        $clean = array();

        foreach ($data as $key => $value) {
            $keyStr = is_string($key) ? $key : (string) $key;
            $keyLower = strtolower($keyStr);

            // Light key cleanup (no deprecated FILTER_SANITIZE_STRING)
            $cleanKey = preg_replace('/[^\w\-\.\:\[\]]+/', '', $keyStr);
            if ($cleanKey === NULL || $cleanKey === '') {
                $cleanKey = $keyStr;
            }

            if (in_array($keyLower, $exceptFields, TRUE)) {
                $clean[$cleanKey] = $value;
                continue;
            }

            if (is_array($value)) {
                $clean[$cleanKey] = $this->cleanArray($value, $exceptFields);
                continue;
            }

            if (is_string($value)) {
                $clean[$cleanKey] = $this->cleanString($value);
                continue;
            }

            $clean[$cleanKey] = $value;
        }

        return $clean;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function cleanString($value)
    {
        // Remove HTML/PHP tags; keep normal text
        $value = strip_tags($value);

        // Normalize null bytes
        $value = str_replace(chr(0), '', $value);

        return $value;
    }
}