<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentsAbsenceReport extends Component
{
    public $absenceData = [];
    public $subjects = [];
    public $selectedSubject;

    public function render()
    {
        $this->subjects = Subject::all();
        return view('livewire.students-absence-report', ['data' => [], 'subjects' => $this->subjects]);
    }

    public function generateReport()
    {
        $absenceData = DB::table('attendances')
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->join('lectures', 'lectures.id', '=', 'attendances.lecture_id')
            ->join('subjects', 'subjects.id', '=', 'lectures.subject_id')
            ->when($this->selectedSubject != null, function ($query) {
                return $query->where('subjects.id', $this->selectedSubject);
            })
            ->select(
                'users.name',
                'subjects.name as subject_name',
                DB::raw('SUM(CASE WHEN attendances.status = 1 THEN 1 ELSE 0 END) as total_absences')
            )
            ->groupBy('users.name', 'subjects.name')
            ->get();

        $this->absenceData = $absenceData;
    }
}
