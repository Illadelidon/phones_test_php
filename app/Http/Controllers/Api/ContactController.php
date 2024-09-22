<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|min:2',
            'last_name' => 'required|string|max:255|min:2',
            'phone_number' => 'required|array',
            'phone_number.*' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $user = Contact::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ]);

        // Додавання телефонних номерів
        foreach ($request->input('phone_number') as $phoneNumber) {
            PhoneNumber::create([
                'contact_id' => $user->id,
                'phone_number' => $phoneNumber,
            ]);
        }

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function index(Request $request)
    {
        $contacts = Contact::with('phoneNumbers')
            ->paginate($request->input('per_page', 3));

        return response()->json($contacts);
    }
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully']);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|min:2',
            'last_name' => 'required|string|max:255|min:2',
            'phone_number' => 'required|array',
            'phone_number.*' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {

            $contact = Contact::findOrFail($id);
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->save();


            PhoneNumber::where('contact_id', $id)->delete();


            foreach ($request->phone_number as $phoneNumber) {
                PhoneNumber::create([
                    'contact_id' => $id,
                    'phone_number' => $phoneNumber,
                ]);
            }

            DB::commit();
            return response()->json($contact, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Не вдалося оновити контакт'], 500);
        }
    }
    public function checkPhone(Request $request)
    {
        $phoneExists = PhoneNumber::where('phone_number', $request->phone)->exists();
        return response()->json(['exists' => $phoneExists]);
    }




}
