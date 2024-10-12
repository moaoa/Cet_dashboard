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
                <select id="lecture-select" wire:model.live="selectedSubject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                    wire:model.live="selectedGroup"
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
                    <th class="border px-4 py-2 text-center">الحالة</th>
                    <th class="border px-4 py-2 text-center">رقم القيد</th>
                    <th class="border px-4 py-2 text-center">الطالب</th>
                    <th class="border px-4 py-2 text-center">المجموعة</th>
                    <th class="border px-4 py-2">المادة</th>
                    <th class="border px-4 py-2">الغياب</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absenceData as $item)
                <tr>
                    <td class="border flex justify-center px-4 py-2 text-center">
                        @if($item->total_absences >= 3)
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        @else
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>

                        @endif
                    </td>
                    <td class="border px-4 py-2 text-center">{{ $item->ref_number }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->group_name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->subject_name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->total_absences }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>