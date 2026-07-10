<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    protected function isEnabled()
    {
        $enabled = getenv('MAIL_ENABLED');

        if ($enabled === FALSE || $enabled === '') {
            return FALSE;
        }

        return in_array(strtolower((string) $enabled), array('1', 'true', 'yes', 'on'), TRUE);
    }

    /**
     * Send a simple HTML receipt confirmation email.
     * Failures are logged and returned; callers should not roll back payments.
     *
     * @param object $receipt
     * @param object $order
     * @param object $user
     * @return array
     */
    public function sendReceiptEmail($receipt, $order, $user)
    {
        if (!$this->isEnabled()) {
            return array(
                'success' => TRUE,
                'message' => 'Mail is disabled. Receipt email skipped.',
            );
        }

        if (!$receipt || !$order || !$user || empty($user->email)) {
            return array(
                'success' => FALSE,
                'message' => 'Missing receipt, order, or user email.',
            );
        }

        $fromEmail = getenv('MAIL_FROM_EMAIL') ?: 'noreply@commerce-portal.test';
        $fromName = getenv('MAIL_FROM_NAME') ?: 'Commerce Portal';
        $receiptUrl = site_url('user/receipts/show/' . (int) $receipt->id);

        $amount = html_escape($receipt->currency) . ' ' . html_escape(number_format((float) $receipt->amount, 2));
        $issuedAt = html_escape(date('d M Y, H:i', strtotime($receipt->issued_at)));

        $body = ''
            . '<p>Hi ' . html_escape($user->name) . ',</p>'
            . '<p>Thank you for your purchase. Your payment receipt is ready.</p>'
            . '<p>Your PDF receipt is attached to this email.</p>'
            . '<ul>'
            . '<li><strong>Receipt:</strong> ' . html_escape($receipt->receipt_number) . '</li>'
            . '<li><strong>Order:</strong> ' . html_escape($order->order_number) . '</li>'
            . '<li><strong>Amount paid:</strong> ' . $amount . '</li>'
            . '<li><strong>Issued on:</strong> ' . $issuedAt . '</li>'
            . '</ul>'
            . '<p><a href="' . html_escape($receiptUrl) . '">View your receipt in the portal</a></p>'
            . '<p>Regards,<br>' . html_escape($fromName) . '</p>';

        $pdfPath = NULL;

        if (!class_exists('Receipt_pdf_service', FALSE)) {
            require_once APPPATH . 'services/Receipt_pdf_service.php';
        }

        $pdfService = new Receipt_pdf_service();
        $pdfPath = $pdfService->generateReceiptPdf($receipt, $order, $user);

        try {
            $this->CI->load->library('email');
            $this->CI->config->load('email', TRUE);
            $this->CI->email->initialize($this->CI->config->item('email'));
            $this->CI->email->clear(TRUE);

            $this->CI->email->from($fromEmail, $fromName);
            $this->CI->email->to($user->email);
            $this->CI->email->subject('Your receipt ' . $receipt->receipt_number);
            $this->CI->email->message($body);

            if ($pdfPath && is_file($pdfPath)) {
                $this->CI->email->attach($pdfPath);
            } else {
                log_message('error', 'Receipt PDF missing for receipt #' . (int) $receipt->id . '; sending email without attachment.');
            }

            $sent = $this->CI->email->send();

            if ($sent) {
                return array(
                    'success' => TRUE,
                    'message' => 'Receipt email sent successfully.',
                );
            }

            $debug = $this->CI->email->print_debugger(array('headers'));
            log_message('error', 'Receipt email failed for receipt #' . (int) $receipt->id . ': ' . $debug);

            return array(
                'success' => FALSE,
                'message' => 'Unable to send receipt email.',
            );
        } catch (Exception $exception) {
            log_message('error', 'Receipt email exception for receipt #' . (int) $receipt->id . ': ' . $exception->getMessage());

            return array(
                'success' => FALSE,
                'message' => 'Unable to send receipt email.',
            );
        } finally {
            if ($pdfPath && is_file($pdfPath)) {
                @unlink($pdfPath);
            }
        }
    }
}