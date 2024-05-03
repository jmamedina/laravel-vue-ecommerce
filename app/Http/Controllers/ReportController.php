<?php
/** 
* Controller for generating reports
* レポートを生成するためのコントローラー
**/

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ReportTrait;

    // Retrieve data for orders report
    // 注文レポートのデータを取得する
    public function orders()
    {
        // Get orders query
        // 注文のクエリを取得する
        $query = Order::query();

        // Prepare data for bar chart
        // 棒グラフ用のデータを準備する
        return $this->prepareDataForBarChart($query, 'Orders By Day');
    }

    // Retrieve data for customers report
    // 顧客レポートのデータを取得する
    public function customers()
    {
        // Get customers query
        // 顧客のクエリを取得する
        $query = Customer::query();

        // Prepare data for bar chart
        // 棒グラフ用のデータを準備する
        return $this->prepareDataForBarChart($query, 'Customers By Day');
    }

    // Prepare data for bar chart
    // 棒グラフ用のデータを準備する
    private function prepareDataForBarChart($query, $label)
    {
        // Get start date for data retrieval
        // データ取得のための開始日を取得する
        $fromDate = $this->getFromDate() ?: Carbon::now()->subDay(30);
        
        // Select and group data by date
        // 日付でデータを選択し、グループ化する
        $query
            ->select([DB::raw('CAST(created_at as DATE) AS day'), DB::raw('COUNT(created_at) AS count')])
            ->groupBy(DB::raw('CAST(created_at as DATE)'));
        
        // Filter data by start date
        // 開始日でデータをフィルタリングする
        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }

        // Retrieve records
        // レコードを取得する
        $records = $query->get()->keyBy('day');

        // Process data for chartjs
        // chartjs用にデータを処理する
        $days = [];
        $labels = [];
        $now = Carbon::now();
        while ($fromDate < $now) {
            $key = $fromDate->format('Y-m-d');
            $labels[] = $key;
            $fromDate = $fromDate->addDay(1);
            $days[] = isset($records[$key]) ? $records[$key]['count'] : 0;
        }

        // Return data for bar chart
        // 棒グラフ用のデータを返す
        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'backgroundColor' => '#f87979',
                'data' => $days
            ]]
        ];
    }
}