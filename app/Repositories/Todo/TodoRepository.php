<?php

namespace App\Repositories\Todo;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Todo;
use App\Models\Shift;
use App\Models\TodoAttachment;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class TodoRepository
{
    use ImageUpload;

    public function getTodo()
    {
        try {
            $place = Todo::orderByDesc('created_at')->with(['user', 'todo_attachment'])->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Todo',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Todo' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getTodoUser($filter)
    {
        try {
            $query = Todo::where('id_user', Auth::user()->id)->where('status', 0)->with(['user', 'todo_attachment'])->orderByDesc('created_at');

            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'thisWeek':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'thisMonth':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'all':
                    // No additional filter needed
                    break;
                default:
                    return [
                        'status' => false,
                        'message' => 'Invalid filter option',
                        'data' => null,
                    ];
            }

            $place = $query->get();

            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Todo Staff',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Todo Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getTodoUserId($filter)
    {
        try {
            $todo = Todo::where('id', intval($filter['id']))->with(['user', 'todo_attachment'])->first();

            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Todo Staff',
                'data' => $todo,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Todo Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeTodo(array $data)
    {
        try {
            $tanggal = Carbon::parse($data['tanggal']);
            $place = Todo::create([
                'id_user' => $data['id_user'],
                'name' => $data['name'],
                'detail' => $data['detail'],
                'tanggal' => $data['tanggal']
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Membuat Todo',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Membuat Todo' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateTodo(array $data)
    {
        try {

            $place = Todo::where('id', $data['id'])->update([
                'id_user' => $data['id_user'],
                'name' => $data['name'],
                'detail' => $data['detail'],
                'tanggal' => Carbon::parse($data['tanggal'])->format('Y-m-d'),
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Todo',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Todo' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateTodoApprove(array $data)
    {
        try {
            $item = Todo::findOrFail($data['id']);

            $izin = Todo::where('id', $data['id'])->update([
                'status' => 1,
            ]);

            return [
                'status' => true,
                'message' => 'Berhasil Approve Todo',
                'data' => Todo::find($izin),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Approve Todo' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateTodoReject(array $data)
    {
        try {
            $izin = Todo::where('id', $data['id'])->update([
                'status' => 0,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Reject Todo',
                'data' => Todo::find($izin),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Reject Todo' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeTodoAttachment(array $data)
    {
        $file = $data['pict'] ? $this->uploadImage($data['pict'], 'todo_attachment') : null;
        try {
            $attachment = TodoAttachment::create([
                'pict' => $file,
                'detail' => $data['detail'],
            ]);
            $attachment->todo()->attach(intval($data['todo_id']));
            return [
                'status' => true,
                'message' => 'Success Add Attachment to Task',
                'data' => $attachment,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function removeTodoAttachment(array $data)
    {
        try {
            // Find the attachment by its ID
            $attachment = TodoAttachment::find($data['attachment_id']);

            if (!$attachment) {
                return [
                    'status' => false,
                    'message' => 'Attachment not found',
                    'data' => null,
                ];
            }

            $filePath = $attachment->pict;
            $proofOfOvertime = $this->deleteImage($filePath);

            $attachment->todo()->detach();

            $attachment->delete();

            return [
                'status' => true,
                'message' => 'Attachment deleted successfully',
                'data' => null,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteTodo(array $data)
    {
        try {
            $todo = Todo::where('id', $data['id'])->with('todo_attachment')->first();
            foreach ($todo->todo_attachment as $attachment) {
                $attachment = TodoAttachment::find($attachment->id);

                $filePath = $attachment->pict;
                $deleteFile = $this->deleteImage($filePath);

                $attachment->todo()->detach();

                $attachment->delete();
            }

            $todo->delete();
            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Todo',
                'data' => $todo,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Todo - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
