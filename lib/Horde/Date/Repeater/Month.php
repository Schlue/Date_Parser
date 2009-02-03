<?php
class Horde_Date_Repeater_Month extends Horde_Date_Repeater
{
    /**
     * 30 * 24 * 60 * 60
     */
    const MONTH_SECONDS = 2592000;

    public $currentMonthStart;

    public function next($pointer)
    {
        parent::next($pointer);

        if (!$this->currentMonthStart) {
            $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month));
        }
        $direction = ($pointer == 'future') ? 1 : -1;
        $this->currentMonthStart->month += $direction;

        $end = clone($this->currentMonthStart);
        $end->month++;
        return new Horde_Date_Span($this->currentMonthStart, $end);
    }

    public function this($pointer = 'future')
    {
        parent::this($pointer);

        switch ($pointer) {
        case 'future':
            $monthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month, 'day' => $this->now->day + 1));
            $monthEnd = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month + 1));
            break;

        case 'past':
            $monthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month));
            $monthEnd = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month, 'day' => $this->now->day));
            break;

        case 'none':
            $monthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month));
            $monthEnd = new Horde_Date(array('year' => $this->now->year, 'month' => $this->now->month + 1));
            break;
        }

        return new Horde_Date_Span($monthStart, $monthEnd);
    }

    public function offset($span, $amount, $pointer)
    {
        $direction = ($pointer == 'future') ? 1 : -1;
        return $span->add(array('month' => $amount * $direction));
    }

    public function width()
    {
        return self::MONTH_SECONDS;
    }

    public function __toString()
    {
        return parent::__toString() . '-month';
    }

}
