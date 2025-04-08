<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bucket;

class BucketController extends Controller
{
    public function index() {
        return Bucket::all();
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        return Bucket::create(['name' => $request->name]);
    }

    public function update(Request $request, $id) {
        $request->validate(['name' => 'required']);
        $bucket = Bucket::findOrFail($id);
        $bucket->update(['name' => $request->name]);
        return response()->json(['message' => 'Bucket updated successfully']);
    }

    public function destroy($id) {
        $bucket = Bucket::findOrFail($id);
        $bucket->delete();
        return response()->json(['message' => 'Bucket deleted successfully']);
    }
}
