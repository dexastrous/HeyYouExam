<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop';    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'width', 'height',
    ]; 


    public function robots()
    {
    	return $this->hasMany('App\Model\Robot'); 
    }


    public function getSimulationResult(){
        return $this->robots->map(function($robot){
            $headings = [$robot->heading];
            $grid_pos = [sprintf('%s %s', $robot->x_pos, $robot->y_pos)];
            $movements = [$robot->position];
            
            foreach (str_split($robot->commands) as $step) {
                switch ($step) {
                    case 'L': // Left Movement
                        array_push($headings, $robot->rotate_left(end($headings)));
                        array_push($grid_pos, end($grid_pos));                            
                        break;
                    case 'R': // Right Movement
                        array_push($headings, $robot->rotate_right(end($headings)));
                        array_push($grid_pos, end($grid_pos));
                        break;
                    case 'M': // Forward Movement
                        array_push($grid_pos, $robot->forward(end($grid_pos),end($headings)));
                        array_push($headings, end($headings));
                        break;                            
                }
                /*
                 * push new position in the list of movements
                 */
                array_push($movements, sprintf('%s %s',end($grid_pos), end($headings)));                    
            }
                
            return [
                'id' => $robot->id,
            	'robot' => $robot->name,
                'headings' => $headings,
                'grid_pos' => $grid_pos,
                'movements' => $movements
            ];        
        });    	
    }


    public function getSimulationStatus($result){
        $max = $result->max(function($item){
            return count($item['grid_pos']);
        });

        $max--;
        $status = [];
        foreach (range(0, $max) as $key) {
            $lawns = $result->map(function($item) use ($key, $max) {
                for ($i=count($item['grid_pos']); $i<=$max ; $i++) { 
                    array_push($item['grid_pos'], end($item['grid_pos']));    
                }     
                return $item['grid_pos'][$key];
            });                        

            /*
             * Push status per lawns movement
             */
            array_push($status, $this->getStatus($lawns));
                        
        }

        return $status;
    }


    private function hasCollision($lawns)
    {
        return $lawns->count() !== $lawns->unique()->count();
    }


    private function isOutOfBounce($lawns){
        return $lawns->contains(function($value, $key){
        	$position = explode(' ', $value);        
        	$x = ($position[0] < 0) || ($position[0] > $this->width);
        	$y = ($position[1] < 0) || ($position[1] > $this->height);

            return $x || $y;
        });
    }

    private function getStatus($lawns){
        $message = Collect(['Ok']);
        if ($this->hasCollision($lawns)) { 
            $message = $message->filter(function($item){
                return strtolower($item) !== 'ok';
            });         
            $message->push('Collision');
        }

        if ($this->isOutOfBounce($lawns)) {
            $message = $message->filter(function($item){
                return strtolower($item) !== 'ok';
            });         
            $message->push('Out of bounce');            
        }

        return $message->toArray();        
    }

}
