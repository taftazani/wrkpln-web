<?php

namespace App\Repositories\MasterUser;

use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Jadwal;
use App\Models\Kasbon;
use App\Models\Kpi;
use App\Models\Lembur;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Models\Shift;
use App\Models\Todo;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;
use Illuminate\Support\Facades\Hash;

class MasterUserRepository
{
    use ImageUpload;

    public function getUser()
    {
        try {
            $user = User::orderByDesc('created_at')->with('roles', 'salaries', 'area')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Staff',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Staff' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getRole()
    {
        try {
            $user = Role::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Role',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Role' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeUserAdmin(array $data)
    {
        try {
            $password = Hash::make('staff123');

            $profile_image = $this->uploadImage($data['profile_image'], 'user');

            $user = User::create([
                'name' =>  $data['name'],
                'email' =>  $data['email'],
                'profile_image' =>  $profile_image,
                'tgl_lahir' => Carbon::parse($data['tgl_lahir']),
                'tgl_masuk' =>  Carbon::parse($data['tgl_masuk']),
                'phone' =>  $data['phone'],
                'salary' =>  $data['salary'],
                'norek' =>  $data['norek'],
                'bank_name' =>  $data['bank_name'],
                'password' =>  $password,
            ]);
            $user->roles()->attach(intval($data['role']));

            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan User',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan User ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeUser(array $data)
    {
        try {
            $password = Hash::make($data['password']);
            if (!$data['password']) {
                $password = Hash::make('staff123');
            }
            $profile_image = $this->uploadImage($data['profile_image'], 'user');

            $user = User::create([
                'name' =>  $data['name'],
                'email' =>  $data['email'],
                'profile_image' =>  $profile_image,
                'tgl_lahir' => Carbon::parse($data['tgl_lahir']),
                'tgl_masuk' =>  date('Y-m-d'),
                'phone' =>  $data['phone'],
                'salary' =>  '0',
                'norek' =>  $data['norek'],
                'bank_name' =>  $data['bank_name'],
                'password' =>  $password,
            ]);
            if (!$data['password']) {
                $user->roles()->attach(intval($data['role']));
            } else {
                $user->roles()->attach(2);
            }
            // Attach roles to the user
            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan User',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan User ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeUserArea(array $data)
    {
        try {
            $user = User::findOrFail($data['user_id']);
            $user->area()->sync(json_decode($data['area_id'], true)); // sync will add and remove as necessary

            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan Area',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan Area ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateUserArea(array $data)
    {
        try {
            $user = User::findOrFail($data['user_id']);
            $user->area()->sync(json_decode($data['area_id'], true)); // sync will add and remove as necessary

            return [
                'status' => true,
                'message' => 'Berhasil Mengupdate Area',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengupdate Area ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function removeUserArea(array $data)
    {
        try {
            $user = User::findOrFail($data['user_id']);
            foreach ($data['area_id'] as $area) {
                $user->area()->detach(intval($area));
            }

            // Attach roles to the user
            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Area',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Area ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function adminUpdateUser(array $data)
    {
        try {
            if (empty($data['profile_image'])) {
                $user = User::where('id', $data['id'])->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'tgl_lahir' => $data['tgl_lahir'],
                    'tgl_masuk' => $data['tgl_masuk'],
                    'phone' => $data['phone'],
                    'salary' => $data['salary'],
                    'norek' => $data['norek'],
                    'bank_name' => $data['bank_name']
                ]);
                return [
                    'status' => true,
                    'message' => 'Berhasil Mengubah Detail User',
                    'data' => $user,
                ];
            }
            $profile_image = $this->uploadImage($data['profile_image'], 'user');
            $user = User::where('id', $data['id'])->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'profile_image' => $profile_image,
                'tgl_lahir' => $data['tgl_lahir'],
                'tgl_masuk' => $data['tgl_masuk'],
                'phone' => $data['phone'],
                'salary' => $data['salary'],
                'norek' => $data['norek'],
                'bank_name' => $data['bank_name']
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail User',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail User' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateUser(array $data)
    {
        try {

            if (empty($data['profile_image'])) {
                $user = User::where('id', $data['id'])->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'norek' => $data['norek'],
                    'bank_name' => $data['bank_name']
                ]);
                return [
                    'status' => true,
                    'message' => 'Berhasil Mengubah Detail User',
                    'data' => $user,
                ];
            }

            $profile_image = $this->uploadImage($data['profile_image'], 'user');
            $user = User::where('id', $data['id'])->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'profile_image' => $profile_image,
                'phone' => $data['phone'],
                'norek' => $data['norek'],
                'bank_name' => $data['bank_name']
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail User',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail User' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function updateUserPassword(array $data)
    {
        try {
            $user = User::where('id', $data['id'])->update([
                'password' => Hash::make($data['password']),
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Password User',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Password User' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteUser(array $data)
    {
        try {
            $user = User::findOrFail($data['id']);
            $cekDataAbsen = Absensi::where('id_user', $user->id)->delete();
            $cekDataJadwal = Jadwal::where('id_user', $user->id)->delete();
            $cekDataKpi = Kpi::where('user_id', $user->id)->delete();
            $cekDataPayment = Payment::where('id_user', $user->id)->delete();
            $cekDataIzin = Izin::where('id_user', $user->id)->delete();
            $cekDataKasbon = Kasbon::where('id_user', $user->id)->delete();
            $cekDataTask = Todo::where('id_user', $user->id)->delete();
            $user->roles()->detach();

            $user->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail User',
                'data' => $user,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail User - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
