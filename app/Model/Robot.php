<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    protected $table = 'robot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id','x_pos', 'y_pos','heading','commands',
    ];


    protected $appends = [
    	'name', 'position'
    ];


    public function getNameAttribute()
    {
    	return sprintf('Robot %d', $this->id);
    }


    public function getPositionAttribute()
    {
    	return sprintf('%d %d %s', $this->x_pos, $this->y_pos, $this->heading);
    }


    public function setHeadingAttribute($value)
    {
    	$this->attributes['heading'] = strtoupper($value);
    }


    public function setCommandsAttribute($value)
    {
    	$this->attributes['commands'] = strtoupper($value);
    } 


    public function forward($grid_pos, $heading){
        $pos = explode(' ', $grid_pos);
        $x = $pos[0]; $y = $pos[1];

        if ($heading == 'N') $x--;
        else if ($heading == 'E') $y++;
        else if ($heading == 'S') $x++;
        else if ($heading == 'W') $y--;

        return sprintf('%s %s', $x, $y);
    }


    public function rotate_left($heading)
    {
        if ($heading == 'N') $heading = 'W';
        else if ($heading == 'E') $heading = 'N';
        else if ($heading == 'S') $heading = 'E';
        else if ($heading == 'W') $heading = 'S';

        return $heading;
    }
    

    public function rotate_right($heading)
    {
        if ($heading == 'N') $heading = 'E';
        else if ($heading == 'E') $heading = 'S';
        else if ($heading == 'S') $heading = 'W';
        else if ($heading == 'W') $heading = 'N';

        return $heading;
    }
}
