<?php
class Horde_Date_Parser_Locale_Base_Repeater_MonthName extends Horde_Date_Parser_Locale_Base_Repeater
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
            $targetMonth = $this->_monthNumber($this->type);
            switch ($pointer) {
            case 'future':
                if ($this->now->month > $targetMonth) {
                    $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $targetMonth));
                } else {
                    $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year + 1, 'month' => $targetMonth));
                }
                break;

            case 'none':
                if ($this->now->month <= $targetMonth) {
                    $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $targetMonth));
                } else {
                    $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year + 1, 'month' => $targetMonth));
                }
                break;

            case 'past':
                if ($this->now->month > $targetMonth) {
                    $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year, 'month' => $targetMonth));
                } else {
                    $this->currentMonthStart = new Horde_Date(array('year' => $this->now->year - 1, 'month' => $targetMonth));
                }
                break;
            }
        } else {
            switch ($pointer) {
            case 'future':
                $this->currentMonthStart->year++;
                break;

            case 'past':
                $this->currentMonthStart->year--;
                break;
            }
        }

        return new Horde_Date_Span($this->currentMonthStart, new Horde_Date(array('year' => $this->currentMonthStart->year, 'month' => $this->currentMonthStart->month + 1)));
    }

    public function this($pointer = 'future')
    {
        parent::this($pointer);

        switch ($pointer) {
        case 'past':
            return $this->next($pointer);

        case 'future':
        case 'none':
            return $this->next('none');
        }
    }

    public function width()
    {
        return self::MONTH_SECONDS;
    }

    public function index()
    {
        return $this->_monthNumber($this->type);
    }

    public function __toString()
    {
        return parent::__toString() . '-monthname-' . $this->type;
    }

    protected function _monthNumber($monthName)
    {
        $months = array(
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12,
        );
        if (!isset($months[$monthName])) {
            throw new InvalidArgumentException('Invalid month name "' . $monthName . '"');
        }
        return $months[$monthName];
    }

}