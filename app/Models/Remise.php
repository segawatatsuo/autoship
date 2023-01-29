<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remise extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'X-TRANID',
        'X-S_TORIHIKI_NO',
        'X-AMOUNT',
        'X-TAX',
        'X-TOTAL',
        'X-REFAPPROVED',
        'X-REFFORWARDED',
        'X-ERRCODE',
        'X-ERRINFO',
        'X-ERRLEVEL',
        'X-R_CODE',
        'REC_TYPE',
        'X-REFGATEWAYNO',
        'X-PAYQUICKID',
        'X-PARTOFCARD',
        'X-EXPIRE',
        'X-NAME',
        'X-AC_MEMBERID',
        'X-AC_S_KAIIN_NO',
        'X-AC_AMOUNT',
        'X-AC_TOTAL',
        'YYYYMMDD',
        'X-AC_INTERVAL',
        'X-CARDBRAND',
    ];
}
