<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public $states = [
        'KDH' => 'Kedah',
        'JHR' => 'Johor',
        'KTN' => 'Kelantan',
        'MLK' => 'Melaka',
        'NSN' => 'Negeri Sembilan',
        'PHG' => 'Pahang',
        'PRK' => 'Perak',
        'PLS' => 'Perlis',
        'PNG' => 'Pulau Pinang',
        'SBH' => 'Sabah',
        'SWK' => 'Sarawak',
        'SGR' => 'Selangor',
        'TRG' => 'Terengganu',
        'KUL' => 'W.P. Kuala Lumpur',
        'LBN' => 'W.P. Labuan',
        'PJY' => 'W.P. Putrajaya',
    ];

    public function index()
    {
        $customers = Customer::orderBy('name');

        if (request('search')) {
            $customers->where('name', 'like', '%' . request('search') . '%')
                ->orWhere('phone', 'like', '%' . request('search') . '%');
        }
        return view('customers.index', [
            'customers' => $customers->paginate(20),
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer,
            'states' => $this->states,
        ]);
    }

    public function create()
    {
        return view('customers.create', [
            'states' => $this->states,
        ]);
    }

    public function update(Customer $customer)
    {
        $attributes = request()->validate([
            'name' => 'required',
            'phone' => 'required|numeric|digits_between:9,12',
            'email' => 'email|nullable|max:100',
            'address' => 'nullable|required_with:city,postcode,state|max:255',
            'city' => 'nullable|required_with:address,city,state|max:50',
            'postcode' => 'nullable|required_with:address,city,state|min:5|max:5',
            'state' => 'nullable|required_with:address,city,postcode|min:3|max:3',
        ]);

        $customer->update($attributes);

        return back()->with('success', 'Maklumat pelanggan berjaya dikemaskini.');
    }

    public function insert()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'phone' => 'required|numeric|digits_between:9,12',
            'email' => 'email|nullable|max:100',
            'address' => 'nullable|required_with:city,postcode,state|max:255',
            'city' => 'nullable|required_with:address,city,state|max:50',
            'postcode' => 'nullable|required_with:address,city,state|min:5|max:5',
            'state' => 'nullable|required_with:address,city,postcode|min:3|max:3',
        ]);

        Customer::create($attributes);

        return redirect('/customers')->with('success', 'Pelanggan ditambah.');
    }
}
