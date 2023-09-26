<?php

namespace Abivia\Ledger\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LedgerBalancePoint extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'balance_id',
        'balance',
        'start_date',
        'balance_updated',
        'last_in_transaction_id',
        'last_out_transaction_id'
    ];
}
