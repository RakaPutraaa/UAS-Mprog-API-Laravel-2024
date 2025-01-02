<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetailListVillaResource;
use App\Http\Resources\ListVillaResource;
use App\Models\ListVilla;
use App\Models\LokasiVilla;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListVillaController extends Controller
{
    // create
    public function store(Request $request) {
        try {
            // validasi untuk inputan
            $validated = $request->validate([
                'nama_villa' => 'required',
                'deskripsi_villa' => 'required',
                'harga_villa' => 'required',
                'lokasi_villa' => 'required',
                'foto_villa' => 'required|mimes:jpeg, jpg, png|max:1000',
                'id_kabupaten' => 'required|exists:kabupaten,id' //cek table kabupaten di column id
            ]);

            // pengambilan gambar
            $filepath = null;
            $fileName='';
            $extension='';
            if($request->foto_villa){
                // mengubah nama image agar unique
                $fileName = $this->generateRandomString().'.'.$request->foto_villa->extension();
                // menaruh foto di folder image
                $filepath = $request->foto_villa->storeAs('image', $fileName, 'public');
            }

            // mengambil semua value di request kecuali foto_villa
            $data = $request->except(['foto_villa']);
            // value foto_villa diambil
            $data['foto_villa'] = $filepath;

            // memasukkan list villa
            $villa = ListVilla::create($data);

            return response()->json([
                'message' => 'success',
                'status' => 201,
                'villa' => new ListVillaResource($villa)
            ], 201);

        }catch(QueryException $e) {
            return response()->json([
                'message' => 'failed',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // show detail
    public function showDetail($id){
        try{
            // mendapatkan list villa sesuai id
            $villa = ListVilla::with('lokasi:id,daerah')->findOrFail($id); //mendapatkan nama daerah sesuai id
            return response()->json([
                'message'=>'success',
                'status'=>200,
                'villa'=> new DetailListVillaResource($villa) //menampilkan detail villa
            ],200);
        }catch(QueryException $e){
            return response()->json([
                'message' => 'failed',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // show all villa
    public function index() {
        try{
            // mendapatkan list semua villa
            $villa = ListVilla::all();
            return response()->json([
                'message' => 'success',
                'status' => 200,
                'villa' => ListVillaResource::collection($villa) //menampilkan semua villa
            ],200);
        }catch(QueryException $e) {
            return response()->json([
                'message' => 'failed',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // update
    public function update(Request $request, $id) {
        try{
            // dd($request->all());

            // dapatkan id user logged-in
            $idUser = Auth::id();

            // mendapatkan villa sesuai id
            $villa = ListVilla::findOrFail($id);

            // cek apakah ini pemilik villa
            if($idUser != $villa->id_user){
                return response()->json([
                    'message' => 'failed',
                    'status' => 403,
                ], 403);
            }

            // melakukan validasi terhadap input
            $validated = $request->validate([
                'nama_villa' => 'nullable',
                'deskripsi_villa' => 'nullable',
                'harga_villa' => 'nullable',
                'lokasi_villa' => 'nullable',
                'foto_villa' => 'nullable|mimes:jpeg, jpg, png|max:1000',
                'id_kabupaten' => 'nullable|exists:kabupaten,id' //cek table kabupaten di column id
            ]);

            // menyimpan foto baru jika ada
            $filePath = $villa->foto_villa; //default old image

            // cek apakah ada image di form
            if($request->hasFile('foto_villa')) {

                // menghapus foto lama
                if(Storage::exists('public/image' . $villa->foto_villa)){
                    Storage::delete('public/image' . $villa->foto_villa);
                }

                // menyimpan foto baru
                // pengambilan gambar
                $filepath = null;
                $fileName='';
                $extension='';

                // mengubah nama image agar unique
                $fileName = $this->generateRandomString().'.'.$request->foto_villa->extension();
                // menaruh foto di folder image
                $filePath = $request->foto_villa->storeAs('image', $fileName, 'public');

            }

            $villa->update(array_merge($validated, ['foto_villa' => $filePath]));

            return response()->json([
                'message' => 'success',
                'status' => 200,
                'villa' => new DetailListVillaResource($villa)
            ], 200);

        }catch(QueryException $e) {
            return response()->json([
                'message' => 'failed',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // delete
    public function destroy($id) {
        try {

            // dapatkan id user logged-in
            $idUser = Auth::id();

            // mendapatkan villa sesuai id
            $villa = ListVilla::findOrFail($id);

            // cek id user logged-in
            if ($idUser != $villa->id_user) {
                return response()->json([
                    'message' => 'failed',
                    'status' => 500,
                ], 500);
            }else {
                // jalankan delete jika hasil id sama
                $villa->delete(); //melakukan delete
                return response()->json([
                    'message' => 'success',
                    'status' => 200,
                    'villa' => new DetailListVillaResource($villa)
                ],200);
            };
        }catch(QueryException $e) {
            return response()->json([
                'message' => 'failed',
                'status' => 403,
                'error' => $e->getMessage()
            ], 403);
        }
    }

    // random string
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
