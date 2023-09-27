<?php

namespace Abivia\Ledger\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LedgerBalanceHistory extends Model
{
    use HasFactory;
    protected $table = "ledger_balance_history";
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'balance_id',
        'balance',
        'start_date',
        'balance_updated',
        'last_in_transaction_id',
        'last_out_transaction_id',
        'last_transaction_id'
    ];
}
