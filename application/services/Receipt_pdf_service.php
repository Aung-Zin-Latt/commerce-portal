<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Receipt_pdf_service
{
    /**
     * Generate a simple receipt PDF and return the absolute file path.
     * Returns FALSE on failure.
     *
     * @param object $receipt
     * @param object $order
     * @param object $user
     * @return string|false
     */
    public function generateReceiptPdf($receipt, $order, $user)
    {
        if (!$receipt || !$order || !$user) {
            return FALSE;
        }

        $tmpDir = dirname(APPPATH) . '/writable/tmp';

        if (!is_dir($tmpDir) && !mkdir($tmpDir, 0755, TRUE) && !is_dir($tmpDir)) {
            log_message('error', 'Unable to create PDF temp directory: ' . $tmpDir);
            return FALSE;
        }

        $currency = html_escape($receipt->currency);
        $issuedAt = html_escape(date('d M Y, H:i', strtotime($receipt->issued_at)));

        $subtotal = isset($order->subtotal) ? (float) $order->subtotal : (float) $receipt->amount;
        $taxAmount = isset($order->tax_amount) ? (float) $order->tax_amount : 0.0;
        $totalAmount = isset($order->total_amount) ? (float) $order->total_amount : (float) $receipt->amount;

        $cssPath = FCPATH . 'assets/css/receipt-pdf.css';
        $css = is_file($cssPath) ? file_get_contents($cssPath) : '';

        if ($css === FALSE || $css === '') {
            log_message('error', 'Receipt PDF CSS missing or empty: ' . $cssPath);
        }

        $html = '
<html>
<head>
    <meta charset="utf-8">
    <style>' . $css . '</style>
</head>
<body>
    <p class="brand">Commerce Portal</p>
    <hr class="divider">

    <div class="meta">
        <div><strong>Receipt No.:</strong> ' . html_escape($receipt->receipt_number) . '</div>
        <div><strong>Date of Issue:</strong> ' . $issuedAt . '</div>
    </div>

    <div class="title">Receipt</div>

    <table>
        <thead>
            <tr>
                <th colspan="2">Contact Info</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">Contact Name</td>
                <td class="value">' . html_escape($user->name) . '</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">' . html_escape($user->email) . '</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2">Order</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">Order No.</td>
                <td class="value">' . html_escape($order->order_number) . '</td>
            </tr>
            <tr>
                <td class="label">Payment Status</td>
                <td class="value">Paid</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="section-left">Price Summary</th>
                <th class="section-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">' . $currency . ' ' . html_escape(number_format($subtotal, 2)) . '</td>
            </tr>
            <tr>
                <td class="label">Tax</td>
                <td class="value">' . $currency . ' ' . html_escape(number_format($taxAmount, 2)) . '</td>
            </tr>
            <tr class="total-row">
                <td class="label">Total</td>
                <td class="value">' . $currency . ' ' . html_escape(number_format($totalAmount, 2)) . '</td>
            </tr>
        </tbody>
    </table>

    <p class="footer">This receipt is automatically generated.</p>
</body>
</html>';

        try {
            $options = new Options();
            $options->set('isRemoteEnabled', FALSE);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $path = $tmpDir . '/receipt-' . (int) $receipt->id . '-' . uniqid('', TRUE) . '.pdf';

            if (file_put_contents($path, $dompdf->output()) === FALSE) {
                log_message('error', 'Unable to write receipt PDF: ' . $path);
                return FALSE;
            }

            return $path;
        } catch (Exception $exception) {
            log_message('error', 'Receipt PDF generation failed: ' . $exception->getMessage());
            return FALSE;
        }
    }
}