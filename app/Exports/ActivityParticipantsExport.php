<?php

namespace App\Exports;

use App\Models\ActivityParticipant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityParticipantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $activityId;

    public function __construct($activityId)
    {
        $this->activityId = $activityId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ActivityParticipant::with(['user.biodata.prodi', 'user.biodata.fakultas'])
            ->where('activity_id', $this->activityId)
            ->join('users', 'activity_participants.user_id', '=', 'users.id')
            ->orderBy('users.no_induk')
            ->select('activity_participants.*')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Program Studi',
            'Fakultas'
        ];
    }

    /**
     * @param mixed $participant
     * @return array
     */
    public function map($participant): array
    {
        return [
            $participant->user->no_induk ?? '-',
            $participant->user->name ?? '-',
            $participant->user->biodata->prodi->prodi ?? '-',
            $participant->user->biodata->fakultas->fakultas ?? '-'
        ];
    }
}
