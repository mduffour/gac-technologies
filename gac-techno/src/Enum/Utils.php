<?php

declare(strict_types=1);

namespace App\Enum;

final class Utils
{
    const INVOICED_ACCOUNT = 'Compte facturé';
    const INVOICE_NUMBER = 'N° Facture';
    const USER_NUMBER = 'N° abonné';
    const DATE = 'Date';
    const HOUR = 'Heure';
    const DURATION_REAL_VOLUME = 'Durée/volume réel';
    const DURATION_INVOICE_VOLUME = 'Durée/volume facturé';
    const TYPE = 'Type';

    public static function getColumnNames()
    {
        return [
            'invoiced_account',
            'invoice_number',
            'user_number',
            'date_call',
            'hour_call',
            'real_duration',
            'real_volume',
            'invoiced_duration',
            'invoice_volume',
            'type_call'
        ];
    }
}