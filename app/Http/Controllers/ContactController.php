<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    // get contacts by users id
    public function getContact(Request $request)
    {
        try {
            $id = $request->user()->id;
            $contacts = Contact::where('user_id', $id)->orderBy('id', 'DESC')->with('users')->get();
            return response()->json([
                'success' => true,
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // create contacts
    public function store(Request $request)
    {
        try {
            // contact instance
            $contacts = new Contact();
            // add validation
            $validator = Validator::make($request->all(), [
                'name' => 'required | string | max:25 | min:7',
                'email' => 'required | string | email | max:30 | min:10 |unique:contacts',
                'country' => 'required | string | max:10 | min:2',
                'number' => 'required | numeric |  min:8 | unique:contacts',
            ]);

            // check validator is fail or fass
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                // get all value
                $contacts->user_id = $request->user()->id;
                $contacts->name = $request->name;
                $contacts->email = $request->email;
                $contacts->country = $request->country;
                $contacts->number = $request->number;
                // save contacts
                $result = $contacts->save();
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Contact add successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Some problem'
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // edit contacts

    public function edit($id)
    {
        try {
            $contacts = Contact::findOrFail($id);
            return response()->json([
                'success' => true,
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // update contacts 

    public function update(Request $request, $id)
    {
        try {
            // contact instance
            $contacts = Contact::findOrFail($id);
            // add validation
            $validator = Validator::make($request->all(), [
                'name' => 'required | string | max:25 | min:7',
                'email' => 'required | string | email | max:30 | min:10',
                'country' => 'required | string | max:10 | min:2',
                'number' => 'required | numeric |  min:8',
            ]);

            // check validator is fail or fass
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                // get all value
                $contacts->name = $request->name;
                $contacts->email = $request->email;
                $contacts->country = $request->country;
                $contacts->number = $request->number;
                // save contacts
                $result = $contacts->save();
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Contact Update successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Some problem'
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $contacts = Contact::findOrFail($id)->delete();
            if ($contacts) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contact Delete successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Some problem'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // search contacts
    public function search(Request $request, $search)
    {
        try {
            $search_item = "%" . $search . "%";
            $id = $request->user()->id;
            $contacts = Contact::where('name', 'LIKE', $search_item)->orderBy('id', 'DESC')->get();
            return response()->json([
                'success' => true,
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
