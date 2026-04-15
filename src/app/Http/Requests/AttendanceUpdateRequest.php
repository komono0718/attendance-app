<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',

            'break_start_0' => 'nullable|date_format:H:i|before:clock_out',
            'break_end_0' => 'nullable|date_format:H:i|after:break_start_0|before:clock_out',

            'break_start_1' => 'nullable|date_format:H:i|before:clock_out',
            'break_end_1' => 'nullable|date_format:H:i|after:break_start_1|before:clock_out',

            'note' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'clock_out.after' => '退勤時間は出勤時間より後にしてください',

            'break_start_0.before' => '休憩開始は退勤時間より前にしてください',
            'break_end_0.after' => '休憩終了は休憩開始より後にしてください',
            'break_end_0.before' => '休憩終了は退勤時間より前にしてください',

            'break_start_1.before' => '休憩開始は退勤時間より前にしてください',
            'break_end_1.after' => '休憩終了は休憩開始より後にしてください',
            'break_end_1.before' => '休憩終了は退勤時間より前にしてください',

            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $in = $this->clock_in;
            $out = $this->clock_out;

            if ($in && $out && $out < $in) {
                $validator->errors()->add(
                    'clock_out',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );
            }

            for ($i = 0; $i < 2; $i++) {

                $start = $this->input("break_start_$i");
                $end = $this->input("break_end_$i");

                if (!is_null($start)) {
                    if (($in && $start < $in) || ($out && $start > $out)) {
                        $validator->errors()->add(
                            "break_start_$i",
                            '休憩時間が不適切な値です'
                        );
                    }
                }

                if ($end) {
                    if (($out && $end > $out) || ($in && $end < $in)) {
                        $validator->errors()->add(
                            "break_end_$i",
                            '休憩時間もしくは退勤時間が不適切な値です'
                        );
                    }
                }
            }
        });
    }
}
