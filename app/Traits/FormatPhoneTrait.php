<?php

namespace App\Traits;

trait FormatPhoneTrait
{
    /**
     * Format nomor WhatsApp ke format internasional (62xxx)
     */
    public function formatWhatsApp($number)
    {
        $whatsapp = preg_replace('/\D+/', '', $number);
        if (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        } elseif (str_starts_with($whatsapp, '620')) {
            $whatsapp = '62' . substr($whatsapp, 3);
        } elseif (!str_starts_with($whatsapp, '62')) {
            $whatsapp = '62' . $whatsapp;
        }
        return $whatsapp;
    }
}
