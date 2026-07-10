<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Form_request
{
    /** @var CI_Controller */
    protected $CI;

    /** @var array|null */
    protected $data = NULL;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('form_validation');
    }

    /**
     * Validate against an array instead of $_POST (JSON / service input).
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array list of [field, label, rules]
     */
    abstract public function rules();

    /**
     * @return bool
     */
    public function validate()
    {
        $this->CI->form_validation->reset_validation();

        if (is_array($this->data)) {
            $this->CI->form_validation->set_data($this->data);
        }

        foreach ($this->rules() as $rule) {
            $this->CI->form_validation->set_rules($rule[0], $rule[1], $rule[2]);
        }

        return $this->CI->form_validation->run() !== FALSE;
    }

    /**
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function input($key = NULL, $default = NULL)
    {
        if (is_array($this->data)) {
            if ($key === NULL) {
                return $this->data;
            }

            return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
        }

        if ($key === NULL) {
            return $this->CI->input->post(NULL, TRUE);
        }

        $value = $this->CI->input->post($key, TRUE);
        return ($value === NULL || $value === FALSE) ? $default : $value;
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->CI->form_validation->error_array();
    }
}