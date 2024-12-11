<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:10',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required',
            'pdf' => 'required|mimes:pdf|max:2048',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pdfPath = $request->file('pdf')->store('public/uploads');
        $imagePath = $request->file('image')->store('public/uploads');

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pdf' => $pdfPath,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => 'Contact created successfully']);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:10',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'required',
            'pdf' => 'nullable|mimes:pdf|max:2048',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('pdf')) {
            $contact->pdf = $request->file('pdf')->store('public/uploads');
        }

        if ($request->hasFile('image')) {
            $contact->image = $request->file('image')->store('public/uploads');
        }

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pdf' => $contact->pdf,
            'image' => $contact->image,
        ]);

        return response()->json(['success' => 'Contact updated successfully']);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['success' => 'Contact deleted successfully']);
    }
}
