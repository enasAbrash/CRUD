<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts=Contact::all();
        return response()->json([
            'status'=>'success',
            'contacts'=>$contacts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'phone'=>'required',
            'email'=>'required|email'
        ]);

        try{
            DB::beginTransaction();
            $contact=Contact::create([
                'name'=>$request->name,
                'phone'=>$request->phone,
                'email'=>$request->email
            ]);
            DB::Commit();
        }
        catch(\Throwable $th)
        {
            DB::rolleback();
            LOg::error($th);
            return response()->json(['status'=>'error'],500);
        }
         
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return response()->json([
            'status'=>'success',
            'contact'=>$contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name'=>'nullable|string|max:255',
            'phone'=>'nullable',
            'email'=>'nullable|email'
        ]);
        $newData=[];
        if(isset($request->name))
        {
            $newData['name']=$request->name;
        }
        if(isset($request->phone))
        {
            $newData['phone']=$request->phone;
        }
        if(isset($request->email))
        {
            $newData['email']=$request->email;
        }
        $contact->update($newData);
        return response()->json([
            'status'=>'success',
            'contact'=>$contact,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json([
            'status'=>'success'
        ]);
    }
}
