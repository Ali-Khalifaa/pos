<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $products_count=Product::count();
        $categories_count=Category::count();
        $clients_count=Client::count();
        $users_count=User::whereRoleIs('admin')->count();


        $sales_data = Order::select(
            Db::raw('YEAR (created_at) as year'),
            Db::raw('MONTH (created_at) as month'),
            Db::raw('SUM(total_price) as sum'),
        )->groupBy('month')->get();


        return view('dashboard.index',compact('products_count','categories_count','clients_count','users_count','sales_data'));
        
    }//end of index
    
}//End of controller
