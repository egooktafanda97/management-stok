<?php

namespace App\Contract;

interface BaseRepositoryInterface
{

    /**
     * Mengembalikan atribut yang bisa diisi dari model.
     * 
     * @return array
     */
    public function fillable();

    /**
     * Mengembalikan aturan validasi untuk model.
     * 
     * @return array
     */
    public function rule();

    /**
     * Mengambil data yang sudah divalidasi.
     * 
     * @return array
     */
    public function getValidated();

    /**
     * Melakukan validasi data berdasarkan aturan yang ditentukan dalam model.
     * 
     * @param array $data Data yang akan divalidasi
     * @return self
     * @throws \Exception Jika validasi gagal
     * *->validate([data] || null)
     */
    public function validate(array $data = []);

    /**
     * Mencari entitas berdasarkan kolom dan nilai tertentu.
     * 
     * @param string $field Kolom yang akan dicari
     * @param mixed $value Nilai dari kolom yang dicari
     * @return mixed
     * *->findBy("key","value")
     */
    public function findBy($field, $value);

    /**
     * Membuat entitas baru dengan data yang diberikan.
     * 
     * @param array $data Data untuk membuat entitas
     * @param callable|null $next Callback opsional yang dijalankan setelah entitas dibuat
     * @return mixed
     * @throws \Exception Jika terjadi kesalahan saat membuat entitas
     * *->create([data] | null, function($created){})
     */
    public function create(array $data = [], $next = null);

    /**
     * Menyimpan entitas yang sudah ada atau membuat entitas baru jika belum ada ID.
     * 
     * @param array $data Data untuk menyimpan atau membuat entitas
     * @return mixed
     * @throws \Exception Jika terjadi kesalahan saat menyimpan entitas
     */
    public function save($data = []);

    /**
     * Memperbarui entitas yang ada dengan data yang diberikan.
     * 
     * @param string|int $id ID dari entitas yang akan diperbarui
     * @param array $data Data untuk memperbarui entitas
     * @return mixed
     * @throws \Exception Jika terjadi kesalahan saat memperbarui entitas
     */
    public function update($id, array $data);

    /**
     * Menghapus entitas berdasarkan ID.
     * 
     * @param string|int|null $id ID dari entitas yang akan dihapus
     * @return mixed
     * @throws \Exception Jika terjadi kesalahan saat menghapus entitas
     */
    public function delete($id = null);

    /**
     * Mengambil semua entitas.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Mencari entitas berdasarkan ID.
     * 
     * @param string|int $id ID dari entitas yang akan dicari
     * @return mixed
     */
    public function find($id);

    /**
     * Mengambil entitas yang sesuai dengan kondisi tertentu.
     * 
     * @param callable $obj Callback untuk menentukan kondisi
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWhere($obj);

    /**
     * Mencari entitas pertama yang sesuai dengan kondisi tertentu.
     * 
     * @param callable $obj Callback untuk menentukan kondisi
     * @return mixed
     */
    public function findWhere($obj);

    /**
     * Mencari entitas pertama yang sesuai dengan kondisi tertentu dan memuat relasi terkait.
     * 
     * @param callable $where Callback untuk menentukan kondisi
     * @param array $with Relasi yang akan dimuat
     * @return mixed
     */
    public function findWhereWith($where, $with = []);

    /**
     * Mengatur ID dari entitas yang sedang dioperasikan.
     * 
     * @param string|int $id ID dari entitas
     * @return self
     */
    public function setId($id);

    /**
     * Mengambil ID dari entitas yang sedang dioperasikan.
     * 
     * @return string|int|null
     */
    public function getId();

    /**
     * Mengatur data yang akan digunakan dalam operasi repository.
     * 
     * @param array $data Data untuk diatur
     * @return self
     */
    public function setData($data);

    /**
     * Mengambil data yang sedang digunakan dalam operasi repository.
     * 
     * @return array
     */
    // public function getData();

    /**
     * Mengatur dan memperbarui data entitas berdasarkan ID.
     * 
     * @param string|int $id ID dari entitas
     * @param array $data Data untuk memperbarui entitas
     * @return void
     */
    // public function set($id, array $data);

    /**
     * Mendapatkan daftar entitas dengan paginasi.
     * 
     * @param callable $callback Callback untuk menentukan kondisi pencarian
     * @param int $paginate Jumlah item per halaman
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginate($callback, $paginate = 10);

    /**
     * Mencari entitas berdasarkan kondisi tertentu dengan batasan jumlah hasil.
     * 
     * @param callable $callback Callback untuk menentukan kondisi pencarian
     * @param int $limit Batasan jumlah hasil yang dikembalikan
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($callback, $limit = 10);

    // public function transformer(array $data);
}
