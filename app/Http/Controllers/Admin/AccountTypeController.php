<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountType;
use App\Models\Admin;
use Exception;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $data = AccountType::get();

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                }
            }
            return view('admin.account_types.index', ['data' => $data]);
        }
        catch (Exception $e) {

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.account_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'related_internal_accounts' => 'required',
            'active' => 'required'
        ]);

        $inserted['name'] = $request->name;
        $inserted['related_internal_accounts'] = $request->related_internal_accounts;
        $inserted['active'] = $request->active;
        $inserted['added_by'] = auth()->user()->id;
        $inserted['created_at'] = date('Y-m-d H:i:s');

        AccountType::create($inserted);

        return redirect()->route('admin.account_types.index')->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
