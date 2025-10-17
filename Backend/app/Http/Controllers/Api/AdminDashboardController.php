<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use BcMath\Number;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function topContent(){
        $currentMonth = now()->month;
        $previousMonth = now()->subMonth()->month;

        $users = User::where('role', 'user')->whereMonth('created_at', $currentMonth)->get();
        $previousMonthUsers =  User::where('role', 'user')->whereMonth('created_at', $previousMonth)->get();
        $usersPercentage = percentance_calculator($users->count(), $previousMonthUsers->count());

        $withdraws = Transaction::where('status', 'success')->where('type','withdraw')->whereMonth('created_at', $currentMonth)->sum('amount');
        $previousMonthWithdraw = Transaction::where('status', 'success')->where('type','withdraw')->whereMonth('created_at', $previousMonth)->sum('amount');
        $withdrawPercentage = percentance_calculator($withdraws, $previousMonthWithdraw);

        $deposites = Transaction::where('status', 'success')->where('type','deposit')->whereMonth('created_at', $currentMonth)->sum('amount');
        $previousMonthDeposites = Transaction::where('status', 'success')->where('type','deposit')->whereMonth('created_at', $previousMonth)->sum('amount');
        $depositesPercentage = percentance_calculator($deposites, $previousMonthDeposites);

        $profits = $deposites - $withdraws;
        $previousMonthProfits = $previousMonthDeposites - $previousMonthWithdraw;
        $profitsPercentage = percentance_calculator($profits, $previousMonthProfits);

        return response()->json([
            'users' => $users->count(),
            'usersPercentage' => number_format($usersPercentage,2),
            'withdraws' => number_format($withdraws,2),
            'withdrawPercentage' => number_format($withdrawPercentage,2),
            'deposites' => number_format($deposites,2),
            'depositesPercentage' => number_format($depositesPercentage,2),
            'profits' => number_format($profits,2),
            'profitsPercentage' => number_format($profitsPercentage,2),
        ]);
    }

}
