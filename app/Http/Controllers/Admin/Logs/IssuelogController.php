<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Helper\Generalfnv;
use App\Http\Controllers\Controller;
use App\Shop\Customers\Customer;
use App\Shop\Logs\IssuelogAdmin;
use App\Shop\Orders\Order;
use Illuminate\Http\Request;

class IssuelogController extends Controller
{

    /**
     * BrandController constructor.
     *
     * @list admin issue log
     */

    public function index(Request $request)
    {

        /*
         * check permission
         */
        $is_allow = Generalfnv::check_permission('view-logs');

        if (isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
        // end permission

        $orderBy = 'id desc';

        $issuelogAdmins = IssuelogAdmin::where(function ($where) use ($request) {
            $where->where('OrderNumber', '!=', 0);
            if (!empty($request->fr) && $request->fr) {
                $where->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->fr)));
            }

            if (!empty($request->to) && $request->to) {
                $where->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to)));
            }
            if (!empty($request->com) && $request->com != '') {
                $search = '%' . $request->com . '%';
                $where->where('CompanyName_search', 'LIKE', $search);
            }

        })
            ->orderByRaw($orderBy)
            ->paginate(10);

        return view('admin.logs.issuelog_admin_list', compact('issuelogAdmins'));

    }

    public function create()
    {

        $companyNames = Customer::leftJoin('addresses as ab', function ($join) {
            //$join->on('customers.id','=','ab.customers_id');
            $join->on('customers.default_address_id', '=', 'ab.id');
        })
            ->selectRaw('customers.id as customers_id, ab.company_name')
            ->orderBy('ab.company_name', 'desc')
            ->get();

        //echo "CK<pre>"; print_r($companyName); echo "</pre>CK"; exit;

        return view('admin.logs.issuelog_admin_create', compact('companyNames'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'CompanyName'   => 'required',
            'NatureOfIssue' => 'required',
            'date1'         => 'required|max:180',
            'LossType'      => 'required|max:180',
        ],
            [
                'date1.required'         => 'The date field is required.',
                'CompanyName.required'   => 'The Company Name field is required.',
                'NatureOfIssue.required' => 'The Nature Of Issue field is required.',
                'LossType.required'      => 'The Loss Type field is required.',

            ]);
        
        if($request->OrderNumber && !empty($request->OrderNumber)){
            $checkOrder = Order::whereId('$request->OrderNumber')->count();
            if($checkOrder<1){
                return back()->with('error', 'Sorry! Entered order number is not exists. Please check order number and try again.')->withInput();
            }
        }
        $getIdData = Customer::find($request->CompanyName)->defaultaddress;

        if (isset($getIdData->id)) {$CompanyName_search = $getIdData->company_name;} else { $CompanyName_search = '';}

        $ClientUser                       = new IssuelogAdmin;
        $ClientUser->NatureOfIssue        = $request->NatureOfIssue;
        $ClientUser->Responsibility       = $request->Responsibility;
        $ClientUser->Details              = $request->Details;
        $ClientUser->Resolution           = $request->Resolution;
        $ClientUser->FinancialImplication = $request->FinancialImplication;
        $ClientUser->LossType             = $request->LossType;
        $ClientUser->CompanyName          = $request->CompanyName;
        $ClientUser->CompanyContact       = $request->CompanyContact;
        $ClientUser->AdminClerk           = $request->AdminClerk;
        $ClientUser->date1                = date("Y-m-d", strtotime($request->date1));
        $ClientUser->OrderNumber          = $request->OrderNumber;
        $ClientUser->CompanyName_search   = $CompanyName_search;
        $ClientUser->save();

        return redirect()->route('admin.issuelog.index')->with('success', 'Issue log has been created successful!');
    }

    public function edit($id)
    {
        $issuelogAdmin = IssuelogAdmin::find($id);
        $companyNames  = Customer::leftJoin('addresses as ab', function ($join) {
            //$join->on('customers.id','=','ab.customers_id');
            $join->on('customers.default_address_id', '=', 'ab.id');
        })
            ->selectRaw('customers.id as customers_id, ab.company_name')
            ->orderBy('ab.company_name', 'desc')
            ->get();

        return view('admin.logs.issuelog_admin_edit', compact('issuelogAdmin', 'companyNames'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {

        $request->validate([
            'CompanyName'   => 'required',
            'NatureOfIssue' => 'required',
            'date1'         => 'required|max:180',
            'LossType'      => 'required|max:180',
        ],
            [
                'date1.required'         => 'The date field is required.',
                'CompanyName.required'   => 'The Company Name field is required.',
                'NatureOfIssue.required' => 'The Nature Of Issue field is required.',
                'LossType.required'      => 'The Loss Type field is required.',

            ]);

        $getIdData = Customer::find($request->CompanyName)->defaultaddress;

        if (isset($getIdData->id)) {$CompanyName_search = $getIdData->company_name;} else { $CompanyName_search = '';}

        $ClientUser                       = IssuelogAdmin::find($id);
        $ClientUser->NatureOfIssue        = $request->NatureOfIssue;
        $ClientUser->Responsibility       = $request->Responsibility;
        $ClientUser->Details              = $request->Details;
        $ClientUser->Resolution           = $request->Resolution;
        $ClientUser->FinancialImplication = $request->FinancialImplication;
        $ClientUser->LossType             = $request->LossType;
        $ClientUser->CompanyName          = $request->CompanyName;
        $ClientUser->CompanyContact       = $request->CompanyContact;
        $ClientUser->AdminClerk           = $request->AdminClerk;
        $ClientUser->date1                = date("Y-m-d", strtotime($request->date1));
        $ClientUser->OrderNumber          = $request->OrderNumber;
        $ClientUser->CompanyName_search   = $CompanyName_search;
        $ClientUser->save();

        return redirect()->route('admin.issuelog.index')->with('success', 'Issue log has been created successful!');
    }

    public function show($id)
    {

        $issuelogAdmin = IssuelogAdmin::find($id);
        $companyNames  = Customer::leftJoin('addresses as ab', function ($join) {
            //$join->on('customers.id','=','ab.customers_id');
            $join->on('customers.default_address_id', '=', 'ab.id');
        })
            ->selectRaw('customers.id as customers_id, ab.company_name')
            ->orderBy('ab.company_name', 'desc')
            ->get();

        return view('admin.logs.issuelog_admin_view', compact('issuelogAdmin', 'companyNames'));
    }

    public function destroy($id)
    {
        $issuelogAdmin = IssuelogAdmin::find($id);
        $issuelogAdmin->delete();
        return redirect()->route('admin.issuelog.index')->with('success', 'Issue log has been deleted successful!');
    }

    public function printLog(Request $request)
    {
        if (!empty($request->printdate)) {
            $issuelogAdmins = IssuelogAdmin::where('date1', date('Y-m-d', strtotime($request->printdate)))->get();
            return view('admin.logs.issuelog_admin_print', compact('issuelogAdmins'));
        } else {
            return redirect()->route('admin.issuelog.index')->with('error', 'Issue log date can not be null!');
        }
    }

}
