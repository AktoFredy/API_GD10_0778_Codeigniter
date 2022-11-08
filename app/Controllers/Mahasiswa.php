<?php

namespace App\Controllers;

use App\Models\Modelmahasiswa;
use CodeIgniter\Model;
use CodeIgniter\RESTful\ResourceController;

class Mahasiswa extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelMhs = new Modelmahasiswa();
        $data = $modelMhs->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];

        return $this->respond($response, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($cari = null)
    {
        $modelMhs = new Modelmahasiswa();

        $data = $modelMhs->orLike('mhsnobp', $cari)
            ->orLike('mhsnama', $cari)->get()->getResult();

            if(count($data) > 1){
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data,
                ];

                return $this->respond($response, 200);
            }else if(count($data) == 1){
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data,
                ];

                return $this->respond($response, 200);
            }else{
                return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
            }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $modelMhs = new Modelmahasiswa();
        $nobp = $this->request->getPost("mhsnobp");
        $nama = $this->request->getPost("mhsnama");
        $alamat = $this->request->getPost("mhsalamat");
        $prodi = $this->request->getPost("prodinama");
        $tgllahir = $this->request->getPost("mhstgllhr");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'mhsnobp' => [
                'rules' => 'is_unique[mahasiswa.mhsnobp]',
                'label' => 'Nomor Induk Mahasiswa',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("mhsnobp"),
            ];

            return $this->respond($response, 404);
        }else{
            $modelMhs->insert([
                'mhsnobp' => $nobp,
                'mhsnama' => $nama,
                'mhsalamat' => $alamat,
                'prodinama' => $prodi,
                'mhstgllhr' => $tgllahir,
            ]);

            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];

            return $this->respond($response, 201);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($nobp = null)
    {
        $model = new Modelmahasiswa();

        $data = [
            'mhsnama' => $this->request->getVar("mhsnama"),
            'mhsalamat' => $this->request->getVar("mhsalamat"),
            'prodinama' => $this->request->getVar("prodinama"),
            'mhstgllahit' => $this->request->getVar("mhstgllhr"),
        ];
        
        $data = $this->request->getRawInput();
        $model->update($nobp, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data anda dengan NIM $nobp berhasil dibaharukan"
        ];
        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($nobp = null)
    {
        $modelMhs = new Modelmahasiswa();

        $cekData = $modelMhs->find($nobp);
        if($cekData){
            $modelMhs->delete($nobp);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
