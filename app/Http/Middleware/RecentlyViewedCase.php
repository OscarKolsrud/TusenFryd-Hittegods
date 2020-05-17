<?php

namespace App\Http\Middleware;

use App\Models\Investigation;
use Closure;

class RecentlyViewedCase
{
    /**
     * Handle an incoming request and save to session
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $case = Investigation::where('reference', request()->segment(count(request()->segments())))->first();

        if (isset($case->reference)) {
            $arr = $request->session()->get('recentInvestigations');

            if (is_array($arr)) {
                //This blocks loops through the stored array to ensure there are no duplicates
                foreach ($arr as $key => $val) {
                    if ($val == $case->reference) {
                        unset($arr[$key]);
                    }
                }
            }

            $arr[time()] = request()->segment(count(request()->segments()));

            $count = count($arr);
            if (is_array($arr) && $count > 5) {
                //Sort the array so the keys come in right order
                ksort($arr);

                $arr = array_slice($arr, $count - 5, $count, true);
            }

            krsort($arr);
            $request->session()->put('recentInvestigations', $arr);
            session()->save();
        } else {
            return redirect('/home')->with(array('message' => 'Denne saken eksisterer ikke', 'status' => 'danger'));
        }

        return $next($request);
    }
}
