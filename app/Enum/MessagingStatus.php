<?php


namespace App\Enum;


class MessagingStatus extends Enum
{
    const OUTSTANDING = 'Outstanding';
    const UNDER_REVIEW = 'Under Review';
    const LATE = 'Late';
    const APPROVED = 'Approved';
}
