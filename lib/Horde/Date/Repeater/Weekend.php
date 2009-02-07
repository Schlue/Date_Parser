<?php
class Horde_Date_Repeater_Weekend extends Horde_Date_Repeater
{
    /**
     * (2 * 24 * 60 * 60)
     */
    const WEEKEND_SECONDS = 172800;

    public $currentWeekStart;

    public function next($pointer)
    {
        parent::next($pointer);

        if (!$this->currentWeekStart) {
            switch ($pointer) {
            case 'future':
                $saturdayRepeater = new Horde_Date_Repeater_DayName('saturday');
                $saturdayRepeater->now = $this->now;
                $nextSaturdaySpan = $saturdayRepeater->next('future');
                $this->currentWeekStart = $nextSaturdaySpan->begin;
                break;

            case 'past':
                $saturdayRepeater = new Horde_Date_Repeater_DayName('saturday');
                $saturdayRepeater->now = $this->now;
                $saturdayRepeater->now->day++;
                $lastSaturdaySpan = $saturdayRepeater->next('past');
                $this->currentWeekStart = $lastSaturdaySpan->begin;
                break;
            }
        } else {
            $direction = ($pointer == 'future') ? 1 : -1;
            $this->currentWeekStart->day += $direction * 7;
        }

        return new Horde_Date_Span($this->currentWeekStart, $this->currentWeekStart->add(array('day' => 2)));
    }

    public function this($pointer = 'future')
    {
        parent::this($pointer);

        switch ($pointer) {
        case 'future':
        case 'none':
            $saturdayRepeater = new Horde_Date_Repeater_DayName('saturday');
            $saturdayRepeater->now = $this->now;
            $thisSaturdaySpan = $saturdayRepeater->this('future');
            return new Horde_Date_Span($thisSaturdaySpan->begin, $thisSaturdaySpan->begin->add(array('day' => 2)));

        case 'past':
            $saturdayRepeater = new Horde_Date_Repeater_DayName('saturday');
            $saturdayRepeater->now = $this->now;
            $lastSaturdaySpan = $saturdayRepeater->this('past');
            return new Horde_Date_Span($lastSaturdaySpan->begin, $lastSaturdaySpan->begin->add(array('day' => 2)));
        }
    }

    public function offset($span, $amount, $pointer)
    {
        $direction = ($pointer == 'future') ? 1 : -1;
        $weekend = new self();
        $weekend->now = clone($span->begin);
        $start = $weekend->next($pointer)->begin;
        $start->day += ($amount - 1) * $direction * 7;
        return new Horde_Date_Span($start, $start->add($span->width()));
    }

    public function width()
    {
        return self::WEEKEND_SECONDS;
    }

    public function __toString()
    {
        return parent::__toString() . '-weekend';
    }

}
