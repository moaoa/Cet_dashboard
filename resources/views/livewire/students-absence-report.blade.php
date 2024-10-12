<div>
    <div>
        <div class="mb-4 flex gap-2 justify-between">
            <label for="major-select" class="block font-medium text-gray-700 ">
                اختر التخصص
                <select
                    id="major-select"
                    wire:model.live="selectedMajor"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">اختر التخصص</option>
                    @foreach ($this->getMajorOptions() as $major)
                    <option value="{{ $major['value'] }}">{{ $major['title'] }}</option>
                    @endforeach
                </select>
            </label>


            <label
                for="semester-select"
                class="block font-medium text-gray-700 ">
                اختر الفصل الدراسي
                <select id="semester-select" wire:model.live="selectedSemester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">اختر الفصل الدراسي</option>
                    @foreach ($semesters as $semester)
                    <option value="{{ $semester['value'] }}">{{ $semester['title'] }}</option>
                    @endforeach
                </select>
            </label>

            <label for="lecture-select" class="block font-medium text-gray-700 ">
                اختر مادة
                <select id="lecture-select" wire:model="selectedSubject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">اختر مادة</option>
                    @foreach ($subjects as $subject)
                    <option value="{{ $subject['value'] }}">{{ $subject['title'] }}</option>
                    @endforeach
                </select>
            </label>

            <label for="lecture-select" class="block font-medium text-gray-700 ">
                اختر المجموعة
                <select
                    id="lecture-select"
                    wire:model="selectedSubject"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">اختر المجموعة</option>
                    @foreach ($groups as $group)
                    <option value="{{ $group['value'] }}">{{ $group['title'] }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="mb-4">
            <button
                wire:click="generateReport"
                class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded">
                إنشاء التقرير
            </button>

            <button
                wire:click="exportToExcel"
                class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded">
                تقرير Excel
            </button>
        </div>



        @if (!empty($absenceData))
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="border px-4 py-2 text-center">الطالب</th>
                    <th class="border px-4 py-2 text-center">رقم القيد</th>
                    <th class="border px-4 py-2">الغياب</th>
                    <th class="border px-4 py-2">المادة</th>
                    <th class="border px-2 py-2">نسبة الغياب</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absenceData as $item)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $item->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->ref_number }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->total_absences }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->subject_name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>