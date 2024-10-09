<div>
    <div>
        <div class="mb-4">
            <label for="lecture-select" class="block font-medium text-gray-700">Select Subject:</label>
            <select id="lecture-select" wire:model="selectedSubject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Select a subject</option>
                @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
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
                Excel
            </button>
        </div>



        @if (!empty($absenceData))
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="border px-4 py-2 text-center">الأستاذ</th>
                    <th class="border px-4 py-2">الغياب</th>
                    <th class="border px-4 py-2">المادة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absenceData as $item)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $item->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->total_absences }}</td>
                    <td class="border px-4 py-2 text-center">{{ $item->subject_name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>