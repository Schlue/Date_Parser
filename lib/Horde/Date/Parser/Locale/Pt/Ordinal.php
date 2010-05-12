<?php
class Horde_Date_Parser_Locale_Pt_Ordinal extends Horde_Date_Parser_Locale_Base_Ordinal
{

/*
    public $ordinalRegex = '/^(\d*)(\.|\xBA|\xAA)$/';
    public $ordinalDayRegex = '/^(\d*)(\.|\xBA|\xAA)$/';
*/
    public $ordinalRegex = '/\b(\d*)(\.|\xBA|\xAA)?\b/';
    public $ordinalDayRegex = '/\b(\d*)(\.|\xBA|\xAA)?\b/';

}
