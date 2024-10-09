<?php

namespace App\Livewire;

use App\Exports\ExcelExport;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TeacherAbsenceReport extends Component
{
    public $absenceData = [];
    public $subjects = [];
    public $selectedSubject;

    public function render()
    {
        $this->subjects = Subject::all();

        return view('livewire.teacher-absence-report');
    }

    public function generateReport()
    {
        $absenceData = DB::table('teacher_absences')
            ->join('teachers', 'teachers.id', '=', 'teacher_absences.teacher_id')
            ->join('lectures', 'lectures.id', '=', 'teacher_absences.lecture_id')
            ->join('subjects', 'subjects.id', '=', 'lectures.subject_id')
            ->when($this->selectedSubject != null, function ($query) {
                return $query->where('subjects.id', $this->selectedSubject);
            })
            ->select(
                'teachers.name',
                'subjects.name as subject_name',
                DB::raw('SUM(CASE WHEN teacher_absences.status = 1 THEN 1 ELSE 0 END) as total_absences')
            )
            ->groupBy('teachers.name', 'subjects.name')
            ->get();

        $this->absenceData = $absenceData;
    }

    public function exportToExcel()
    {
        if ($this->absenceData) {
            return Excel::download(new ExcelExport($this->absenceData, ['الاستاذ', 'المادة', 'الغياب']), 'absence_report.xlsx');
        }
    }
}
