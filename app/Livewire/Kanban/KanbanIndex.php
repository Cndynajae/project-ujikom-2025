<?php
namespace App\Livewire\Kanban;

use App\Models\Priorities;
use App\Models\Project;
use App\Models\Statuses;
use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\TaskType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KanbanIndex extends Component
{
    public $editingTaskId = null;
    public $editedTask    = [];
    public $creatingTask  = false;
    public $newTask       = [];
    public $project;
    public $projectId;
    public $owners        = [];
    public $responsibles  = [];
    public $priorities    = [];
    public $types         = [];
    public $showTaskModal = false;
    public $selectedTask  = null;

    // filter properties
    public $selectedType        = '';
    public $selectedPriority    = '';
    public $selectedResponsible = '';

    public $showStatusModal   = false;
    public $availableStatuses = [];

    public $projectStatus;
    protected $listeners = [
        'refreshProject' => 'refreshProjectData',
        'updateTaskContentCreate',
        'updateTaskContentEdit',
        'initEditor',
    ];

    public function updateTaskContentCreate($content)
    {
        if (! is_array($this->newTask)) {
            $this->newTask = [];
        }
        $this->newTask['content'] = $content;
    }

    public function updateTaskContentEdit($content)
    {
        if (! is_array($this->editedTask)) {
            $this->editedTask = [];
        }
        $this->editedTask['content'] = $content;
    }

    public function filter()
    {
        // Implement filter functionality if needed
    }

    public function resetFilters()
    {
        $this->selectedType        = '';
        $this->selectedPriority    = '';
        $this->selectedResponsible = '';
    }

    public function destroy($tasksId)
    {
        $task = Tasks::find($tasksId);

        if ($task && $task->status->name === 'Archived') {
            $task->delete();
            session()->flash('message', 'Data Berhasil Dihapus.');
        } else {
            session()->flash('error', 'Tugas hanya dapat dihapus jika berada di status Archived.');
        }

    }

    public function editTask($taskId)
    {
        if (! Auth::user()->can('manageTasks-edit')) {
            session()->flash('error', 'You do not have permission to edit tasks.');
            return;
        }

        $task = Tasks::find($taskId);

        if ($task) {
            $this->editingTaskId = $taskId;
            $this->editedTask    = $task->toArray();
            $this->dispatch('setContent:editorEdit', $this->editedTask['content']);
            $this->dispatch('initEditor');

        }
    }

    public function saveTask()
    {
        // Validasi untuk tanggal
        $this->validate([
            'editedTask.start_date' => 'nullable|date',
            'editedTask.end_date'   => 'nullable|date|after_or_equal:editedTask.start_date',
        ]);

        // Pastikan tanggal start_date dan end_date adalah objek Carbon jika ada
        if (isset($this->editedTask['start_date'])) {
            $this->editedTask['start_date'] = Carbon::parse($this->editedTask['start_date']);
        }

        if (isset($this->editedTask['end_date'])) {
            $this->editedTask['end_date'] = Carbon::parse($this->editedTask['end_date']);
        }

        $task = Tasks::find($this->editingTaskId);

        if ($task) {
            $task->update($this->editedTask);
        }

        $this->resetEdit();
    }

    public function resetEdit()
    {
        $this->editingTaskId = null;
        $this->editedTask    = [];
    }

    public function openCreateTaskModal()
    {

        $this->creatingTask = true;
        $this->newTask      = [
            'name'           => '',
            'content'        => '',
            'owner_id'       => '',
            'responsible_id' => '',
            'status_id'      => '',
            'type_id'        => '',
            'priority_id'    => '',
            'code'           => '',
            'order'          => '',
            'estimation'     => '',
            'is_default'     => true,
            'start_date'     => null,
            'end_date'       => null,
        ];

        $defaultStatus = TaskStatus::where('is_default', true)->first();
        if ($defaultStatus) {
            $this->newTask['status_id'] = $defaultStatus->id;
        } else {
            session()->flash('error', 'Default status not found!');
        }

       // Dispatch event ke frontend
        $this->dispatch('setContent:editorCreate', $this->newTask['content']);

        $this->dispatch('initEditor');

        // \Log::info('openCreateTaskModal dipanggil');

    }

    public function resetCreateTask()
    {
        $this->creatingTask = false;
        $this->newTask      = [];
    }

    public function saveNewTask()
    {
        if (! Auth::user()->can('manageTasks-create')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk membuat tugas.');
            return;
        }

        $this->validate([
            'newTask.name'       => 'required|string|max:255',
            'newTask.start_date' => 'nullable|date',
            'newTask.end_date'   => 'nullable|date|after_or_equal:newTask.start_date',
        ]);

        // Pastikan newTask adalah array
        if (! is_array($this->newTask)) {
            $this->newTask = [];
        }

        // Pastikan tanggal start_date dan end_date adalah objek Carbon jika ada
        if (! empty($this->newTask['start_date'])) {
            $this->newTask['start_date'] = Carbon::parse($this->newTask['start_date']);
        }

        if (! empty($this->newTask['end_date'])) {
            $this->newTask['end_date'] = Carbon::parse($this->newTask['end_date']);
        }

        // Ambil status default jika belum diatur
        if (empty($this->newTask['status_id'])) {
            $defaultStatus = TaskStatus::where('is_default', true)->first();
            if ($defaultStatus) {
                $this->newTask['status_id'] = $defaultStatus->id;
            } else {
                session()->flash('error', 'Default status not found!');
                return;
            }
        }

        // Pastikan `newTask` tidak ada nilai null sebelum disimpan
        $cleanedTask = array_filter($this->newTask, fn($value) => ! is_null($value));

        // Simpan task baru
        Tasks::create(array_merge($cleanedTask, ['project_id' => $this->projectId]));

        // Reset modal setelah menyimpan
        $this->resetCreateTask();
    }

    public function updateTaskRealTime()
    {
        $task = Tasks::find($this->editingTaskId);

        if ($task) {
            $task->update($this->editedTask); // Updates the task with the new values
        }
    }

    public function closeModal()
    {
        $this->editingTaskId = null; // This will close the modal
    }

    public function updateTaskStatus($taskId, $newStatusId)
    {
        $task = Tasks::find($taskId);

        if ($task) {
            $task->status_id = $newStatusId;
            $task->save();
        }
    }

    public function refreshProjectData()
    {
        $this->project = Project::find($this->projectId); // Refresh the project instance
    }

    public function updateStatus()
    {
        if (! $this->projectStatus) {
            session()->flash('error', 'Silakan pilih status yang valid.');
            return;
        }

        $project = Project::find($this->projectId);

        if ($project) {
            $project->status_id = $this->projectStatus;
            $project->save();

            $this->showStatusModal = false;

            $this->refreshProjectData();

            session()->flash('message', "Status proyek berhasil diperbarui ke {$project->status->name}.");
        } else {
            session()->flash('error', 'Proyek tidak ditemukan.');
        }
    }

    public function openStatusModal()
    {
        $this->availableStatuses = Statuses::all();
        $this->projectStatus     = $this->project ? $this->project->status_id : null;
        $this->showStatusModal   = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
    }

    public function openTaskModal($taskId)
    {
        $this->selectedTask = Tasks::find($taskId);
        if ($this->selectedTask) {
            $this->selectedTask->start_date = Carbon::parse($this->selectedTask->start_date)->format('d M Y');
            $this->selectedTask->end_date   = Carbon::parse($this->selectedTask->end_date)->format('d M Y');
        }

        $this->showTaskModal = true;
    }

    public function closeTaskModal()
    {
        $this->showTaskModal = false;
        $this->selectedTask  = null;
    }

    public function mount($projectId)
    {
        $this->projectId     = $projectId;
        $this->project       = Project::find($projectId);
        $this->owners        = User::all() ?? collect();
        $this->responsibles  = User::all() ?? collect();
        $this->priorities    = Priorities::all() ?? collect();
        $this->types         = TaskType::all() ?? collect();
        $this->currentStatus = $this->project && $this->project->status ? $this->project->status->name : null;

        $this->availableStatuses = Statuses::all() ?? collect();

    }

    public function render()
    {
        $user = Auth::user();

        // Periksa apakah pengguna memiliki peran 'superadmin'
        $isSuperAdmin = $user && $user->hasRole('superadmin');

        // Ambil semua status, kecuali Archived jika pengguna bukan superadmin
        $statuses = TaskStatus::with(['tasks' => function ($query) {
            $query->where('project_id', $this->projectId);

            if ($this->selectedType) {
                $query->where('type_id', $this->selectedType);
            }
            if ($this->selectedPriority) {
                $query->where('priority_id', $this->selectedPriority);
            }
            if ($this->selectedResponsible) {
                $query->where('responsible_id', $this->selectedResponsible);
            }
        }])
            ->when(! $isSuperAdmin, function ($query) {
                // Sembunyikan status "Archived" jika bukan superadmin
                $query->where('name', '!=', 'Archived');
            })
            ->get();

        return view('livewire.kanban.kanban-index', [
            'statuses'          => $statuses,
            'owners'            => $this->owners,
            'responsibles'      => $this->responsibles,
            'priorities'        => $this->priorities,
            'types'             => $this->types,
            'availableStatuses' => $this->availableStatuses,
        ]);
    }

}
