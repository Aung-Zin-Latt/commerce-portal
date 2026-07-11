<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rate_limit_service {
    protected $CI;
    protected $dir;

    public function __construct() {
        $this->CI =& get_instance();
        $this->dir = APPPATH . 'cache/rate_limit/';

        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, TRUE);
        }
    }

    /**
     * @param string $routeKey e.g. auth/login/authenticate
     * @param string $ip
     * @param int $max
     * @param int $windowSeconds
     * @return array{allowed:bool,retry_after:int,remaining:int}
     */
    public function attempt($routeKey, $ip, $max, $windowSeconds)
    {
        $max = max(1, (int) $max);
        $windowSeconds = max(1, (int) $windowSeconds);
        $now = time();

        $file = $this->dir . md5($routeKey . '|' . $ip) . '.json';
        $data = array('start' => $now, 'count' => 0);

        if (is_file($file)) {
            $raw = @file_get_contents($file);
            $decoded = json_decode((string) $raw, TRUE);
            if (is_array($decoded) && isset($decoded['start'], $decoded['count'])) {
                $data = $decoded;
            }
        }

        // New window
        if (($now - (int) $data['start']) >= $windowSeconds) {
            $data = array('start' => $now, 'count' => 0);
        }

        if ((int) $data['count'] >= $max) {
            $retryAfter = $windowSeconds - ($now - (int) $data['start']);
            return array(
                'allowed' => FALSE,
                'retry_after' => max(1, $retryAfter),
                'remaining' => 0,
            );
        }

        $data['count'] = (int) $data['count'] + 1;
        @file_put_contents($file, json_encode($data), LOCK_EX);
        
        return array(
            'allowed' => TRUE,
            'retry_after' => 0,
            'remaining' => max(0, $max - (int) $data['count']),
        );
    }

    
}