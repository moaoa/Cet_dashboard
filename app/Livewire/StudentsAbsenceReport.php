<?php

namespace App\Livewire;

use App\Enums\AttendanceStatus;
use App\Enums\Major;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Exports\ExcelExport;
use App\Models\Group;
use App\Models\Semester;
use Maatwebsite\Excel\Facades\Excel;


class StudentsAbsenceReport extends Component
{
    public $selectedSubject;
    public $selectedMajor;
    public $selectedSemester;
    public $selectedGroup;

    public $absenceData = [];
    public $subjects = [];
    public $semesters = [];
    public $groups = [];

    public function render()
    {
        return view('livewire.students-absence-report');
    }

    public function generateReport()
    {

        $absenceData = DB::table('attendances')
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->join('lectures', 'lectures.id', '=', 'attendances.lecture_id')
            ->join('subjects', 'subjects.id', '=', 'lectures.subject_id')
            ->join('group_user', 'group_user.user_id', '=', 'users.id')
            ->join('groups', 'groups.id', '=', 'group_user.group_id')
            ->where(function ($query) {
                if ($this->selectedSubject !== null) {
                    $query->where('subjects.id', $this->selectedSubject);
                }
            })
            ->when($this->selectedGroup !== null, function ($query) {
                return $query->where('group_user.group_id', $this->selectedGroup);
            })
            ->select(
                'users.name',
                'users.ref_number',
                'groups.name as group_name',
                'subjects.name as subject_name',
                DB::raw('SUM(CASE WHEN attendances.status ='  . AttendanceStatus::Absent->value . ' THEN 1 ELSE 0 END) as total_absences'),
            )
            ->groupBy('users.name', 'subjects.name', 'users.ref_number', 'group_name')
            ->get();

        $this->absenceData = $absenceData;
    }

    public function exportToExcel()
    {
        if ($this->absenceData) {
            return Excel::download(
                new ExcelExport(
                    $this->absenceData,
                    ['الطالب', 'رقم القيد', 'المادة', 'الغياب']
                ),
                'absence_report.xlsx'
            );
        }
    }

    public function getMajorOptions()
    {
        $options = [];

        collect(Major::cases())
            ->each(function ($major) use (&$options) {
                $options[] = ['title' => $major->getLabel(), 'value' => $major->value];
            });

        return $options;
    }

    public function getSemesterOptions()
    {
        $options = [];

        $semesters = Semester::where('major', $this->selectedMajor)->get();

        foreach ($semesters as $semester) {
            $options[] = ['title' => $semester->name, 'value' => $semester->id];
        }

        return $options;
    }

    public function updatedSelectedMajor()
    {
        $this->selectedSemester = null;
        $this->selectedSubject = null;
        $this->selectedGroup = null;

        $semesters = Semester::where('major', $this->selectedMajor)->get();

        $options = [];

        foreach ($semesters as $semester) {
            $options[] = ['title' => $semester->name, 'value' => $semester->id];
        }

        $this->semesters = $options;
    }

    public function updatedSelectedSemester()
    {
        $this->selectedSubject = null;
        $this->selectedGroup = null;

        $subjects = Subject::whereHas('semester', function ($query) {
            $query->where('id', $this->selectedSemester)
                ->where('major', $this->selectedMajor);
        })->get();

        $options = [];

        foreach ($subjects as $item) {
            $options[] = ['title' => $item->name, 'value' => $item->id];
        }

        $this->subjects = $options;
    }

    public function updatedSelectedSubject()
    {
        $this->selectedGroup = null;

        $groups = DB::table('groups')
            ->join('group_subject', 'group_subject.group_id', '=', 'groups.id')
            ->where('groups.semester_id', $this->selectedSemester)
            ->where('group_subject.subject_id', $this->selectedSubject)
            ->select('groups.name', 'groups.id')
            ->get();

        $options = [];

        foreach ($groups as $item) {
            $options[] = ['title' => $item->name, 'value' => $item->id];
        }

        $this->groups = $options;
    }
}
